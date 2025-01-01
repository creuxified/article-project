<?php

namespace App\Http\Controllers;

use App\Services\ScraperService;
use Illuminate\Http\Request;

class Scraper extends Controller
{

  protected $scraper;

  public function __construct(ScraperService $scraper)
  {
    $this->scraper = $scraper;
  }

  public function index(Request $request)
  {
    // Menangkap input ID user dari query string
    $id_user = $request->query('id_user');

    // Jika ID user tidak ada, tampilkan form tanpa hasil scraping
    if (!$id_user) {
      return view('scraped');
    }

    // Gantilah URL dengan ID user dinamis
    $url = "https://scholar.google.com/citations?user=$id_user";

    // Lakukan scraping pada URL tersebut
    $dataScrapping = $this->scraper->scrape($url);

    // Kembalikan data hasil scraping ke view
    return view('scraped', ['dataScrapping' => $dataScrapping]);
  }
}
