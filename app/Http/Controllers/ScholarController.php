<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use GuzzleHttp\Client;
// use DOMDocument;
// use DOMXPath;

// class ScholarController extends Controller
// {
//     // Menampilkan halaman publikasi Google Scholar
//     public function index()
//     {
//         // Mengambil publikasi dari sesi jika sudah disimpan
//         $publications = session('publications', []);

//         // Periksa apakah artikel kosong
//         if (empty($publications['articles'])) {
//             // Jika kosong, mungkin Anda ingin menampilkan pesan atau tampilan default
//             $publications = null; // Atau Anda bisa menetapkan array kosong
//         }

//         return view('scholar.index', compact('publications'));
//     }

//     // Fungsi untuk melakukan scrape publikasi berdasarkan author_id
//     public function scrapeAndShow(Request $request)
//     {
//         // Validasi input
//         $request->validate([
//             'author_id' => 'required|string',
//         ]);

// $authorId = $request->input('author_id');
// $cstart = $request->query('cstart', 0); // Default to 0 if not set
// $pagesize = $request->query('pagesize', 100); // Default to 100 if not set

// $url = "https://scholar.google.com/citations?user=$authorId&cstart=$cstart&pagesize=$pagesize";

//         // Mengambil data dari Google Scholar menggunakan Guzzle
//         $client = new Client();
//         $response = $client->get($url);
//         $html = (string) $response->getBody();

//         // Mem-parsing HTML untuk mengambil data publikasi
//         $publications = $this->parseHtml($html);

//         // Menyimpan publikasi ke dalam sesi untuk ditampilkan di view
//         session(['publications' => $publications]);

//         return redirect()->route('scholar.index')->with('success', 'Data publikasi berhasil diambil!');
//     }

//     // Fungsi untuk parsing HTML dan mengambil data publikasi
//     private function parseHtml($html)
//     {
//         $dom = new DOMDocument();
//         @$dom->loadHTML($html);

//         $xpath = new DOMXPath($dom);

//         // Mengambil data artikel
//         $articles = $this->extractArticleData($xpath);

//         return [
//             'articles' => $articles
//         ];
//     }

//     // Fungsi untuk mengekstrak data artikel
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
// }











// namespace App\Http\Controllers;

// use App\Services\ScraperService;
// use Illuminate\Routing\Controller;
// use Illuminate\Http\Request;

// class ScholarController extends Controller
// {
//     protected $scraper;

//     public function __construct(ScraperService $scraper)
//     {
//         $this->scraper = $scraper;
//     }

//     public function index(Request $request)
//     {
//         // Menangkap input ID user dari form
//         $author_id = $request->query('author_id');

//         // Jika ID user tidak ada, tampilkan form tanpa hasil scraping
//         if (!$author_id) {
//             return view('scholar.index');
//         }

//         // Gantilah URL dengan ID user dinamis
//         $url = "https://scholar.google.com/citations?user=$author_id";

//         // Lakukan scraping pada URL tersebut
//         $dataScrapping = $this->scraper->scrape($url);

//         // Kembalikan data hasil scraping ke view
//         return view('scholar.index', data: compact(var_name: 'dataScrapping'));
//     }
// }


// namespace App\Http\Controllers;

// use App\Services\ScraperService;
// use Illuminate\Http\Request;
// use App\Models\Publication;

// class ScholarController extends Controller
// {
//     protected $scraperService;

//     public function __construct(ScraperService $scraperService)
//     {
//         $this->scraperService = $scraperService;
//     }

//     /**
//      * Menampilkan data publikasi dari database.
//      */
//     public function showPublications()
//     {
//         $publications = Publication::all(); // Ambil semua data dari tabel `publications`
//         return view('scholar.index', compact('publications')); // Tampilkan ke view
//     }

//     /**
//      * Menangani form submission untuk scraping data dari Scholar berdasarkan Author ID.
//      */
//     public function scrapeAndShow(Request $request)
//     {
//         $request->validate([
//             'author_id' => 'required|string',
//         ]);

//         $author_id = $request->input('author_id');

//         // Panggil fungsi untuk melakukan scraping
//         $articles = $this->scraperService->scrapeArticles($author_id);

//         // Simpan data ke dalam database
//         foreach ($articles as $article) {
//             $this->saveOrUpdatePublication($article);
//         }

//         // Redirect ke halaman utama dengan notifikasi sukses
//         return redirect()->route('scholar.index')->with('success', 'Publications fetched successfully.');
//     }

//     /**
//      * Fungsi untuk menyimpan atau memperbarui data publikasi.
//      */
//     protected function saveOrUpdatePublication($article)
//     {
//         $existingPublication = Publication::where('link', $article['link'])->first();

//         // Memastikan citations tidak kosong dan diubah menjadi integer (default 0 jika kosong)
//         $article['citations'] = empty($article['citations']) ? 0 : (int) $article['citations'];

//         // Tambahkan user_id ke data yang akan disimpan
//         $article['user_id'] = auth()->id();

//         if ($existingPublication) {
//             $existingPublication->update($article);
//         } else {
//             Publication::create($article);
//         }
//     }

//     /**
//      * Fungsi untuk mengambil artikel dari Scholar dan mengembalikannya.
//      */
//     public function getScholarArticles($author_id)
//     {
//         $base_url = "https://scholar.google.com/citations?user=$author_id&cstart=0&pagesize=100";
//         return $this->scraperService->scrapeArticles($base_url);
//     }
// }

// namespace App\Http\Controllers;

// use App\Services\ScraperService;
// use Illuminate\Http\Request;
// use App\Models\Publication;

// class ScholarController extends Controller
// {
//     protected $scraperService;

//     public function __construct(ScraperService $scraperService)
//     {
//         $this->scraperService = $scraperService;
//     }

//     /**
//      * Menampilkan data publikasi dari database.
//      */
//     public function showPublications()
//     {
//         $publications = Publication::all(); // Ambil semua data dari tabel `publications`

//         // Get chart data for publication count per year
//         $chartData = $this->getPublicationCountPerYear();

//         return view('scholar.index', compact('publications', 'chartData')); // Tampilkan ke view
//     }

//     /**
//      * Menangani form submission untuk scraping data dari Scholar berdasarkan Author ID.
//      */
//     public function scrapeAndShow(Request $request)
//     {
//         $request->validate([
//             'author_id' => 'required|string',
//         ]);

//         $author_id = $request->input('author_id');

//         // Panggil fungsi untuk melakukan scraping dan mendapatkan data yang diperlukan
//         $scrapedData = $this->scraperService->getScrapingData($author_id);

//         // Simpan data ke dalam database jika diperlukan
//         foreach ($scrapedData['articles'] as $article) {
//             $this->saveOrUpdatePublication($article);
//         }

//         // Debug log to ensure data is scraped
//         // Log::debug('Scraped Data:', $scrapedData); // Check the scraped data in the logs

//         // Redirect ke halaman utama dengan data yang telah diambil
//         return redirect()->route('scholar.index')
//             ->with('success', 'Publications fetched successfully.')
//             ->with('scrapedData', $scrapedData); // Store scraped data in session
//     }



//     /**
//      * Fungsi untuk menyimpan atau memperbarui data publikasi.
//      */
//     protected function saveOrUpdatePublication($article)
//     {
//         $existingPublication = Publication::where('link', $article['link'])->first();

//         // Memastikan citations tidak kosong dan diubah menjadi integer (default 0 jika kosong)
//         $article['citations'] = empty($article['citations']) ? 0 : (int) $article['citations'];

//         // Tambahkan user_id ke data yang akan disimpan
//         $article['user_id'] = auth()->id();

//         if ($existingPublication) {
//             $existingPublication->update($article);
//         } else {
//             Publication::create($article);
//         }
//     }

//     /**
//      * Fungsi untuk mengambil artikel dari Scholar dan mengembalikannya.
//      */
//     public function getScholarArticles($author_id)
//     {
//         $base_url = "https://scholar.google.com/citations?user=$author_id&cstart=0&pagesize=100";
//         return $this->scraperService->scrapeArticles($base_url);
//     }

//     /**
//      * Get the publication count per year.
//      */
//     protected function getPublicationCountPerYear()
//     {
//         // Fetch all publications
//         $publications = Publication::all();

//         // Group publications by year and count them
//         $chartData = $publications->groupBy(function ($publication) {
//             return \Carbon\Carbon::parse($publication->publication_date)->format('Y');
//         })->map(function ($yearGroup) {
//             return $yearGroup->count();
//         });

//         // Format the chart data for passing to the view
//         $chartDataFormatted = [];
//         foreach ($chartData as $year => $count) {
//             $chartDataFormatted[] = ['year' => $year, 'count' => $count];
//         }

//         return $chartDataFormatted;
//     }

//     public function deleteData(Request $request)
//     {
//         // Ensure that the authenticated user owns the data
//         Publication::where('user_id', auth()->id())->delete();

//         // Redirect back with a success message
//         return redirect()->route('scholar.index')->with('success', 'All your data has been deleted successfully.');
//     }
// }


namespace App\Http\Controllers;

use GuzzleHttp\Client;
use DOMDocument;
use DOMXPath;
use App\Models\Publication;
use Illuminate\Http\Request;

class ScholarController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Menampilkan data publikasi dari database.
     */
    public function showPublications()
    {
        $publications = Publication::all(); // Ambil semua data dari tabel `publications`

        // Get chart data for publication count per year
        $chartData = $this->getPublicationCountPerYear();

        return view('scholar.index', compact('publications', 'chartData')); // Tampilkan ke view
    }

    /**
     * Menangani form submission untuk scraping data dari Scholar berdasarkan Author ID.
     */
    public function scrapeAndShow(Request $request)
    {
        $request->validate([
            'author_id' => 'required|string',
        ]);

        $author_id = $request->input('author_id');

        // Panggil fungsi untuk melakukan scraping dan mendapatkan data yang diperlukan
        $scrapedData = $this->getScrapingData($author_id);

        // Simpan data ke dalam database jika diperlukan
        foreach ($scrapedData['articles'] as $article) {
            $this->saveOrUpdatePublication($article);
        }

        // Redirect ke halaman utama dengan data yang telah diambil
        return redirect()->route('scholar.index')
            ->with('success', 'Publications fetched successfully.')
            ->with('scrapedData', $scrapedData); // Store scraped data in session
    }

    /**
     * Fungsi untuk menyimpan atau memperbarui data publikasi.
     */
    protected function saveOrUpdatePublication($article)
    {
        $existingPublication = Publication::where('link', $article['link'])->first();

        // Memastikan citations tidak kosong dan diubah menjadi integer (default 0 jika kosong)
        $article['citations'] = empty($article['citations']) ? 0 : (int) $article['citations'];

        // Tambahkan user_id ke data yang akan disimpan
        $article['user_id'] = auth()->id();

        if ($existingPublication) {
            $existingPublication->update($article);
        } else {
            Publication::create($article);
        }
    }

    public function scrapeArticles($author_id)
    {
        $base_url = "https://scholar.google.com/citations?user=$author_id&cstart=0&pagesize=100";
        $html = $this->scrape($base_url);

        // Parsing artikel dari HTML yang di-scrape
        return $this->parseArticles($html);
    }

    /**
     * Fungsi untuk mengambil artikel dari Scholar dan mengembalikannya.
     */
    public function getScholarArticles($author_id)
    {
        $base_url = "https://scholar.google.com/citations?user=$author_id&cstart=0&pagesize=100";
        return $this->scrapeArticles($base_url);
    }

    /**
     * Get the publication count per year.
     */
    protected function getPublicationCountPerYear()
    {
        // Fetch all publications
        $publications = Publication::all();

        // Group publications by year and count them
        $chartData = $publications->groupBy(function ($publication) {
            return \Carbon\Carbon::parse($publication->publication_date)->format('Y');
        })->map(function ($yearGroup) {
            return $yearGroup->count();
        });

        // Format the chart data for passing to the view
        $chartDataFormatted = [];
        foreach ($chartData as $year => $count) {
            $chartDataFormatted[] = ['year' => $year, 'count' => $count];
        }

        return $chartDataFormatted;
    }

    public function deleteData(Request $request)
    {
        // Ensure that the authenticated user owns the data and filter by source
        Publication::where('user_id', auth()->id())
                   ->where('source', 'Google Scholar')
                   ->delete();

        // Redirect back with a success message
        return redirect()->route('scholar.index')->with('success', 'All your Google Scholar data has been deleted successfully.');
    }

    /**
     * Mengambil artikel dari URL menggunakan Guzzle.
     */
    private function scrape($url)
    {
        $response = $this->client->request('GET', $url);
        return $response->getBody()->getContents();
    }

    /**
     * Parsing artikel dari konten HTML.
     */
    private function parseArticles($html)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($html); // '@' untuk mengabaikan peringatan HTML yang tidak valid

        $xpath = new DOMXPath($dom);

        $articles = [];
        $rows = $xpath->query('//tr[@class="gsc_a_tr"]');

        foreach ($rows as $row) {
            $titleNode = $xpath->query('.//a[@class="gsc_a_at"]', $row)->item(0);
            $authorNode = $xpath->query('.//div[@class="gs_gray"]', $row)->item(0);
            $journalNode = $xpath->query('.//div[@class="gs_gray"]', $row)->item(1);
            $citationsNode = $xpath->query('.//a[@class="gsc_a_ac gs_ibl"]', $row)->item(0);
            $linkNode = $xpath->query('.//a[@class="gsc_a_at"]', $row)->item(0);
            $yearNode = $xpath->query('.//span[@class="gsc_a_h gsc_a_hc gs_ibl"]', $row)->item(0);

            // Pastikan node tidak null sebelum mengambil nilai atau atribut
            $articles[] = [
                'title' => trim($titleNode ? $titleNode->nodeValue : 'No Title'),
                'author_name' => trim($authorNode ? $authorNode->nodeValue : 'No Author'),
                'journal_name' => trim($journalNode ? $journalNode->nodeValue : 'No Journal'),
                'citations' => trim($citationsNode ? $citationsNode->nodeValue : '0'),
                'link' => $linkNode ? "https://scholar.google.com" . $linkNode->getAttribute('href') : 'No Link',
                'publication_date' => $this->formatPublicationDate(trim($yearNode ? $yearNode->nodeValue : 'Unknown')),
                'source' => 'Google Scholar',
            ];
        }

        return $articles;
    }

    /**
     * Format publication date.
     */
    private function formatPublicationDate($year)
    {
        if ($year === 'Unknown' || empty($year)) {
            return null;
        }

        return \Carbon\Carbon::createFromFormat('Y', $year)->startOfYear()->toDateString();
    }

    /**
     * Mengambil data yang diperlukan dan menyiapkan untuk tampilan.
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

    /**
     * Extract citation data.
     */
    private function extractCitedByData($xpath)
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

    /**
     * Extract table cell.
     */
    private function extractTableCell($xpath, $query)
    {
        $nodes = $xpath->query($query);
        return $nodes->length > 0 ? $nodes->item(0)->nodeValue : null;
    }
}
