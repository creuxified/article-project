<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Publication;
// use GuzzleHttp\Client;
// use Illuminate\Support\Facades\Log;

// class ScopusScraperController extends Controller
// {
//     public function showForm()
//     {
//         $publications = Publication::all();
//         return view('scrap.scopus', compact('publications'));
//     }

//     public function scrapeScopus(Request $request)
//     {
//         $request->validate([
//             'scopus_id' => 'required|numeric', // validate the scopus_id
//         ]);

//         $scopus_id = $request->scopus_id; // Get Scopus ID from the form input
//         // $api_key = env('SCOPUS_API_KEY'); // Use environment variable for the API key
//         $api_key = '1dad71e1f3b375d6c4f111ac047ed1e9';
//         $base_url = 'https://api.elsevier.com/content/search/scopus';

//         try {
//             // Fetch articles from Scopus
//             $articles = $this->getScopusArticles($scopus_id, $api_key, $base_url);

//             if ($articles && isset($articles['search-results']['entry'])) {
//                 foreach ($articles['search-results']['entry'] as $article) {
//                     $doi = $article['prism:doi'] ?? null;

//                     // Check if the publication already exists
//                     $existingPublication = Publication::where('doi', $doi)->first();

//                     // Collect data
//                     $newData = [
//                         'title' => $article['dc:title'] ?? 'Unknown',
//                         'journal_name' => $article['prism:publicationName'] ?? 'Unknown',
//                         'publication_date' => $article['prism:coverDate'] ?? null,
//                         'citations' => $article['citedby-count'] ?? 0,
//                         'link' => $doi,
//                         'author_name' => $article['dc:creator'] ?? 'Unknown Author',
//                         'source' => 'scopus',
//                         'user_id' => auth()->id(), // Associate with the currently logged-in user
//                     ];

//                     if ($existingPublication) {
//                         $existingPublication->update($newData);
//                     } else {
//                         Publication::create($newData);
//                     }
//                 }

//                 return redirect()->route('scrap.scopus')->with('status', 'Data successfully saved!');
//             } else {
//                 return redirect()->back()->with('error', 'No articles found for the provided Scopus ID.');
//             }
//         } catch (\Exception $e) {
//             Log::error('Error fetching data from Scopus for Scopus ID: ' . $scopus_id . '. Error: ' . $e->getMessage());
//             return redirect()->back()->with('error', 'Failed to fetch data from Scopus. Please try again later.');
//         }
//     }

//     private function getScopusArticles($scopus_id, $api_key, $base_url)
//     {
//         try {
//             $client = new Client();
//             $url = $base_url . "?query=AU-ID($scopus_id)";

//             $response = $client->request('GET', $url, [
//                 'headers' => [
//                     'X-ELS-APIKey' => $api_key,
//                 ]
//             ]);

//             return json_decode($response->getBody(), true);
//         } catch (\Exception $e) {
//             Log::error('Error in Scopus API request: ' . $e->getMessage());
//             return null;
//         }
//     }
// }













// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Publication;
// use App\Models\User;
// use GuzzleHttp\Client;
// use Illuminate\Support\Facades\Log;

// class ScopusScraperController extends Controller
// {
//     public function showForm()
//     {
//         $publications = Publication::all();
//         return view('scrap.scopus', compact('publications'));
//     }

//     public function scrapeScopus(Request $request)
//     {
//         // Get the scopus_id from the authenticated user
//         // $scopus_id = auth()->user()->scopus;

//         $scopus_id = 57202221836;

//         if (!$scopus_id) {
//             return redirect()->back()->with('error', 'User does not have a Scopus ID.');
//         }

//         // $api_key = env('SCOPUS_API_KEY');
//         $api_key = '2f3be97cfe6cc239b0a9f325a660d9c1';
//         $base_url = 'https://api.elsevier.com/content/search/scopus';

//         try {
//             // Fetch articles from Scopus
//             $articles = $this->getScopusArticles($scopus_id, $api_key, $base_url);

//             if ($articles && isset($articles['search-results']['entry'])) {
//                 foreach ($articles['search-results']['entry'] as $article) {
//                     $doi = $article['prism:doi'] ?? null;

//                     // Check if the publication already exists
//                     $existingPublication = Publication::where('doi', $doi)->first();

//                     // Collect data
//                     $newData = [
//                         'title' => $article['dc:title'] ?? 'Unknown',
//                         'journal_name' => $article['prism:publicationName'] ?? 'Unknown',
//                         'publication_date' => $article['prism:coverDate'] ?? null,
//                         'citations' => $article['citedby-count'] ?? 0,
//                         'link' => 'https://doi.org/'.$doi,
//                         'author_name' => $article['dc:creator'] ?? 'Unknown Author',
//                         'source' => 'scopus',
//                         'user_id' => auth()->id(),
//                     ];

//                     if ($existingPublication) {
//                         $existingPublication->update($newData);
//                     } else {
//                         Publication::create($newData);
//                     }
//                 }

//                 return redirect()->route('scrap.scopus')->with('status', 'Data successfully saved!');
//             } else {
//                 Log::warning("No articles found for Scopus ID: $scopus_id");
//                 return redirect()->back()->with('error', 'No articles found for the provided Scopus ID.');
//             }
//         } catch (\Exception $e) {
//             Log::error('Error fetching data from Scopus for Scopus ID: ' . $scopus_id . '. Error: ' . $e->getMessage());
//             return redirect()->back()->with('error', 'Failed to fetch data from Scopus. Please try again later.');
//         }
//     }

//     private function getScopusArticles($scopus_id, $api_key, $base_url)
//     {
//         try {
//             $client = new Client();
//             $url = $base_url . "?query=AU-ID($scopus_id)";

//             $response = $client->request('GET', $url, [
//                 'headers' => [
//                     'X-ELS-APIKey' => $api_key,
//                 ]
//             ]);

//             // Log the response body for debugging
//             Log::info("Scopus API Response: " . $response->getBody()->getContents());

//             return json_decode($response->getBody(), true);
//         } catch (\Exception $e) {
//             Log::error('Error in Scopus API request: ' . $e->getMessage());
//             return null;
//         }
//     }
// }


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ScopusScraperController extends Controller
{
    private $baseUrl;
    private $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://api.elsevier.com/content/search/scopus';
        $this->apiKey = '2f3be97cfe6cc239b0a9f325a660d9c1';
    }

    public function index()
    {
        return view('scopus.index');
    }

    public function fetchData(Request $request)
    {
        $authorId = $request->input('author_id');

        if (!$authorId) {
            return back()->withErrors(['author_id' => 'Author ID is required']);
        }

        try {
            $client = new Client();
            $response = $client->get('https://api.elsevier.com/content/author', [
                'query' => [
                    'author_id' => $authorId,
                    'apiKey' => $this->apiKey,
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return view('scopus.results', [
                'author' => $data['author-retrieval-response'][0] ?? null,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to fetch data: ' . $e->getMessage()]);
        }
    }
}
