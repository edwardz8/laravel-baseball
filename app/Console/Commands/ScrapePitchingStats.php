<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Pitcher;

class ScrapePitchingStats extends Command
{
    protected $signature = 'scrape:pitching';
    protected $description = 'Scrape Player Standard Pitching table from Baseball-Reference';

    public function handle(): int
    {
        $url = 'https://www.baseball-reference.com/leagues/majors/2025-standard-pitching.shtml';
        $client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (compatible; Laravel Scraper/1.0)', // Avoid bot detection
            ],
            'timeout' => 30,
        ]);

        try {
            $response = $client->get($url);
            $html = (string) $response->getBody();
        } catch (RequestException $e) {
            $this->error('Failed to fetch page: ' . $e->getMessage());
            return 1;
        }

        $crawler = new Crawler($html);

        // Target the pitching table (inspect page; it's often the first <table> with class 'sortable' or similar; adjust selector if needed)
        // Based on page structure, target tbody rows after thead with specific headers
        $rows = $crawler->filter('table#players_standard_pitching tbody tr')->each(function (Crawler $row) {
            $cells = $row->filter('td');
            if ($cells->count() < 2) return null; // Skip empty rows

            return [
                'rank' => (int) trim($cells->eq(0)->text()),
                'player' => trim($cells->eq(1)->text()), // e.g., "Logan Webb"
                'age' => (int) trim($cells->eq(2)->text()),
                'team' => trim($cells->eq(3)->text() ?? ''), // Team abbr
                'war' => trim($cells->eq(5)->text() ?? ''), // War
/* 
                'league' => trim($cells->eq(4)->text() ?? ''), // League
                'wins' => trim($cells->eq(6)->text() ?? ''), // Wins
                'losses' => trim($cells->eq(7)->text() ?? ''), // Losses
                'era' => trim($cells->eq(9)->text() ?? ''), // ERA
                'games' => trim($cells->eq(10)->text() ?? ''), // Games
                'games_started' => trim($cells->eq(11)->text() ?? ''), // Games Started
                'saves' => trim($cells->eq(15)->text() ?? ''), // Saves
                'innings_pitched' => trim($cells->eq(16)->text() ?? ''), // Innings Pitched
                'strikeouts' => trim($cells->eq(23)->text() ?? ''), // Strikeouts
                'era_plus' => trim($cells->eq(28)->text() ?? ''), // ERA+
                'fip' => trim($cells->eq(29)->text() ?? ''), // FIP
                'whip' => trim($cells->eq(30)->text() ?? ''), // WHIP
*/
            ];
        });

        // Filter out nulls and insert/update (use upsert for efficiency)
        $validRows = array_filter($rows);
        Pitcher::upsert(
            $validRows,
            ['rank'],
            ['player', 'age', 'team', 'war']
        );

        $this->info('Scraped ' . count($validRows) . ' pitchers successfully.');
        return 0;
    }
}

