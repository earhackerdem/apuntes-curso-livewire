<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;

class CreatePost extends Component
{

    public $open = true;

    public $title, $content;

    public function save()
    {
        Post::create([
            'title' => $this->title,
            'content' => $this->content
        ]);
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
