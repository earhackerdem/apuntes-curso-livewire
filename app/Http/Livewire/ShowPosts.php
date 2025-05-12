<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class ShowPosts extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $post;
    public $image;
    public $identificador;

    #[Url(except: '')]
    public $search = '';

    #[Url(except: 'id')]
    public $sort = 'id';

    #[Url(except: 'desc')]
    public $direction = 'desc';

    #[Url(except: '10')]
    public $cant = '10';

    public $readyToLoad = false;
    public $open_edit = false;

    #[Rule('required')]
    public $post_title;

    #[Rule('required')]
    public $post_content;

    public function mount()
    {
        $this->identificador = rand();
        $this->post = new Post();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if ($this->readyToLoad) {
            $posts = Post::where('title', 'like', '%' . $this->search . '%')
                ->orWhere('content', 'like', '%' . $this->search . '%')
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->cant);
        } else {
            $posts = [];
        }

        return view('livewire.show-posts', compact('posts'));
    }

    public function loadPosts()
    {
        $this->readyToLoad = true;
    }

    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    public function edit(Post $post)
    {
        $this->open_edit = true;
        $this->post = $post;
        $this->post_title = $post->title;
        $this->post_content = $post->content;
    }

    public function update()
    {
        $this->validate();

        $this->post->title = $this->post_title;
        $this->post->content = $this->post_content;

        if ($this->image) {
            Storage::delete([$this->post->image]);
            $this->post->image = $this->image->store('posts');
        }

        $this->post->save();

        $this->reset(['open_edit', 'image']);
        $this->identificador = rand();

        $this->dispatch('alert', message: 'El post se actualizó satisfactoriamente');
    }

    #[On('delete')]
    public function delete(Post $post)
    {
        $post->delete();
    }
}
