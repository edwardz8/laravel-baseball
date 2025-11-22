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
            --text: #f0f0f0;
            --text-secondary: #ff7b00ff;
            --card-bg: rgba(28, 28, 35, 0.6);
        }
        body { background: #0a0a0a; color: var(--text); min-height: 100vh; font-family: 'SF Pro Display', -apple-system, sans-serif; }
        .glass-header { background: var(--glass); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border); padding: 1.5rem 0; position: sticky; top: 0; z-index: 10; }
        .search-input { background: rgba(40,40,50,0.9); border: 1px solid var(--border); color: white; border-radius: 16px; padding: 0.75rem 1rem; }
        .search-input:focus { background: rgba(50,50,60,0.95); box-shadow: 0 0 0 3px rgba(255,255,255,0.1); color: white; }
        .player-card { background: var(--card-bg); backdrop-filter: blur(16px); border: 1px solid var(--border); transition: all 0.4s ease; box-shadow: 0 8px 32px rgba(0,0,0,0.5); }
        .player-card:hover { transform: translateY(-8px); box-shadow: 0 20px 50px rgba(0,0,0,0.7); border-color: rgba(255,255,255,0.15); }
        .player-initials { height: 120px; display: flex; align-items: center; justify-content: center; font-size: 2.8rem; font-weight: 900; color: white; background: linear-gradient(135deg, #1e1e1e, #333333); }
        .team-logo { width: 36px; height: 36px; object-fit: contain; filter: drop-shadow(0 2px 8px rgba(0,0,0,0.6)); }
        .pagination .page-link { background: rgba(40,40,50,0.8); border: 1px solid var(--border); color: var(--text); }
        .pagination .page-item.active .page-link { background: rgba(255,255,255,0.15); }
        .back-home { color: var(--text-secondary); text-decoration: none; }
        .back-home:hover { color: white; }
    </style>
</head>
<body class="pb-5">

    <!-- Sticky Header with Search -->
    <div class="glass-header">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div>
                    <h2 class="mb-0 fw-bold">
                        <i class="bi bi-people-fill me-3"></i>MLB Players
                    </h2>
                    <div class="text-secondary small">
                        {{ $players->total() }} players found
                        @if($search)
                            for "<strong>{{ $search }}</strong>"
                            <a href="{{ route('players.index') }}" class="text-decoration-underline ms-2 small">clear</a>
                        @endif
                    </div>
                </div>

                <!-- Search Form -->
                <form method="GET" action="{{ route('players.index') }}" class="d-flex" style="max-width: 420px; width: 100%;">
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control search-input me-2" 
                        placeholder="Search players or teams..." 
                        value="{{ $search ?? '' }}"
                        autofocus
                    >
                    <button type="submit" class="btn btn-outline-light">
                        <i class="bi bi-search"></i>
                    </button>
                </form>

                <a href="{{ url('/') }}" class="back-home">
                    <i class="bi bi-arrow-left me-1"></i> Home
                </a>
            </div>
        </div>
    </div>

    <div class="container py-5">
        @if($players->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                @foreach($players as $player)
                    <div class="col">
                        <div class="card h-100 player-card text-white">
                            <div class="player-initials">
                                {{ strtoupper(substr($player->first_name, 0, 1) . substr($player->last_name, 0, 1)) }}
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-1 fw-bold">{{ $player->full_name }}</h5>
                                <p class="text-white small mb-3">{{ $player->position ?? '—' }}</p>

                                <div class="d-flex align-items-center mb-3">
                                    @if($player->team_abbreviation)
                                        <img src="https://www.mlb.com/assets/images/team-logos/{{ $player->team_abbreviation }}.svg" 
                                             alt="{{ $player->team_display_name }}" class="team-logo me-3"
                                             onerror="this.style.display='none'">
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $player->team_display_name ?? 'Free Agent' }}</div>
                                    </div>
                                </div>

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

                                <div class="mt-3">
                                    <span class="badge {{ $player->active ? 'bg-success' : 'bg-secondary' }}">
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
                {{ $players->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-secondary fs-4">No players found matching "{{ $search ?? 'your search' }}"</p>
                <a href="{{ route('players.index') }}" class="btn btn-outline-light mt-3">Show all players</a>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>