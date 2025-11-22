<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLB Players</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .player-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .player-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }
        .card-img-placeholder {
            background: linear-gradient(135deg, #929397ff 0%, #292829ff 100%);
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
            text-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }
        .badge-active {
            font-size: 0.75rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-primary">MLB Players Directory</h1>
            <p class="text-muted mb-0">Total: <strong>{{ $players->total() }}</strong></p>
        </div>

        @if($players->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                @foreach($players as $player)
                    <div class="col">
                        <div class="card h-100 player-card">
                            <!-- Placeholder Image (Initials) -->
                            <div class="card-img-placeholder">
                                {{ strtoupper(substr($player->first_name, 0, 1) . substr($player->last_name, 0, 1)) }}
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-1">
                                    {{ $player->full_name }}
                                </h5>
                                <p class="text-muted small mb-2">
                                    <strong>Position:</strong> {{ $player->position ?? 'N/A' }}
                                </p>

                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge {{ $player->active ? 'bg-success' : 'bg-secondary' }} badge-active">
                                            {{ $player->active ? 'Active' : 'Inactive' }}
                                        </span>
                                        @if($player->jersey)
                                            <span class="text-primary fw-bold">#{{ $player->jersey }}</span>
                                        @endif
                                    </div>

                                    <hr class="my-2">

                                    <div class="small text-muted">
                                        <div>
                                            <strong>Team:</strong>
                                            @if($player->team_display_name)
                                            <span class="text-primary">{{ $player->team_display_name }}</span>
                                            <!-- <small class="text-muted">{{ $player->team_location }}</small> -->
                                            @else
                                                Free Agent
                                            @endif
                                        </div>
                                        @if($player->age)
                                            <div><strong>Age:</strong> {{ $player->age }}</div>
                                        @endif
                                        @if($player->height && $player->weight)
                                            <div><strong>Size:</strong> {{ $player->height }} / {{ $player->weight }}</div>
                                        @endif
                                        @if($player->bats_throws)
                                            <div><strong>B/T:</strong> {{ $player->bats_throws }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-5">
                {{ $players->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted">No players found. Run the command to fetch data:</p>
                <code class="bg-light p-2 rounded">php artisan mlb:fetch-players</code>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>