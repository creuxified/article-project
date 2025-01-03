<?php

// namespace App\Services;

// use GuzzleHttp\Client;

// class ScraperService
// {
//     protected $client;

//     public function __construct()
//     {
//         $this->client = new Client();
//     }

//     public function scrape($url)
//     {
//         // Mengirim permintaan GET ke URL
//         $response = $this->client->request('GET', $url);

//         // Mengambil konten HTML dari respons
//         $html = $response->getBody()->getContents();

//         // Mengolah konten HTML
//         return $this->parseHtml($html);
//     }

//     protected function parseHtml($html)
//     {
//         $dom = new \DOMDocument();
//         @$dom->loadHTML($html); // '@' untuk mengabaikan peringatan HTML yang tidak valid

//         $xpath = new \DOMXPath($dom);

//         // Mengambil data profil
//         $profileData = [
//             'name' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@id="gsc_prf_in"]'),
//             'affiliation' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@class="gsc_prf_il"]'),
//             'email_verified' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@id="gsc_prf_ivh"]'),
//             'interests' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@id="gsc_prf_int"]'),
//             'photo_url' => $this->extractAttribute($xpath, '//div[@id="gsc_prf_pu"]//img', 'src')
//         ];

//         // Mengambil data "Cited by"
//         $citedByData = [
//             'citations_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[1]//td[2]'),
//             'citations_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[1]//td[3]'),
//             'h_index_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[2]//td[2]'),
//             'h_index_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[2]//td[3]'),
//             'i10_index_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[3]//td[2]'),
//             'i10_index_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[3]//td[3]')
//         ];

//         // Mengambil data artikel dan grafik
//         $articleData = $this->extractArticleData($xpath);
//         $chartData = $this->extractChartData($xpath);

//         return [
//             'profile' => $profileData,
//             'cited_by' => $citedByData,
//             'articles' => $articleData,
//             'chart' => $chartData
//         ];
//     }

//     private function extractArticleData($xpath)
//     {
//         $titles = $xpath->query('//tr[@class="gsc_a_tr"]//a[@class="gsc_a_at"]');
//         $years = $xpath->query('//tr[@class="gsc_a_tr"]//span[@class="gsc_a_h gsc_a_hc gs_ibl"]');
//         $citations = $xpath->query('//tr[@class="gsc_a_tr"]//a[@class="gsc_a_ac gs_ibl"]');
//         $links = $xpath->query('//tr[@class="gsc_a_tr"]//a[@class="gsc_a_at"]');

//         $articles = [];
//         foreach ($titles as $index => $title) {
//             $year = $years->length > $index ? trim($years->item($index)->nodeValue) : 'Tahun tidak ditemukan';
//             $citationCount = $citations->length > $index ? trim($citations->item($index)->nodeValue) : '0';

//             $articles[] = [
//                 'title' => trim($title->nodeValue),
//                 'year' => $year,
//                 'citations' => $citationCount,
//                 'url' => $links->length > $index ? 'https://scholar.google.com' . $links->item($index)->getAttribute('href') : null
//             ];
//         }

//         return $articles;
//     }

//     private function extractText($xpath, $query)
//     {
//         $nodes = $xpath->query($query);
//         return $nodes->length > 0 ? trim($nodes->item(0)->nodeValue) : null;
//     }

//     private function extractAttribute($xpath, $query, $attribute)
//     {
//         $nodes = $xpath->query($query);
//         return $nodes->length > 0 ? $nodes->item(0)->getAttribute($attribute) : null;
//     }

//     private function extractTableCell($xpath, $query)
//     {
//         $nodes = $xpath->query($query);
//         return $nodes->length > 0 ? trim($nodes->item(0)->nodeValue) : null;
//     }

//     private function extractChartData($xpath)
//     {
//         $years = $xpath->query('//div[@class="gsc_md_hist_b"]//span[@class="gsc_g_t"]');
//         $counts = $xpath->query('//div[@class="gsc_md_hist_b"]//a[@class="gsc_g_a"]//span[@class="gsc_g_al"]');

//         $chartData = [];
//         foreach ($years as $index => $year) {
//             $yearText = trim($year->nodeValue);
//             $count = $counts->length > $index ? trim($counts->item($index)->nodeValue) : '0';
//             $chartData[] = ['year' => $yearText, 'count' => $count];
//         }

//         return $chartData;
//     }
// }



// namespace App\Services;

// use GuzzleHttp\Client;

// class ScraperService
// {
//     protected $client;

//     public function __construct()
//     {
//         $this->client = new Client();
//     }

//     public function scrape($url)
//     {
//         // Mengirim permintaan GET ke URL
//         $response = $this->client->request('GET', $url);

//         // Mengambil konten HTML dari respons
//         $html = $response->getBody()->getContents();

//         // Mengolah konten HTML
//         return $this->parseHtml($html);
//     }

//     protected function parseHtml($html)
//     {
//         $dom = new \DOMDocument();
//         @$dom->loadHTML($html); // '@' untuk mengabaikan peringatan HTML yang tidak valid

//         $xpath = new \DOMXPath($dom);

//         // Mengambil data dari elemen profil
//         $profileData = [
//             'name' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@id="gsc_prf_in"]'),
//             'affiliation' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@class="gsc_prf_il"]'),
//             'email_verified' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@id="gsc_prf_ivh"]'),
//             'interests' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@id="gsc_prf_int"]'),
//             'photo_url' => $this->extractAttribute($xpath, '//div[@id="gsc_prf_pu"]//img', 'src')
//         ];

//         // Mengambil data dari elemen "Cited by"
//         $citedByData = [
//             'citations_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[1]//td[2]'),
//             'citations_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[1]//td[3]'),
//             'h_index_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[2]//td[2]'),
//             'h_index_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[2]//td[3]'),
//             'i10_index_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[3]//td[2]'),
//             'i10_index_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[3]//td[3]')
//         ];

//         // Mengambil data grafik tahun dan jumlah artikel
//         $chartData = $this->extractChartData($xpath);

//         //  Mengambil data artikel dan grafik
//         $articleData = $this->extractArticleData($xpath);

//         return [
//             'profile' => $profileData,
//             'cited_by' => $citedByData,
//             'articles' => $articleData,
//             'chart' => $chartData
//         ];
//     }

//     private function extractArticleData($xpath)
//         {
//             $titles = $xpath->query('//tr[@class="gsc_a_tr"]//a[@class="gsc_a_at"]');
//             $years = $xpath->query('//tr[@class="gsc_a_tr"]//span[@class="gsc_a_h gsc_a_hc gs_ibl"]');
//             $citations = $xpath->query('//tr[@class="gsc_a_tr"]//a[@class="gsc_a_ac gs_ibl"]');
//             $links = $xpath->query('//tr[@class="gsc_a_tr"]//a[@class="gsc_a_at"]');

//             $articles = [];
//             foreach ($titles as $index => $title) {
//                 $year = $years->length > $index ? trim($years->item($index)->nodeValue) : 'Tahun tidak ditemukan';
//                 $citationCount = $citations->length > $index ? trim($citations->item($index)->nodeValue) : '0';

//                 $articles[] = [
//                     'title' => trim($title->nodeValue),
//                     'year' => $year,
//                     'citations' => $citationCount,
//                     'url' => $links->length > $index ? 'https://scholar.google.com' . $links->item($index)->getAttribute('href') : null
//                 ];
//             }

//             return $articles;
//         }

//     private function extractText($xpath, $query)
//     {
//         $nodes = $xpath->query($query);
//         return $nodes->length > 0 ? $nodes->item(0)->nodeValue : null;
//     }

//     private function extractAttribute($xpath, $query, $attribute)
//     {
//         $nodes = $xpath->query($query);
//         return $nodes->length > 0 ? $nodes->item(0)->getAttribute($attribute) : null;
//     }

//     private function extractTableCell($xpath, $query)
//     {
//         $nodes = $xpath->query($query);
//         return $nodes->length > 0 ? $nodes->item(0)->nodeValue : null;
//     }

//     private function extractChartData($xpath)
//     {
//         $years = $xpath->query('//div[@class="gsc_md_hist_b"]//span[@class="gsc_g_t"]');
//         $counts = $xpath->query('//div[@class="gsc_md_hist_b"]//a[@class="gsc_g_a"]//span[@class="gsc_g_al"]');

//         $chartData = [];
//         foreach ($years as $index => $year) {
//             $yearText = trim($year->nodeValue);
//             $count = $counts->length > $index ? trim($counts->item($index)->nodeValue) : '0';
//             $chartData[] = ['year' => $yearText, 'count' => $count];
//         }

//         return $chartData;
//     }
// }



// namespace App\Services;

// use GuzzleHttp\Client;
// use DOMDocument;
// use DOMXPath;

// class ScraperService
// {
//     protected $client;

//     public function __construct()
//     {
//         $this->client = new Client();
//     }

//     /**
//      * Scrape artikel dari Scholar berdasarkan ID Author.
//      */
//     // public function scrapeArticles($author_id)
//     // {
//     //     $base_url = "https://scholar.google.com/citations?user=$author_id&cstart=0&pagesize=50";
//     //     $html = $this->scrape($base_url);

//     //     // Parsing artikel dari HTML yang di-scrape
//     //     return $this->parseArticles($html);
//     // }

//     public function scrapeArticles($author_id)
//     {
//         $articles = [];
//         $start = 0;

//         // Melakukan iterasi sampai seluruh artikel diambil
//         while (true) {
//             $url = "https://scholar.google.com/citations?user=$author_id&cstart=$start&pagesize=50";
//             $html = $this->scrape($url);
//             $newArticles = $this->parseArticles($html);

//             // Jika tidak ada artikel yang ditemukan, berhenti
//             if (empty($newArticles)) {
//                 break;
//             }

//             // Menambahkan artikel yang ditemukan pada iterasi ini
//             $articles = array_merge($articles, $newArticles);

//             // Increment start untuk mengambil artikel selanjutnya
//             $start += 50;
//         }

//         return $articles;
//     }



//     // /**
//     //  * Mengambil HTML dari URL menggunakan Guzzle.
//     //  */
//     public function scrape($url)
//     {
//         $response = $this->client->request('GET', $url);
//         return $response->getBody()->getContents();
//     }

//     // /**
//     //  * Parsing artikel dari konten HTML.
//     //  */
//     protected function parseArticles($html)
//     {
//         $dom = new DOMDocument();
//         @$dom->loadHTML($html); // '@' untuk mengabaikan peringatan HTML yang tidak valid

//         $xpath = new DOMXPath($dom);

//         $articles = [];
//         $rows = $xpath->query('//tr[@class="gsc_a_tr"]');

//         foreach ($rows as $row) {
//             $titleNode = $xpath->query('.//a[@class="gsc_a_at"]', $row)->item(0);
//             $citationsNode = $xpath->query('.//a[@class="gsc_a_ac gs_ibl"]', $row)->item(0);
//             $linkNode = $xpath->query('.//a[@class="gsc_a_at"]', $row)->item(0);
//             $yearNode = $xpath->query('.//span[@class="gsc_a_h gsc_a_hc gs_ibl"]', $row)->item(0);

//             // $articles[] = [
//             //     'title' => trim($titleNode->nodeValue ?? 'No Title'),
//             //     'citations' => trim($citationsNode->nodeValue ?? '0'),
//             //     'link' => "https://scholar.google.com" . $linkNode->getAttribute('href'),
//             //     // 'year' => trim($yearNode->nodeValue ?? 'Unknown'),
//             //     'publication_date' => $this->formatPublicationDate(trim($yearNode->nodeValue ?? 'Unknown')),
//             // ];

//             $articles[] = [
//                 'title' => trim($titleNode->nodeValue ?? 'No Title'),
//                 'citations' => trim($citationsNode->nodeValue ?? '0'),
//                 // Memeriksa apakah $linkNode tidak null sebelum memanggil getAttribute
//                 'link' => $linkNode ? "https://scholar.google.com" . $linkNode->getAttribute('href') : 'No Link',
//                 'publication_date' => $this->formatPublicationDate(trim($yearNode->nodeValue ?? 'Unknown')),
//             ];

//         }

//         return $articles;
//     }

//     protected function formatPublicationDate($year)
//     {
//         // Periksa apakah tahun valid
//         if ($year === 'Unknown' || empty($year)) {
//             return null; // Bisa mengembalikan null atau nilai default lainnya
//         }

//         // Format tahun menjadi tanggal pertama Januari
//         return \Carbon\Carbon::createFromFormat('Y', $year)->startOfYear()->toDateString();
//     }


//     protected function extractChartData($xpath)
//     {
//         // Mengambil data tahun
//         $years = $xpath->query('//div[@class="gsc_md_hist_b"]//span[@class="gsc_g_t"]');

//         // Mengambil data jumlah artikel per tahun
//         $counts = $xpath->query('//div[@class="gsc_md_hist_b"]//a[@class="gsc_g_a"]//span[@class="gsc_g_al"]');

//         $chartData = [];
//         $totalArticles = 0; // Inisialisasi total artikel

//         foreach ($years as $index => $year) {
//             // Mengambil tahun dan menghitung jumlah artikel
//             $yearText = trim($year->nodeValue);
//             $count = $counts->length > $index ? trim($counts->item($index)->nodeValue) : '0';

//             // Mengonversi count ke integer untuk memastikan validasi angka
//             $count = (int) $count;

//             // Menambahkan jumlah artikel ke total
//             $totalArticles += $count;

//             // Menyimpan data tahun dan jumlah artikel ke dalam array
//             $chartData[] = ['year' => $yearText, 'count' => $count];
//         }

//         // Menambahkan total artikel sebagai bagian dari data
//         // $chartData[] = ['year' => 'Total Articles', 'count' => $totalArticles];
//         // Data sementara
//         $chartData[] = ['year' => 'Total Articles', 'count' => 200];

//         return $chartData;
//     }

//     protected function extractCitedByData($xpath)
//     {
//         $citedByData = [
//             'citations_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[1]//td[2]'),
//             'citations_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[1]//td[3]'),
//             'h_index_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[2]//td[2]'),
//             'h_index_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[2]//td[3]'),
//             'i10_index_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[3]//td[2]'),
//             'i10_index_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[3]//td[3]')
//         ];

//         return $citedByData;
//     }

//     private function extractTableCell($xpath, $query)
//     {
//         $nodes = $xpath->query($query);
//         return $nodes->length > 0 ? $nodes->item(0)->nodeValue : null;
//     }

//     /**
//      * Mengambil data yang diperlukan dan menyiapkan untuk tampilan
//      */
//     // public function getScrapingData($author_id)
//     // {
//     //     $base_url = "https://scholar.google.com/citations?user=$author_id&cstart=0&pagesize=50";
//     //     $html = $this->scrape($base_url);

//     //     $dom = new DOMDocument();
//     //     @$dom->loadHTML($html);
//     //     $xpath = new DOMXPath($dom);

//     //     return [
//     //         'cited_by' => $this->extractCitedByData($xpath),
//     //         'chart' => $this->extractChartData($xpath),
//     //         'articles' => $this->parseArticles($html),
//     //     ];
//     // }

//     public function getScrapingData($author_id)
//     {
//         $all_articles = [];
//         $base_url = "https://scholar.google.com/citations?user=$author_id&cstart=";

//         $page = 0;
//         $articles = [];

//         do {
//             // Mengambil HTML untuk halaman ke-`page`
//             $url = $base_url . ($page * 50);  // `cstart` diubah untuk setiap halaman
//             $html = $this->scrape($url);

//             // Parsing artikel dari HTML
//             $articles = $this->parseArticles($html);

//             if (count($articles) > 0) {
//                 // Menambahkan artikel dari halaman ini ke array
//                 $all_articles = array_merge($all_articles, $articles);
//                 $page++;
//             } else {
//                 // Jika tidak ada artikel lagi, hentikan iterasi
//                 break;
//             }
//         } while (count($articles) > 0);

//         // Mengambil data terkait lainnya (cited_by, chart, dll.)
//         $dom = new DOMDocument();
//         @$dom->loadHTML($html); // Memuat HTML terakhir yang diambil
//         $xpath = new DOMXPath($dom);

//         return [
//             'cited_by' => $this->extractCitedByData($xpath),
//             'chart' => $this->extractChartData($xpath),
//             'articles' => $all_articles,  // Mengembalikan semua artikel yang diambil
//         ];
//     }




//     /**
//      * Menyaring data chart publikasi per tahun
//      */
//     // protected function extractChartData($xpath)
//     // {
//     //     $years = $xpath->query('//div[@class="gsc_md_hist_b"]//span[@class="gsc_g_t"]');
//     //     $counts = $xpath->query('//div[@class="gsc_md_hist_b"]//a[@class="gsc_g_a"]//span[@class="gsc_g_al"]');

//     //     $chartData = [];
//     //     foreach ($years as $index => $year) {
//     //         $yearText = trim($year->nodeValue);
//     //         $count = $counts->length > $index ? trim($counts->item($index)->nodeValue) : '0';
//     //         $chartData[] = ['year' => $yearText, 'count' => $count];
//     //     }

//     //     return $chartData;
//     // }
// }


namespace App\Services;

use GuzzleHttp\Client;
use DOMDocument;
use DOMXPath;

class ScraperService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Scrape artikel dari Scholar berdasarkan ID Author.
     */
    // public function scrapeArticles($author_id)
    // {
    //     $articles = [];
    //     $start = 0;

    //     // Melakukan iterasi sampai seluruh artikel diambil
    //     while (true) {
    //         $url = "https://scholar.google.com/citations?user=$author_id&cstart=$start&pagesize=50";
    //         $html = $this->scrape($url);
    //         $newArticles = $this->parseArticles($html);

    //         // Jika tidak ada artikel yang ditemukan, berhenti
    //         if (empty($newArticles)) {
    //             break;
    //         }

    //         // Menambahkan artikel yang ditemukan pada iterasi ini
    //         $articles = array_merge($articles, $newArticles);

    //         // Increment start untuk mengambil artikel selanjutnya
    //         $start += 50;
    //     }

    //     return $articles;
    // }
    public function scrapeArticles($author_id)
    {
        $base_url = "https://scholar.google.com/citations?user=$author_id&cstart=0&pagesize=100";
        $html = $this->scrape($base_url);

        // Parsing artikel dari HTML yang di-scrape
        return $this->parseArticles($html);
    }

    /**
     * Mengambil HTML dari URL menggunakan Guzzle.
     */
    public function scrape($url)
    {
        $response = $this->client->request('GET', $url);
        return $response->getBody()->getContents();
    }

    /**
     * Parsing artikel dari konten HTML.
     */
    protected function parseArticles($html)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($html); // '@' untuk mengabaikan peringatan HTML yang tidak valid

        $xpath = new DOMXPath($dom);

        $articles = [];
        $rows = $xpath->query('//tr[@class="gsc_a_tr"]');

        foreach ($rows as $row) {
            $titleNode = $xpath->query('.//a[@class="gsc_a_at"]', $row)->item(0);
            $citationsNode = $xpath->query('.//a[@class="gsc_a_ac gs_ibl"]', $row)->item(0);
            $linkNode = $xpath->query('.//a[@class="gsc_a_at"]', $row)->item(0);
            $yearNode = $xpath->query('.//span[@class="gsc_a_h gsc_a_hc gs_ibl"]', $row)->item(0);

            // Pastikan node tidak null sebelum mengambil nilai atau atribut
            $articles[] = [
                'title' => trim($titleNode ? $titleNode->nodeValue : 'No Title'),
                'citations' => trim($citationsNode ? $citationsNode->nodeValue : '0'),
                // Memeriksa apakah $linkNode tidak null sebelum memanggil getAttribute
                'link' => $linkNode ? "https://scholar.google.com" . $linkNode->getAttribute('href') : 'No Link',
                'publication_date' => $this->formatPublicationDate(trim($yearNode ? $yearNode->nodeValue : 'Unknown')),
                'source' => 'Google Scholar',
            ];
        }

        return $articles;
    }

    protected function formatPublicationDate($year)
    {
        // Periksa apakah tahun valid
        if ($year === 'Unknown' || empty($year)) {
            return null; // Bisa mengembalikan null atau nilai default lainnya
        }

        // Format tahun menjadi tanggal pertama Januari
        return \Carbon\Carbon::createFromFormat('Y', $year)->startOfYear()->toDateString();
    }

    protected function extractChartData($xpath)
    {
        // Mengambil data tahun
        $years = $xpath->query('//div[@class="gsc_md_hist_b"]//span[@class="gsc_g_t"]');

        // Mengambil data jumlah artikel per tahun
        $counts = $xpath->query('//div[@class="gsc_md_hist_b"]//a[@class="gsc_g_a"]//span[@class="gsc_g_al"]');

        $chartData = [];
        $totalArticles = 0; // Inisialisasi total artikel

        foreach ($years as $index => $year) {
            // Mengambil tahun dan menghitung jumlah artikel
            $yearText = trim($year->nodeValue);
            $count = $counts->length > $index ? trim($counts->item($index)->nodeValue) : '0';

            // Mengonversi count ke integer untuk memastikan validasi angka
            $count = (int) $count;

            // Menambahkan jumlah artikel ke total
            $totalArticles += $count;

            // Menyimpan data tahun dan jumlah artikel ke dalam array
            $chartData[] = ['year' => $yearText, 'count' => $count];
        }

        // Menambahkan total artikel sebagai bagian dari data
        $chartData[] = ['year' => 'Total Articles', 'count' => $totalArticles];

        return $chartData;
    }

    protected function extractCitedByData($xpath)
    {
        $citedByData = [
            'citations_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[1]//td[2]'),
            'citations_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[1]//td[3]'),
            'h_index_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[2]//td[2]'),
            'h_index_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[2]//td[3]'),
            'i10_index_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[3]//td[2]'),
            'i10_index_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[3]//td[3]')
        ];

        return $citedByData;
    }

    private function extractTableCell($xpath, $query)
    {
        $nodes = $xpath->query($query);
        return $nodes->length > 0 ? $nodes->item(0)->nodeValue : null;
    }

    /**
     * Mengambil data yang diperlukan dan menyiapkan untuk tampilan
     */
    public function getScrapingData($author_id)
    {
        $base_url = "https://scholar.google.com/citations?user=$author_id&cstart=0&pagesize=100";
        $html = $this->scrape($base_url);

        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        return [
            'cited_by' => $this->extractCitedByData($xpath),
            'chart' => $this->extractChartData($xpath),
            'articles' => $this->parseArticles($html),
        ];
    }
}
