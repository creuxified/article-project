<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use GuzzleHttp\Client;

class ScopusScraperController extends Controller
{
    public function showForm()
    {
        $publication = Publication::all();
        return view('scrap.scopus', compact('publication')); // Fixed view name with dot notation
    }

    public function scrapeScopus(Request $request)
    {
        $scopus_id = $request->input('scopus_id');
        $api_key = '2f3be97cfe6cc239b0a9f325a660d9c1';
        $base_url = 'https://api.elsevier.com/content/search/scopus';

        // Fetch articles from Scopus
        $articles = $this->getScopusArticles($scopus_id, $api_key, $base_url);

        if ($articles) {
            foreach ($articles['search-results']['entry'] as $article) {
                $doi = $article['prism:doi'] ?? null;

                // Check if the publication already exists
                $existingPublication = Publication::where('doi', $doi)->first();

                // Collect data
                $authorName = $article['dc:creator'] ?? 'Unknown Author';
                $institution = $article['affiliation']['affilname'] ?? 'Unknown Institution';
                $citations = $article['citedby-count'] ?? 0;

                $newData = [
                    'title' => $article['dc:title'] ?? 'Unknown',
                    'journal_name' => $article['prism:publicationName'] ?? 'Unknown',
                    'publication_date' => $article['prism:coverDate'] ?? null,
                    'citations' => $citations,
                    'doi' => $doi,
                    'author_name' => $authorName,
                    'institution' => $institution,
                    'source' => 'scopus'
                ];

                if ($existingPublication) {
                    $existingPublication->update($newData);
                } else {
                    Publication::create($newData);
                }
            }

            return redirect('/scrap/scopus')->with('status', 'Data successfully saved!');
        } else {
            return response()->json(['message' => 'Failed to fetch data from Scopus.'], 400);
        }
    }

    private function getScopusArticles($scopus_id, $api_key, $base_url)
    {
        $client = new Client();
        $url = $base_url . "?query=AU-ID($scopus_id)";

        $response = $client->request('GET', $url, [
            'headers' => [
                'X-ELS-APIKey' => $api_key,
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}
