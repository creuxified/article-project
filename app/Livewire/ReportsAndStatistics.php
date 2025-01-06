<?php

namespace App\Livewire;

use Livewire\Component;
use GuzzleHttp\Client;
use DOMDocument;
use DOMXPath;
use App\Models\Publication;
use Illuminate\Support\Facades\Auth;

class ReportsAndStatistics extends Component
{
    public $publications;
    public $formattedChartData;
    public $formattedCitationData;
    public $scholar_id;
    public $scopus_id;
    public $message;

    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function mount()
    {
        $this->loadPublications();
    }

    public function loadPublications()
    {
        $user = Auth::user();

        // Role-based publication fetching
        if ($user->role_id == 2) {
            // Dosen: Hanya publikasi miliknya
            $this->publications = Publication::where('user_id', $user->id)->get();
        } elseif ($user->role_id == 3) {
            // Admin Prodi: Data berdasarkan relasi program_id
            $programId = $user->program_id;
            $this->publications = Publication::whereHas('user', function ($query) use ($programId) {
                $query->where('program_id', $programId);
            })->get();
        } elseif (in_array($user->role_id, [4, 5])) {
            // Admin Fakultas dan Universitas: Semua data publikasi
            $this->publications = Publication::all();
        } else {
            // Role tidak dikenal
            $this->publications = collect();
        }

        // Prepare data for publication counts
        $chartData = $this->publications->groupBy('publication_date')->map(function ($yearGroup) {
            return $yearGroup->groupBy('source')->map(function ($sourceGroup) {
                return $sourceGroup->count();
            });
        });

        // Format data for publication Highcharts
        $this->formattedChartData = [];
        foreach ($chartData as $year => $sources) {
            foreach ($sources as $source => $count) {
                $this->formattedChartData[] = [
                    'year' => $year,
                    'source' => $source,
                    'count' => $count,
                ];
            }
        }

        // Prepare data for citation counts
        $citationData = $this->publications->groupBy('publication_date')->map(function ($yearGroup) {
            return $yearGroup->groupBy('source')->map(function ($sourceGroup) {
                return $sourceGroup->sum('citations');
            });
        });

        // Format data for citation Highcharts
        $this->formattedCitationData = [];
        foreach ($citationData as $year => $sources) {
            foreach ($sources as $source => $count) {
                $this->formattedCitationData[] = [
                    'year' => $year,
                    'source' => $source,
                    'count' => $count,
                ];
            }
        }
    }

    public function scrapeAndShow()
    {
        $this->validate([
            'scholar_id' => 'required|string',
            'scopus_id' => 'required|string',
        ]);

        // Scrape data from Google Scholar and Scopus simultaneously
        $scholarData = $this->scrapeScholar($this->scholar_id) ?? [];
        $scopusData = $this->scrapeScopus($this->scopus_id) ?? [];

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

            $this->message = "$scholarCount publications fetched from Google Scholar and $scopusCount publications fetched from Scopus. Total: $totalCount publications fetched successfully.";
        } else {
            $this->message = 'No publications found or failed to scrape data.';
        }

        // Reload the publications data after scraping
        $this->loadPublications();
    }

    protected function saveOrUpdatePublication($article)
    {
        // Check if publication already exists by DOI or link
        $existingPublication = Publication::where('link', $article['link'])->first();

        // Ensure citations are an integer (default to 0 if empty)
        $article['citations'] = empty($article['citations']) ? 0 : (int) $article['citations'];

        // Add user_id to the article data
        $article['user_id'] = Auth::id();

        if ($existingPublication) {
            // Update existing publication if there are changes
            $existingPublication->update($article);
        } else {
            // Create new publication if it doesn't exist
            Publication::create($article);
        }
    }

    private function scrapeScholar($author_id)
    {
        $base_url = "https://scholar.google.com/citations?user=$author_id&cstart=0&pagesize=100";
        $html = $this->scrape($base_url);

        return $this->parseArticles($html, 'Google Scholar');
    }

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
                    'user_id' => Auth::id(),
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

    private function scrape($url)
    {
        $response = $this->client->request('GET', $url);
        return $response->getBody()->getContents();
    }

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
                'source' => $source,
            ];
        }

        return $articles;
    }

    private function formatPublicationDate($year)
    {
        if ($year === 'Unknown' || empty($year)) {
            return null;
        }

        return \Carbon\Carbon::createFromFormat('Y', $year)->startOfYear()->toDateString();
    }

    public function render()
    {
        return view('livewire.reports-and-statistics');
    }
}
