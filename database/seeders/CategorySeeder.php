<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Python',
                'slug' => 'python',
                'description' => 'Apprenez Python, le langage de programmation le plus populaire pour les débutants.',
                'icon' => 'fab fa-python',
                'order' => 1,
            ],
            [
                'name' => 'JavaScript',
                'slug' => 'javascript',
                'description' => 'Maîtrisez JavaScript pour créer des sites web interactifs.',
                'icon' => 'fab fa-js',
                'order' => 2,
            ],
            [
                'name' => 'Java',
                'slug' => 'java',
                'description' => 'Découvrez Java, un langage robuste pour les applications enterprise.',
                'icon' => 'fab fa-java',
                'order' => 3,
            ],
            [
                'name' => 'HTML & CSS',
                'slug' => 'html-css',
                'description' => 'Apprenez à créer des pages web avec HTML et CSS.',
                'icon' => 'fab fa-html5',
                'order' => 4,
            ],
            [
                'name' => 'PHP',
                'slug' => 'php',
                'description' => 'Développez des applications web dynamiques avec PHP.',
                'icon' => 'fab fa-php',
                'order' => 5,
            ],
            [
                'name' => 'SQL',
                'slug' => 'sql',
                'description' => 'Apprenez à manipuler les bases de données avec SQL.',
                'icon' => 'fas fa-database',
                'order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
