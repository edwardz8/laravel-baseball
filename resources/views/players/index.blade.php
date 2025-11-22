{{-- resources/views/players/index.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLB Players • Directory</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --bg: #0a0a0a;
            --glass: rgba(20, 20, 25, 0.75);
            --border: rgba(255, 255, 255, 0.08);
            --text: #ffffffff;
            --text-secondary: #ffffffff;
            --card-bg: rgba(28, 28, 35, 0.6);
        }

        body {
            background: #0a0a0a;
            color: var(--text);
            min-height: 100vh;
            font-family: 'SF Pro Display', -apple-system, sans-serif;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(40, 40, 60, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(60, 60, 80, 0.2) 0%, transparent 50%);
        }

        .glass-header {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 1.5rem 0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .player-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }

        .player-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .player-initials {
            width: 100%;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8rem;
            font-weight: 900;
            color: white;
            background: linear-gradient(135deg, #1e1e1e, #333333);
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }

        .team-logo {
            width: 36px;
            height: 36px;
            object-fit: contain;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.6));
        }

        .badge-active {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }

        .pagination .page-link {
            background: rgba(40, 40, 50, 0.8);
            border: 1px solid var(--border);
            color: var(--text);
        }

        .pagination .page-item.active .page-link {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .back-home {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .back-home:hover {
            color: white;
        }
    </style>
</head>
<body class="pb-5">

    <!-- Sticky Glass Header -->
    <div class="glass-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 fw-bold">
                    <i class="bi bi-people-fill me-3"></i>
                    MLB Players Directory
                </h2>
                <a href="{{ url('/') }}" class="back-home">
                    <i class="bi bi-arrow-left me-2"></i>Back to Home
                </a>
            </div>
            <div class="mt-2 text-secondary small">
                {{ $players->total() }} players • Updated daily
            </div>
        </div>
    </div>

    <div class="container py-5">
        @if($players->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                @foreach($players as $player)
                    <div class="col">
                        <div class="card h-100 player-card text-white">
                            <!-- Initials Header -->
                            <div class="player-initials">
                                {{ strtoupper(substr($player->first_name, 0, 1) . substr($player->last_name, 0, 1)) }}
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-1 fw-bold">
                                    {{ $player->full_name }}
                                </h5>
                                <p class="text-secondary small mb-3">
                                    {{ $player->position ?? 'Unknown Position' }}
                                </p>

                                <!-- Team + Logo -->
                                <div class="d-flex align-items-center mb-3">
                                    @if($player->team_abbreviation)
                                        <img 
                                            src="https://www.mlb.com/assets/images/team-logos/{{ $player->team_abbreviation }}.svg" 
                                            alt="{{ $player->team_display_name }}"
                                            class="team-logo me-3"
                                            onerror="this.style.display='none'"
                                        >
                                    @endif
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $player->team_display_name ?? 'Free Agent' }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Details Grid -->
                                <div class="row small text-secondary mt-auto">
                                    <div class="col-6">
                                        <div><strong>Jersey</strong> #{{ $player->jersey ?? '—' }}</div>
                                        <div><strong>Age</strong> {{ $player->age ?? '—' }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div><strong>Size</strong> {{ $player->height ?? '?' }} / {{ $player->weight ?? '?' }}</div>
                                        <div><strong>B/T</strong> {{ $player->bats_throws ?? '—' }}</div>
                                    </div>
                                </div>

                                <!-- Active Badge -->
                                <div class="mt-3">
                                    <span class="badge {{ $player->active ? 'bg-success' : 'bg-secondary' }} badge-active">
                                        {{ $player->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $players->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-secondary">No players found. Run the fetch command to populate the database.</p>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>