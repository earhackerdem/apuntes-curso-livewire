<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;

class CreatePost extends Component
{
    use WithFileUploads;

    public $open = false;

    #[Rule('required')]
    public $title;

    #[Rule('required')]
    public $content;

    #[Rule('required|image|max:2048')]
    public $image;

    public $identificador;

    public function mount()
    {
        $this->identificador = rand();
    }

    public function save()
    {
        $this->validate();

        $image = $this->image->store('posts');

        Post::create([
            'title' => $this->title,
            'content' => $this->content,
            'image' => $image
        ]);

        $this->reset(['open', 'title', 'content', 'image']);
        $this->identificador = rand();

        $this->dispatch('render');
        $this->dispatch('alert', 'El post se creó satisfactoriamente');
    }

    public function render()
    {
        return view('livewire.create-post');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->reset(['title', 'content', 'image']);
            $this->identificador = rand();
            $this->dispatch('resetCKEditor');
        }
    }
}
