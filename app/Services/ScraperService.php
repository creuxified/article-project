<?php

namespace App\Services;

use GuzzleHttp\Client;

class ScraperService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function scrape($url)
    {
        // Mengirim permintaan GET ke URL
        $response = $this->client->request('GET', $url);

        // Mengambil konten HTML dari respons
        $html = $response->getBody()->getContents();

        // Menonaktifkan tombol "Show more"
        $html = $this->disableShowMoreButton($html);

        // Mengolah konten HTML
        return $this->parseHtml($html, $url);
    }

    protected function parseHtml($html)
    {
        $dom = new \DOMDocument();

        // Load HTML dan periksa apakah berhasil
        libxml_use_internal_errors(true);
        if ($dom->loadHTML($html)) {
            $xpath = new \DOMXPath($dom);

            // Ekstrak data profil
            $profileData = [
                'name' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@id="gsc_prf_in"]'),
                'affiliation' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@class="gsc_prf_il"]'),
                'email_verified' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@id="gsc_prf_ivh"]'),
                'interests' => $this->extractText($xpath, '//div[@id="gsc_prf_i"]//div[@id="gsc_prf_int"]'),
                'photo_url' => $this->extractAttribute($xpath, '//div[@id="gsc_prf_pu"]//img', 'src')
            ];

            // Ekstrak data sitasi
            $citedByData = [
                'citations_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[1]//td[2]'),
                'citations_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[1]//td[3]'),
                'h_index_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[2]//td[2]'),
                'h_index_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[2]//td[3]'),
                'i10_index_all' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[3]//td[2]'),
                'i10_index_since_2019' => $this->extractTableCell($xpath, '//table[@id="gsc_rsb_st"]//tr[3]//td[3]')
            ];

            // Ekstrak data artikel
            $articleData = $this->extractArticleData($xpath);

            // Mengambil data grafik tahun dan jumlah artikel
            $chartData = $this->extractChartData($xpath);

            // Kembalikan data yang sudah diekstrak
            return [
                'profile' => $profileData,
                'cited_by' => $citedByData,
                'articles' => $articleData,
                'chart' => $chartData
            ];
        } else {
            // Mengembalikan kesalahan atau menangani kegagalan
            return ['error' => 'Failed to load HTML'];
        }
    }

    private function extractArticleData($xpath)
    {
        // Mengambil elemen judul, tahun, sitasi artikel, dan URL artikel
        $titles = $xpath->query('//tr[@class="gsc_a_tr"]//a[@class="gsc_a_at"]');
        $years = $xpath->query('//tr[@class="gsc_a_tr"]//span[@class="gsc_a_h gsc_a_hc gs_ibl"]');
        $citations = $xpath->query('//tr[@class="gsc_a_tr"]//a[@class="gsc_a_ac gs_ibl"]');
        $links = $xpath->query('//tr[@class="gsc_a_tr"]//a[@class="gsc_a_at"]'); // Extracting article URL

        $articles = [];
        foreach ($titles as $index => $title) {
            // Pastikan elemen tahun dan sitasi tersedia sebelum mengambil nilai
            $year = $years->length > $index ? trim($years->item($index)->nodeValue) : 'Tahun tidak ditemukan';
            $citationCount = $citations->length > $index ? trim($citations->item($index)->nodeValue) : '0';

            $article = [
                'title' => trim($title->nodeValue),
                'year' => $year,
                'citations' => $citationCount,
                'url' => $links->length > $index ? 'https://scholar.google.com' . $links->item($index)->getAttribute('href') : null // Getting article link
            ];
            $articles[] = $article;
        }

        return $articles;
    }

    private function extractText($xpath, $query)
    {
        $nodes = $xpath->query($query);
        return $nodes->length > 0 ? $nodes->item(0)->nodeValue : null;
    }

    private function extractAttribute($xpath, $query, $attribute)
    {
        $nodes = $xpath->query($query);
        return $nodes->length > 0 ? $nodes->item(0)->getAttribute($attribute) : null;
    }

    private function extractTableCell($xpath, $query)
    {
        $nodes = $xpath->query($query);
        return $nodes->length > 0 ? $nodes->item(0)->nodeValue : null;
    }

    protected function disableShowMoreButton($html)
    {
        // Menonaktifkan tombol "Show more"
        // Mencari elemen tombol "Show more" dan menggantinya dengan versi disabled
        $html = preg_replace('/(<button[^>]*id="gsc_bpf_more"[^>]*>)/', '<button type="button" id="gsc_bpf_more" class="gs_btnPD gs_in_ib gs_btn_flat gs_btn_lrge gs_btn_lsu" disabled><span class="gs_wr"><span class="gs_ico"></span><span class="gs_lbl">Show more</span></span></button>', $html);

        return $html;
    }

    private function extractChartData($xpath)
    {
        $years = $xpath->query('//div[@class="gsc_md_hist_b"]//span[@class="gsc_g_t"]');
        $counts = $xpath->query('//div[@class="gsc_md_hist_b"]//a[@class="gsc_g_a"]//span[@class="gsc_g_al"]');

        $chartData = [];
        foreach ($years as $index => $year) {
            $yearText = trim($year->nodeValue);
            $count = $counts->length > $index ? trim($counts->item($index)->nodeValue) : '0';
            $chartData[] = ['year' => $yearText, 'count' => $count];
        }

        return $chartData;
    }
}
