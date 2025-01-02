<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Publication;

class ScholarController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function index(Request $request)
    {
        // Menangkap input ID user dari query string
        $id_user_scholar = $request->query('id_user_scholar');

        // Jika ID user tidak ada, tampilkan form tanpa hasil scraping
        if (!$id_user_scholar) {
            return view('scholar.index', ['dataScrapping' => null]);
        }

        // Mengambil parameter cstart dan pagesize dari query string, jika ada
        $cstart = $request->query('cstart', 0); // Default to 0 if not set
        $pagesize = $request->query('pagesize', 100); // Default to 100 if not set

        // Gantilah URL dengan ID user dinamis dan parameter cstart serta pagesize
        $url = "https://scholar.google.com/citations?user=$id_user_scholar&cstart=$cstart&pagesize=$pagesize";

        // Lakukan scraping pada URL tersebut
        $dataScrapping = $this->scrape($url);

        // Simpan hasil scraping ke database
        $this->savePublications($dataScrapping, $id_user_scholar);

        // Kembalikan data hasil scraping ke view
        return view('scholar.index', ['dataScrapping' => $dataScrapping]);
    }

    private function scrape($url)
    {
        // Mengirim permintaan GET ke URL
        $response = $this->client->request('GET', $url);

        // Mengambil konten HTML dari respons
        $html = $response->getBody()->getContents();

        // Mengolah konten HTML
        return $this->parseHtml($html);
    }

    private function parseHtml($html)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);

        $xpath = new \DOMXPath($dom);

        // Mengambil data profil
        $profileData = [
            'name' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@id="gsc_prf_in"]'),
            'affiliation' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@class="gsc_prf_il"]'),
            'email_verified' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@id="gsc_prf_ivh"]'),
            'interests' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@id="gsc_prf_int"]'),
            'photo_url' => $this->extractAttribute($xpath, '//div[@id="gsc_prf_pu"]//img', 'src')
        ];

        // Mengambil data "Cited by"
        $citedByData = [
            'citations_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[1]//td[2]'),
            'citations_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[1]//td[3]'),
            'h_index_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[2]//td[2]'),
            'h_index_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[2]//td[3]'),
            'i10_index_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[3]//td[2]'),
            'i10_index_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[3]//td[3]')
        ];

        // Mengambil data artikel dan grafik
        $articleData = $this->extractArticleData($xpath);
        $chartData = $this->extractChartData($xpath);

        return [
            'profile' => $profileData,
            'cited_by' => $citedByData,
            'articles' => $articleData,
            'chart' => $chartData
        ];
    }

    private function extractArticleData($xpath)
    {
        $titles = $xpath->query('//tr[@class="gsc_a_tr"]//a[@class="gsc_a_at"]');
        $years = $xpath->query('//tr[@class="gsc_a_tr"]//span[@class="gsc_a_h gsc_a_hc gs_ibl"]');
        $citations = $xpath->query('//tr[@class="gsc_a_tr"]//a[@class="gsc_a_ac gs_ibl"]');
        $links = $xpath->query('//tr[@class="gsc_a_tr"]//a[@class="gsc_a_at"]');

        $articles = [];
        foreach ($titles as $index => $title) {
            $year = $years->length > $index ? trim($years->item($index)->nodeValue) : 'Tahun tidak ditemukan';
            $citationCount = $citations->length > $index ? trim($citations->item($index)->nodeValue) : '0';

            $articles[] = [
                'title' => trim($title->nodeValue),
                'year' => $year,
                'citations' => $citationCount,
                'url' => $links->length > $index ? 'https://scholar.google.com' . $links->item($index)->getAttribute('href') : null
            ];
        }

        return $articles;
    }

    private function extractText($xpath, $query)
    {
        $nodes = $xpath->query($query);
        return $nodes->length > 0 ? trim($nodes->item(0)->nodeValue) : null;
    }

    private function extractAttribute($xpath, $query, $attribute)
    {
        $nodes = $xpath->query($query);
        return $nodes->length > 0 ? $nodes->item(0)->getAttribute($attribute) : null;
    }

    private function extractTableCell($xpath, $query)
    {
        $nodes = $xpath->query($query);
        return $nodes->length > 0 ? trim($nodes->item(0)->nodeValue) : null;
    }

    private function extractChartData($xpath)
    {
        $years = $xpath->query('//div[@class="gsc_md_hist_b"]//span[@class="gsc_g_t"]');
        $counts = $xpath->query('//div[@class="gsc_md_hist_b"]//a[@class="gsc_g_a"]//span[@class="gsc_g_al"]');

        $chartData = [];
        foreach ($years as $index => $year) {
            $yearText = trim($year->nodeValue);
            $count = $counts->length > $index ? trim($counts->item($index)->nodeValue) : '0';
            $chartData[] = ['year' => $yearText, 'count' => $count];
        }
        return $chartData;
    }

    private function savePublications($dataScrapping, $userId)
    {
        // Loop melalui setiap artikel data
        foreach ($dataScrapping['articles'] as $article) {
            // Pastikan tanggal publikasi dalam format yang valid
            $publicationDate = $this->formatPublicationDate($article['year']);

            // Pastikan jumlah sitasi adalah integer yang valid (default ke 0 jika kosong atau tidak valid)
            $citations = is_numeric($article['citations']) ? (int) $article['citations'] : 0;

            // Periksa apakah artikel sudah ada di database berdasarkan URL atau judul
            $existingPublication = Publication::where('link', $article['url'])
                ->where('user_id', $userId)  // Pastikan milik pengguna yang sama
                ->first();

            // Jika artikel sudah ada, lakukan update
            if ($existingPublication) {
                // Update artikel yang sudah ada
                $existingPublication->update([
                    'title' => $article['title'],
                    'journal_name' => 'Google Scholar',
                    'publication_date' => $publicationDate,
                    'citations' => $citations,
                    'author_name' => $dataScrapping['profile']['name'],
                    'source' => 'Google Scholar',
                ]);
            } else {
                // Jika artikel belum ada, buat entri baru
                if ($article['title'] && $publicationDate) {
                    Publication::create([
                        'user_id' => auth()->id(), // User ID saat ini
                        'title' => $article['title'], // Judul artikel
                        'journal_name' => 'Google Scholar', // Sumber artikel
                        'publication_date' => $publicationDate, // Tanggal publikasi
                        'citations' => $citations, // Jumlah sitasi
                        'link' => $article['url'], // URL artikel
                        'author_name' => $dataScrapping['profile']['name'], // Nama penulis dari profil
                        'source' => 'Google Scholar', // Sumber data publikasi
                    ]);
                }
            }
        }
    }


    private function formatPublicationDate($year)
    {
        // If the date is just a year, set a default month and day (e.g., January 1st)
        if (preg_match('/^\d{4}$/', $year)) {
            return $year . '-01-01'; // Default to January 1st
        }

        // If the year is empty or invalid, return NULL
        if (empty($year)) {
            return null;
        }

        // Otherwise, return the date as is (assuming it's in a valid format)
        return $year;
    }
}
