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
use Illuminate\Foundation\Testing\TestCase;
use Livewire\Livewire;
use Tests\TestCase as BaseTestCase;

class PostManagementTest extends BaseTestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function page_contains_show_posts_livewire_component()
    {
        $user = User::factory()->create();
        // Asegurar que es una instancia de User
        $this->assertInstanceOf(User::class, $user);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertSeeLivewire('show-posts');
    }

    /**
     * @test
     */
    public function can_create_post()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->assertInstanceOf(User::class, $user);

        $this->actingAs($user);

        $title = $this->faker->sentence;
        $content = $this->faker->paragraph;

        // Usar un archivo de texto en lugar de una imagen si GD no está disponible
        if (extension_loaded('gd') && function_exists('imagejpeg')) {
            $file = UploadedFile::fake()->image('post.jpg');
        } else {
            $file = UploadedFile::fake()->create('post.jpg', 100);
        }

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
    public function can_update_post()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->assertInstanceOf(User::class, $user);

        $this->actingAs($user);

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
    public function can_delete_post()
    {
        $user = User::factory()->create();
        $this->assertInstanceOf(User::class, $user);

        $this->actingAs($user);

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
    public function can_filter_posts()
    {
        $user = User::factory()->create();
        $this->assertInstanceOf(User::class, $user);

        $this->actingAs($user);

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
