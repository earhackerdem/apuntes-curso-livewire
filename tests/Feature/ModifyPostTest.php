<?php

namespace Tests\Feature;

use App\Http\Livewire\CreatePost;
use App\Http\Livewire\ShowPosts;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ModifyPostTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @var \App\Models\User
     */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear un usuario para todas las pruebas
        $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function dashboard_contains_show_posts_component()
    {
        $this->actingAs($this->user)
            ->get('/dashboard')
            ->assertSeeLivewire('show-posts');
    }

    /**
     * @test
     */
    public function it_can_create_a_post()
    {
        Storage::fake('public');
        $this->actingAs($this->user);

        $title = $this->faker->sentence;
        $content = $this->faker->paragraph;

        // Crear un archivo simple en lugar de una imagen
        $file = UploadedFile::fake()->create('post.jpg', 100, 'image/jpeg');

        Livewire::test(CreatePost::class)
            ->set('title', $title)
            ->set('content', $content)
            ->set('image', $file)
            ->call('save');

        $this->assertTrue(Post::where('title', $title)->exists());
        $this->assertDatabaseHas('posts', [
            'title' => $title,
            'content' => $content,
        ]);
    }

    /**
     * @test
     */
    public function it_can_update_a_post()
    {
        Storage::fake('public');
        $this->actingAs($this->user);

        $post = Post::create([
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'image' => 'posts/test-image.jpg'
        ]);

        $newTitle = $this->faker->sentence;
        $newContent = $this->faker->paragraph;

        Livewire::test(ShowPosts::class)
            ->call('edit', $post)
            ->set('post.title', $newTitle)
            ->set('post.content', $newContent)
            ->call('update');

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $newTitle,
            'content' => $newContent,
        ]);
    }

    /**
     * @test
     */
    public function it_can_delete_a_post()
    {
        $this->actingAs($this->user);

        $post = Post::create([
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'image' => 'posts/test-image.jpg'
        ]);

        Livewire::test(ShowPosts::class)
            ->call('delete', $post);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id
        ]);
    }

    /**
     * @test
     */
    public function it_can_filter_posts_by_search()
    {
        $this->actingAs($this->user);

        $postA = Post::create([
            'title' => 'Post con Laravel',
            'content' => 'Contenido de Laravel',
            'image' => 'posts/test-image-a.jpg'
        ]);

        $postB = Post::create([
            'title' => 'Otro post',
            'content' => 'Otro contenido',
            'image' => 'posts/test-image-b.jpg'
        ]);

        Livewire::test(ShowPosts::class)
            ->set('search', 'Laravel')
            ->set('readyToLoad', true)
            ->assertSee('Post con Laravel')
            ->assertDontSee('Otro post');
    }
}
