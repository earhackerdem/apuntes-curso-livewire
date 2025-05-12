<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Asegurarse de que el directorio existe
        $directory = 'public/storage/posts';
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Generar un nombre de archivo aleatorio
        $filename = $this->faker->uuid . '.jpg';

        try {
            // Intentar usar faker para generar imagen real
            $image = $this->faker->image($directory, 640, 480, null, false);
            return [
                'title' => $this->faker->sentence(),
                'content' => $this->faker->text(),
                'image' => 'posts/' . $image
            ];
        } catch (\Exception $e) {
            // Si falla, usar un placeholder
            return [
                'title' => $this->faker->sentence(),
                'content' => $this->faker->text(),
                'image' => 'posts/placeholder.jpg'
            ];
        }
    }
}
