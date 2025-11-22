<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
         'api_id', 'first_name', 'last_name', 'full_name', 'position', 'active',
        'jersey', 'dob', 'age', 'height', 'weight', 'bats_throws', 'team_id', 'team_name', 'team_location', 'team_display_name',
    ];

    protected $casts = [
        'active' => 'boolean',
        'dob' => 'date'
    ];
}
