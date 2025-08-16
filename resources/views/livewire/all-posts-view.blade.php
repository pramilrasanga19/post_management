<div class="container">
    <h1
        class="text-center mb-8 text-5xl md:text-6xl font-extrabold text-slate-900 tracking-tight drop-shadow-md hover:text-indigo-600 transition-colors duration-300">
        All Posts
    </h1>
    <a href="{{ route('dashboard') }}" type="button" class="btn btn-secondary" style="margin-bottom:15px;">
        <i class="fas fa-arrow-left me-2"></i>Dashboard
    </a>
    <div class="row mb-4">
<div class="col-md-6 mb-3 mb-md-0">
    <input wire:model.live.debounce.300ms="search" 
           type="text" 
           placeholder="Search posts..."
           class="form-control"
           style="background-color: #3b82f6; color: white; border-color: #2563eb;">
</div>
        <div class="col-md-6 d-flex align-items-center justify-content-md-end flex-wrap gap-2">
            <span class="text-muted small">Sort:</span>
            <select wire:model.live="sortField" class="form-select form-select-sm w-auto">
                <option value="created_at">Date</option>
                <option value="title">Title</option>
                <option value="user_id">Author</option>
            </select>
            <select wire:model.live="sortDirection" class="form-select form-select-sm w-auto">
                <option value="desc">Newest First</option>
                <option value="asc">Oldest First</option>
            </select>
            <select wire:model.live="perPage" class="form-select form-select-sm w-auto">
                <option value="5">5 per page</option>
                <option value="10">10 per page</option>
                <option value="20">20 per page</option>
            </select>
        </div>
    </div>

    @forelse ($posts as $post)
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h2 class="card-title h4">{{ $post->title }}</h2>
                </div>

                <div class="card-text mb-3">
                    {!! nl2br(e($post->content)) !!}
                </div>

                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start border-top pt-3 small text-muted">
                    <div class="mb-2 mb-md-0 d-flex align-items-center gap-2">
                        <span class="badge bg-primary rounded-pill px-2">Author:</span>
                        <span>{{ $post->user->name }}</span>
                    </div>
                    <div class="d-flex gap-4">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success rounded-pill px-2 py-1">Posted at:</span>
                            <span>{{ $post->created_at->format('M j, Y g:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <div class="text-muted mb-4">
                <i class="fas fa-inbox fa-3x"></i>
            </div>
            <h3 class="h4 text-muted">No posts found</h3>
            @if ($search)
                <p class="text-muted mt-2">Try adjusting your search query</p>
            @endif
        </div>
    @endforelse

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>
