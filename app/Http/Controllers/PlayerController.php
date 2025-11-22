<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        // Get the search term from the URL (?search=...)
        $search = $request->query('search');

        // Build the query
        $query = Player::query();

        // Apply search if present
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('team_display_name', 'LIKE', "%{$search}%")
                  ->orWhere('team_abbreviation', 'LIKE', "%{$search}%")
                  ->orWhere('team_location', 'LIKE', "%{$search}%");
            });
        }

        // Always order by last name for consistency
        $query->orderBy('last_name');

        // Paginate the results (24 per page = perfect 4-column grid)
        $players = $query->paginate(24);

        // Keep the search term in pagination links
        $players->appends(['search' => $search]);

        // Pass both variables to the view
        return view('players.index', compact('players', 'search'));
    }
}
