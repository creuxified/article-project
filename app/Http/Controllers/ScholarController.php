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

namespace App\Http\Controllers;

use App\Services\ScraperService;
use Illuminate\Http\Request;
use App\Models\Publication;

class ScholarController extends Controller
{
    protected $scraperService;

    public function __construct(ScraperService $scraperService)
    {
        $this->scraperService = $scraperService;
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
        $scrapedData = $this->scraperService->getScrapingData($author_id);

        // Simpan data ke dalam database jika diperlukan
        foreach ($scrapedData['articles'] as $article) {
            $this->saveOrUpdatePublication($article);
        }

        // Debug log to ensure data is scraped
        // Log::debug('Scraped Data:', $scrapedData); // Check the scraped data in the logs

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

    /**
     * Fungsi untuk mengambil artikel dari Scholar dan mengembalikannya.
     */
    public function getScholarArticles($author_id)
    {
        $base_url = "https://scholar.google.com/citations?user=$author_id&cstart=0&pagesize=100";
        return $this->scraperService->scrapeArticles($base_url);
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
        // Ensure that the authenticated user owns the data
        Publication::where('user_id', auth()->id())->delete();

        // Redirect back with a success message
        return redirect()->route('scholar.index')->with('success', 'All your data has been deleted successfully.');
    }
}
