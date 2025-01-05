<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use DOMDocument;
use DOMXPath;
use App\Models\Publication;
use Illuminate\Http\Request;

class ScraperController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function showPublications()
    {
        $user = auth()->user();

        // Role 2: Dosen
        if ($user->role_id == 2) {
            $publications = Publication::where('user_id', $user->id)->get();
        }
        // Role 3: Admin Prodi
        elseif ($user->role_id == 3) {
            $programId = $user->program_id;
            $publications = Publication::whereHas('user', function ($query) use ($programId) {
                $query->where('program_id', $programId); // Filter berdasarkan program_id
            })->get();
        }
        // Role 4: Admin Fakultas
        elseif ($user->role_id == 4) {
            $facultyId = $user->faculty_id;
            $publications = Publication::whereHas('user', function ($query) use ($facultyId) {
                $query->where('faculty_id', $facultyId); // Filter berdasarkan faculty_id
            })
                ->with(['user.program' => function ($query) {
                    $query->select('id', 'name'); // Ambil kolom id dan name dari tabel study_programs
                }])
                ->get();

            // Map hasil publikasi dan tambahkan program (study_program) name
            $publicationsWithStudyProgram = $publications->map(function ($publication) {
                $publication->study_program = $publication->user->program->name; // Menambahkan program studi (study_program) ke publikasi
                return $publication;
            });
        }
        // // Role 4: Admin Fakultas
        // elseif ($user->role_id == 4) {
        //     $facultyId = $user->faculty_id;
        //     $publications = Publication::whereHas('user', function ($query) use ($facultyId) {
        //         $query->where('faculty_id', $facultyId); // Filter berdasarkan faculty_id
        //     })
        //         ->with(['user.studyProgram' => function ($query) {
        //             $query->select('id', 'name'); // Ambil kolom id dan name dari tabel study_programs
        //         }])
        //         ->get();

        //     // Map hasil publikasi dan tambahkan study_program name
        //     $publicationsWithStudyProgram = $publications->map(function ($publication) {
        //         $publication->study_program = $publication->user->studyProgram->name; // Menambahkan study_program ke publikasi
        //         return $publication;
        //     });
        // }
        // Role 5: Admin Universitas
        elseif ($user->role_id == 5) {
            $publications = Publication::with(['user.studyProgram' => function ($query) {
                $query->select('id', 'name'); // Ambil nama program studi
            }, 'user.faculty' => function ($query) {
                $query->select('id', 'name'); // Ambil nama fakultas
            }])
                ->get();

            // Map hasil publikasi dan tambahkan study_program dan faculty_name
            $publicationsWithDetails = $publications->map(function ($publication) {
                $publication->study_program = $publication->user->studyProgram->name; // Tambahkan study_program ke publikasi
                $publication->faculty_name = $publication->user->faculty->name; // Tambahkan faculty_name ke publikasi
                return $publication;
            });
        } else {
            // Role tidak dikenal
            $publications = collect(); // Koleksi kosong
        }


        // Prepare data for publication counts
        $chartData = $publications->groupBy('publication_date')->map(function ($yearGroup) {
            return $yearGroup->groupBy('source')->map(function ($sourceGroup) {
                return $sourceGroup->count();
            });
        });

        // Format data for publication Highcharts
        $formattedChartData = [];
        foreach ($chartData as $year => $sources) {
            foreach ($sources as $source => $count) {
                $formattedChartData[] = [
                    'year' => $year,
                    'source' => $source,
                    'count' => $count,
                ];
            }
        }

        // Prepare data for citation counts
        $citationData = $publications->groupBy('publication_date')->map(function ($yearGroup) {
            return $yearGroup->groupBy('source')->map(function ($sourceGroup) {
                return $sourceGroup->sum('citations'); // Summing up citations
            });
        });

        // Format data for citation Highcharts
        $formattedCitationData = [];
        foreach ($citationData as $year => $sources) {
            foreach ($sources as $source => $count) {
                $formattedCitationData[] = [
                    'year' => $year,
                    'source' => $source,
                    'count' => $count,
                ];
            }
        }

        return view('scraper.index', compact('publications', 'formattedChartData', 'formattedCitationData'));
    }

    /**
     * Handle form submission for scraping data from Google Scholar and Scopus.
     */
    public function scrapeAndShow(Request $request)
    {
        $request->validate([
            'author_id_scholar' => 'required|string',
            'author_id_scopus' => 'required|string',
        ]);

        $scholar_id = $request->input('author_id_scholar');
        $scopus_id = $request->input('author_id_scopus');

        // Scrape data from Google Scholar and Scopus simultaneously
        $scholarData = $this->scrapeScholar($scholar_id) ?? [];
        $scopusData = $this->scrapeScopus($scopus_id) ?? [];

        // Merge data from both sources
        $allData = array_merge($scholarData, $scopusData);

        // Count publications from each source
        $scholarCount = count($scholarData);
        $scopusCount = Publication::where('source', 'Scopus')->count();
        $totalCount = $scholarCount + $scopusCount;

        // Check if data is scraped
        if ($totalCount > 0) {
            // Save or update publications in the database
            foreach ($allData as $article) {
                $this->saveOrUpdatePublication($article);
            }

            // Provide feedback with the count of publications fetched from each source
            return redirect()->route('scraper.index')
                ->with('success', "$scholarCount publications fetched from Google Scholar and $scopusCount publications fetched from Scopus. Total: $totalCount publications fetched successfully.")
                ->with('scrapedData', $allData);
        } else {
            // If no articles were scraped, show an error message
            return redirect()->route('scraper.index')
                ->with('error', 'No publications found or failed to scrape data.');
        }
    }

    /**
     * Save or update the publication data.
     */
    protected function saveOrUpdatePublication($article)
    {
        // Check if publication already exists by DOI or link
        $existingPublication = Publication::where('link', $article['link'])->first();

        // Ensure citations are an integer (default to 0 if empty)
        $article['citations'] = empty($article['citations']) ? 0 : (int) $article['citations'];

        // Add user_id to the article data
        $article['user_id'] = auth()->id();

        if ($existingPublication) {
            // Update existing publication if there are changes
            $existingPublication->update($article);
        } else {
            // Create new publication if it doesn't exist
            Publication::create($article);
        }
    }

    /**
     * Scrape data from Google Scholar.
     */
    private function scrapeScholar($author_id)
    {
        $base_url = "https://scholar.google.com/citations?user=$author_id&cstart=0&pagesize=100";
        $html = $this->scrape($base_url);

        return $this->parseArticles($html, 'Google Scholar');
    }

    /**
     * Scrape data from Scopus.
     */
    public function scrapeScopus($author_id)
    {
        $api_key = '1dad71e1f3b375d6c4f111ac047ed1e9';
        $base_url = 'https://api.elsevier.com/content/';
        $articles = $this->getScopusArticles($author_id, $api_key, $base_url);

        if ($articles) {
            foreach ($articles['search-results']['entry'] as $article) {
                $doi = $article['prism:doi'] ?? null;

                $existingPublication = Publication::where('link', $doi)->first();

                $newData = [
                    'user_id' => auth()->id(),
                    'title' => $article['dc:title'] ?? 'Unknown',
                    'journal_name' => $article['prism:publicationName'] ?? 'Unknown',
                    'publication_date' => $article['prism:coverDate'] ?? null,
                    'citations' => $article['citedby-count'] ?? 0,
                    'link' => $doi,
                    'source' => 'Scopus',
                ];

                if ($existingPublication) {
                    // Periksa apakah ada perubahan di kolom lain
                    $isUpdated = false;
                    foreach ($newData as $key => $value) {
                        if ($existingPublication->$key !== $value) {
                            $isUpdated = true;
                            break;
                        }
                    }

                    // Jika ada perubahan, lakukan pembaruan data
                    if ($isUpdated) {
                        $existingPublication->update($newData);
                    }
                } else {
                    // Jika DOI belum ada, tambahkan data baru
                    Publication::create($newData);
                }
            }
        } else {
            file_put_contents('scopus_error_log.txt', "Failed to scrape Scopus for author ID: $author_id\n", FILE_APPEND);
        }
    }


    /**
     * Fetch articles from Scopus API.
     */
    private function getScopusArticles($author_id, $api_key, $base_url)
    {
        $url = $base_url . "search/scopus?query=AU-ID($author_id)&apiKey=$api_key";
        $client = new Client();

        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                return $data;
            }
        } catch (\Exception $e) {
            file_put_contents('scopus_error_log.txt', "Error fetching Scopus data: " . $e->getMessage() . "\n", FILE_APPEND);
        }

        return null;
    }

    /**
     * Fetch HTML content from a URL using Guzzle.
     */
    private function scrape($url)
    {
        $response = $this->client->request('GET', $url);
        return $response->getBody()->getContents();
    }

    /**
     * Parse articles from HTML content (Google Scholar).
     */
    private function parseArticles($html, $source)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);

        $xpath = new DOMXPath($dom);

        $articles = [];
        $rows = $xpath->query('//tr[@class="gsc_a_tr"]');

        foreach ($rows as $row) {
            $titleNode = $xpath->query('.//a[@class="gsc_a_at"]', $row)->item(0);
            $journalNode = $xpath->query('.//div[@class="gs_gray"]', $row)->item(1);
            $citationsNode = $xpath->query('.//a[@class="gsc_a_ac gs_ibl"]', $row)->item(0);
            $linkNode = $xpath->query('.//a[@class="gsc_a_at"]', $row)->item(0);
            $yearNode = $xpath->query('.//span[@class="gsc_a_h gsc_a_hc gs_ibl"]', $row)->item(0);

            // Ensure nodes are not null before extracting values
            $articles[] = [
                'title' => trim($titleNode ? $titleNode->nodeValue : 'No Title'),
                'journal_name' => trim($journalNode ? $journalNode->nodeValue : 'No Journal'),
                'citations' => trim($citationsNode ? $citationsNode->nodeValue : '0'),
                'link' => $linkNode ? "https://scholar.google.com" . $linkNode->getAttribute('href') : 'No Link',
                'publication_date' => $this->formatPublicationDate(trim($yearNode ? $yearNode->nodeValue : 'Unknown')),
                'source' => $source, // Store the data source
            ];
        }

        return $articles;
    }

    /**
     * Format the publication date.
     */
    private function formatPublicationDate($year)
    {
        if ($year === 'Unknown' || empty($year)) {
            return null;
        }

        return \Carbon\Carbon::createFromFormat('Y', $year)->startOfYear()->toDateString();
    }

    /**
     * Delete all data related to the logged-in user.
     */
    public function deleteData(Request $request)
    {
        // Delete all publications associated with the current user
        Publication::where('user_id', auth()->id())->delete();

        return redirect()->route('scraper.index')
            ->with('success', 'All your data has been deleted successfully.');
    }
}
