<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::paginate(50); // for better UX
        return view('players.index', compact('players'));
    }
}
