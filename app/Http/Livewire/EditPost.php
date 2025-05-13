<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;

use Illuminate\Support\Facades\Storage;

class EditPost extends Component
{
    use WithFileUploads;

    public $open = false;
    public $post;
    public $image;
    public $identificador;

    #[Rule('required')]
    public $title;

    #[Rule('required')]
    public $content;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->identificador = rand();
    }

    public function save()
    {
        $this->validate();

        $this->post->title = $this->title;
        $this->post->content = $this->content;

        if ($this->image) {
            Storage::delete([$this->post->image]);
            $this->post->image = $this->image->store('posts');
        }

        $this->post->save();

        $this->reset(['open', 'image']);
        $this->identificador = rand();

        $this->dispatch('render');
        $this->dispatch('alert', 'El post se actualizó satisfactoriamente');
    }

    public function render()
    {
        return view('livewire.edit-post');
    }
}
