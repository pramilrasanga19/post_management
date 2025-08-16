<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Posts extends Component
{
    use WithPagination;

    public $title, $content, $post_id, $user_id;
    public $isModalOpen = false;
    public $search = '';
    public $showAllPosts = false;
    public $isViewModalOpen = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'user_id' => 'required|exists:users,id',
    ];

    public function mount()
    {
        $this->user_id = Auth::id();
    }

    public function render()
    {
        $users = collect();
        if (Auth::user()->role === 'admin') {
            $users = User::where('role', '!=', 'admin')->get();
        }

        $query = Post::with('user')
            ->when(!$this->showAllPosts && Auth::user()->role !== 'admin', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('content', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest();

        $posts = $query->paginate(10);

        return view('livewire.posts', [
            'posts' => $posts,
            'isAdmin' => Auth::user()->role === 'admin',
            'users' => $users,
        ]);
    }

    public function openViewModal($id)
    {
        $post = Post::with('user')->findOrFail($id);
        $this->post_id = $id;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->isViewModalOpen = true;
    }

    public function closeViewModal()
    {
        $this->isViewModalOpen = false;
        $this->resetInputFields();
    }

    public function toggleAllPosts()
    {
        $this->showAllPosts = !$this->showAllPosts;
        $this->resetPage();
    }


    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function resetInputFields()
    {
        $this->title = '';
        $this->content = '';
        $this->post_id = '';
    }

    public function store()
    {
        $this->validate();

        Post::updateOrCreate(['id' => $this->post_id], [
            'title' => $this->title,
            'content' => $this->content,
            'user_id' => Auth::user()->role === 'admin' ? $this->user_id : Auth::id(),
        ]);

        session()->flash(
            'message',
            $this->post_id ? 'Post Updated Successfully.' : 'Post Created Successfully.'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);


        if (Auth::user()->role !== 'admin' && $post->user_id !== Auth::id()) {
            abort(403);
        }

        $this->post_id = $id;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->user_id = $post->user_id;

        $this->openModal();
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);

        if (Auth::user()->role === 'admin' || $post->user_id === Auth::id()) {
            $post->delete();
            session()->flash('message', 'Post Deleted Successfully.');
        } else {
            session()->flash('error', 'You are not authorized to delete this post.');
        }
    }
}
