<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Publication;

class ScopusController extends Controller
{
    /**
     * Menampilkan data publikasi dari database.
     */
    public function showPublications()
    {
        $publications = Publication::all(); // Ambil semua data dari tabel `publications`
        return view('scopus.index', compact('publications')); // Tampilkan ke view
    }

    /**
     * Menangani form submission untuk scraping data dari Scopus berdasarkan Author ID.
     */
    public function scrapeAndShow(Request $request)
    {
        $request->validate([
            'author_id' => 'required|string',
        ]);

        $author_id = $request->input('author_id');

        // Panggil fungsi untuk melakukan scraping
        $this->scrapeScopus($author_id);

        // Redirect ke halaman utama dengan notifikasi sukses
        return redirect()->route('scopus.index')->with('success', 'Publications fetched successfully.');
    }

    /**
     * Fungsi untuk scraping data dari Scopus berdasarkan Author ID.
     */
    public function scrapeScopus($author_id)
    {
        $api_key = '1dad71e1f3b375d6c4f111ac047ed1e9'; // API Key Scopus
        $base_url = 'https://api.elsevier.com/content/';
        $articles = $this->getScopusArticles($author_id, $api_key, $base_url);

        // Misalnya, kita mendapatkan HTML string dari response
        $htmlContent = '<div data-testid="author-list"><span class="Authors-module__umR1O"><a href="/authid/detail.uri?authorId=57200990968" class="Button-module__f8gtt Button-module__rphhF Button-module__XTcEt Button-module__MlsfC Button-module__hK_LA Button-module__qDdAl Button-module__rTQlw"><span class="Typography-module__lVnit Typography-module__Nfgvc Button-module__Imdmt">Liantoni, F.</span></a>, </span><span class="Authors-module__umR1O"><a href="/authid/detail.uri?authorId=57201071505" class="Button-module__f8gtt Button-module__rphhF Button-module__XTcEt Button-module__MlsfC Button-module__hK_LA Button-module__qDdAl Button-module__rTQlw"><span class="Typography-module__lVnit Typography-module__Nfgvc Button-module__Imdmt">Prakisya, N.P.T.</span></a>, </span><span class="Authors-module__umR1O"><a href="/authid/detail.uri?authorId=57222612490" class="Button-module__f8gtt Button-module__rphhF Button-module__XTcEt Button-module__MlsfC Button-module__hK_LA Button-module__qDdAl Button-module__rTQlw"><span class="Typography-module__lVnit Typography-module__Nfgvc Button-module__Imdmt">Aristyagama, Y.H.</span></a>, </span><span class="Authors-module__umR1O"><a href="/authid/detail.uri?authorId=57201992580" class="Button-module__f8gtt Button-module__rphhF Button-module__XTcEt Button-module__MlsfC Button-module__hK_LA Button-module__qDdAl Button-module__rTQlw"><span class="Typography-module__lVnit Typography-module__Nfgvc Button-module__Imdmt">Hatta, P.</span></a></span></div>';

        // Inisialisasi DOMDocument untuk parsing HTML
        $dom = new \DOMDocument();
        @$dom->loadHTML($htmlContent); // Mengabaikan error parsing HTML

        // Mengambil elemen penulis
        $authors = $dom->getElementsByTagName('span');
        $authorNames = [];

        // Iterasi elemen span untuk menemukan nama penulis
        foreach ($authors as $author) {
            if ($author->getAttribute('class') == 'Typography-module__lVnit Typography-module__Nfgvc Button-module__Imdmt') {
                $authorNames[] = $author->textContent;
            }
        }

        // Menyusun nama penulis dalam format yang diinginkan (misalnya koma untuk pemisah)
        $authorNamesString = implode(', ', $authorNames);

        if ($articles) {
            foreach ($articles['search-results']['entry'] as $article) {
                $doi = $article['prism:doi'] ?? null;

                // Cari publikasi berdasarkan DOI
                $existingPublication = Publication::where('link', $doi)->first();

                // Data yang akan dimasukkan atau diperbarui
                $newData = [
                    'user_id' => auth()->id(), // User ID saat ini
                    'author_name' => $authorNamesString, // Menyimpan penulis dalam format string
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
     * Fungsi untuk mengambil data artikel dari Scopus menggunakan Guzzle.
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
}
