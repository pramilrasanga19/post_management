<div>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Welcome!</h1>

        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="mb-4 flex justify-between items-center">
            <div class="w-1/2">
                <input wire:model.live="search" type="text" placeholder="Search posts..." class="form-control">
            </div>
            <div class="flex gap-2">
                <div class="flex flex-wrap gap-3">
                    {{-- @if ($isAdmin)
                        <button wire:click="toggleAllPosts" class="modern-btn secondary-btn">
                            <i class="fas {{ $showAllPosts ? 'fa-user' : 'fa-users' }} mr-2"></i>
                            {{ $showAllPosts ? 'My Posts' : 'All Posts' }}
                        </button>
                    @endif --}}

                    <a href="{{ route('all-posts') }}" type="button" class="btn btn-success">
                        <i class="fas fa-clipboard-list mr-2"></i>
                        @if (auth()->user()->role === 'admin')
                            All Posts
                        @else
                            My Posts
                        @endif
                    </a>
                </div>


                <button wire:click="create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#postModal">
                    <i class="fas fa-plus me-2"></i>Create New Post
                </button>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-left">#</th>
                                @if ($isAdmin)
                                    <th class="text-left">User</th>
                                @endif
                                <th class="text-left">Title</th>
                                <th class="text-left">Content</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($posts as $index => $post)
                                <tr>
                                    <td>{{ $posts->firstItem() + $index }}</td>
                                    @if ($isAdmin)
                                        <td>{{ $post->user->name ?? 'N/A' }}</td>
                                    @endif
                                    <td>{{ $post->title }}</td>
                                    <td>{{ Str::limit($post->content, 50) }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button wire:click="openViewModal({{ $post->id }})"
                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#viewModal">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button wire:click="edit({{ $post->id }})"
                                                class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#postModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="delete({{ $post->id }})"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isAdmin ? 5 : 4 }}" class="text-center py-4">No posts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">View Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($isAdmin)
                        <div class="mb-3">
                            <label class="form-label">Author:</label>
                            <p class="form-control-plaintext">{{ $post->user->name ?? 'N/A' }}</p>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Title:</label>
                        <p class="form-control-plaintext">{{ $title }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content:</label>
                        <div class="form-control-plaintext border rounded p-2 bg-light">{{ $content }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="postModalLabel">{{ $post_id ? 'Edit Post' : 'Create Post' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    @if ($isAdmin)
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Author</label>
                            <select wire:model="user_id" id="user_id" class="form-select">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $user->id == $user_id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" wire:model="title" id="title" class="form-control">
                        @error('title')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea wire:model="content" id="content" rows="5" class="form-control"></textarea>
                        @error('content')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="store" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
