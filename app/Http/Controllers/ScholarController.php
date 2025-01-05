<?php

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
            $journalNode = $xpath->query('.//div[@class="gs_gray"]', $row)->item(1);
            $citationsNode = $xpath->query('.//a[@class="gsc_a_ac gs_ibl"]', $row)->item(0);
            $linkNode = $xpath->query('.//a[@class="gsc_a_at"]', $row)->item(0);
            $yearNode = $xpath->query('.//span[@class="gsc_a_h gsc_a_hc gs_ibl"]', $row)->item(0);

            // Pastikan node tidak null sebelum mengambil nilai atau atribut
            $articles[] = [
                'title' => trim($titleNode ? $titleNode->nodeValue : 'No Title'),
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
