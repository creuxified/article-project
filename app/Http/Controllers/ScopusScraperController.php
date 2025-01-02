<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ScopusScraperController extends Controller
{
    public function showForm()
    {
        $publications = Publication::all();
        return view('scrap.scopus', compact('publications')); // Corrected variable name and dot notation for view
    }

    public function scrapeScopus(Request $request)
    {
        $request->validate([
            'scopus_id' => 'required|numeric',
        ]);

        $scopus_id = $request->input('scopus_id');
        $api_key = '2f3be97cfe6cc239b0a9f325a660d9c1';
        $base_url = 'https://api.elsevier.com/content/search/scopus';

        try {
            // Fetch articles from Scopus
            $articles = $this->getScopusArticles($scopus_id, $api_key, $base_url);

            if ($articles && isset($articles['search-results']['entry'])) {
                foreach ($articles['search-results']['entry'] as $article) {
                    $doi = $article['prism:doi'] ?? null;

                    // Check if the publication already exists
                    $existingPublication = Publication::where('doi', $doi)->first();

                    // Collect data
                    $newData = [
                        'title' => $article['dc:title'] ?? 'Unknown',
                        'journal_name' => $article['prism:publicationName'] ?? 'Unknown',
                        'publication_date' => $article['prism:coverDate'] ?? null,
                        'citations' => $article['citedby-count'] ?? 0,
                        'doi' => $doi,
                        'author_name' => $article['dc:creator'] ?? 'Unknown Author',
                        'institution' => $article['affiliation'][0]['affilname'] ?? 'Unknown Institution',
                        'source' => 'scopus',
                        'user_id' => auth()->id(), // Associate with the currently logged-in user
                    ];


                    if ($existingPublication) {
                        $existingPublication->update($newData);
                    } else {
                        Publication::create($newData);
                    }
                }

                return redirect()->route('scrap.scopus')->with('status', 'Data successfully saved!');
            } else {
                return redirect()->back()->with('error', 'No articles found for the provided Scopus ID.');
            }
        } catch (\Exception $e) {
            Log::error('Error fetching data from Scopus: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to fetch data from Scopus. Please try again later.');
        }
    }

    private function getScopusArticles($scopus_id, $api_key, $base_url)
    {
        try {
            $client = new Client();
            $url = $base_url . "?query=AU-ID($scopus_id)";

            $response = $client->request('GET', $url, [
                'headers' => [
                    'X-ELS-APIKey' => $api_key,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Error in Scopus API request: ' . $e->getMessage());
            return null;
        }
    }
}
