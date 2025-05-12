<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Asegurarse de que exista el directorio para almacenar las imágenes
        Storage::deleteDirectory('posts');
        Storage::makeDirectory('posts');

        // Verificar que existe el directorio public/storage (enlace simbólico)
        if (!File::exists(public_path('storage'))) {
            $this->command->call('storage:link');
        }

        \App\Models\Post::factory(100)->create();
    }
}
