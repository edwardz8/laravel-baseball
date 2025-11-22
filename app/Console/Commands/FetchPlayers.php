<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Player;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchPlayers extends Command
{
    protected $signature = 'mlb:fetch-players';
    protected $description = 'Fetch all MLB players from API and store in database';

    public function handle()
    {
        $apiKey = env('BALL_DONT_LIE_MLB_API'); // Replace with your personal API key
        $baseUrl = 'https://api.balldontlie.io/mlb/v1/players';
        $perPage = 100; // Max per page
        $cursor = null;
        $totalFetched = 0;

        $this->info('Starting to fetch MLB players...');

        do {
            $url = $baseUrl . '?per_page=' . $perPage;
            if ($cursor) {
                $url .= '&cursor=' . $cursor;
            }

            $response = Http::withHeaders([
                'Authorization' => $apiKey,
            ])->get($url);

            if ($response->failed()) {
                $this->error('API request failed: ' . $response->body());
                Log::error('MLB API Error: ' . $response->body());
                return 1;
            }

            $data = $response->json();
            $players = $data['data'] ?? [];
            $nextCursor = $data['meta']['next_cursor'] ?? null;

            foreach ($players as $player) {
                // Upsert to avoid duplicates (using api_id)
                Player::updateOrCreate(
                    ['api_id' => $player['id']],
                    [
                        'first_name' => $player['first_name'],
                        'last_name' => $player['last_name'],
                        'full_name' => $player['full_name'],
                        'position' => $player['position'],
                        'active' => $player['active'],
                        'jersey' => $player['jersey'],
                        'dob' => $player['dob'] ? date('Y-m-d', strtotime($player['dob'])) : null,
                        'age' => $player['age'],
                        'height' => $player['height'],
                        'weight' => $player['weight'],
                        'bats_throws' => $player['bats_throws'],
                        'team_id' => $player['team']['id'] ?? null,
                        'team_name' => $player['team']['name'] ?? null,
                        'team_location' => $player['team']['location'] ?? null,
                        'team_display_name' => $player['team']['display_name'] ?? null,
                    ]
                );
                $totalFetched++;
            }

            $cursor = $nextCursor;
            $this->info("Fetched {$totalFetched} players so far...");

            // Rate limit respect: Free tier is 5 req/min, so add delay if needed
            sleep(12); // ~5 req/min safe delay

        } while ($cursor);

        $this->info("All done! Total players fetched and stored: {$totalFetched}");
        return 0;
    }
}