<?php

namespace App\Livewire\Scraper;

use Livewire\Component;
use GuzzleHttp\Client;
use DOMDocument;
use DOMXPath;
use App\Models\Publication;
use Illuminate\Support\Facades\Auth;

class PublicationChart extends Component
{
    public $author_id_scholar;
    public $author_id_scopus;
    public $scrapedData = [];
    public $successMessage;
    public $errorMessage;
    public $publications;
    public $formattedChartData;
    public $formattedCitationData;
    public $scholar_id;
    public $scopus_id;
    public $message;

    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function mount()
    {
        $this->loadPublications();
         // Isi dengan data default jika ada
         $this->author_id_scholar = auth()->user()->scholar;
         $this->author_id_scopus = auth()->user()->scopus;
    }

    public function loadPublications()
    {
        $user = Auth::user();

        // Role-based publication fetching
        if ($user->role_id == 2) {
            // Dosen: Hanya publikasi miliknya
            $this->publications = Publication::where('user_id', $user->id)->get();
        } elseif ($user->role_id == 3) {
            // Admin Prodi: Data berdasarkan relasi program_id
            $programId = $user->program_id;
            $this->publications = Publication::whereHas('user', function ($query) use ($programId) {
                $query->where('program_id', $programId);
            })->get();
        } elseif (in_array($user->role_id, [4, 5])) {
            // Admin Fakultas dan Universitas: Semua data publikasi
            $this->publications = Publication::all();
        } else {
            // Role tidak dikenal
            $this->publications = collect();
        }

        // Prepare data for publication counts
        $chartData = $this->publications->groupBy('publication_date')->map(function ($yearGroup) {
            return $yearGroup->groupBy('source')->map(function ($sourceGroup) {
                return $sourceGroup->count();
            });
        });

        // Format data for publication Highcharts
        $this->formattedChartData = [];
        foreach ($chartData as $year => $sources) {
            foreach ($sources as $source => $count) {
                $this->formattedChartData[] = [
                    'year' => $year,
                    'source' => $source,
                    'count' => $count,
                ];
            }
        }

        // Prepare data for citation counts
        $citationData = $this->publications->groupBy('publication_date')->map(function ($yearGroup) {
            return $yearGroup->groupBy('source')->map(function ($sourceGroup) {
                return $sourceGroup->sum('citations');
            });
        });

        // Format data for citation Highcharts
        $this->formattedCitationData = [];
        foreach ($citationData as $year => $sources) {
            foreach ($sources as $source => $count) {
                $this->formattedCitationData[] = [
                    'year' => $year,
                    'source' => $source,
                    'count' => $count,
                ];
            }
        }
    }

    public function render()
    {
        return view('livewire.scraper.publication-chart');
    }
}
