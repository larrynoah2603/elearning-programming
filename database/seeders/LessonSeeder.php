<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessons = [
            [
                'title' => 'Introduction à Python',
                'description' => 'Découvrez les bases de Python, de l\'installation à vos premiers programmes.',
                'slug' => 'introduction-python',
                'level' => 'debutant',
                'access_level' => 'free',
                'pdf_file' => 'lessons/introduction-python.pdf',
                'page_count' => 25,
                'user_id' => 1,
                'order' => 1,
            ],
            [
                'title' => 'Les variables et types de données en Python',
                'description' => 'Apprenez à utiliser les variables et comprendre les différents types de données.',
                'slug' => 'variables-types-python',
                'level' => 'debutant',
                'access_level' => 'free',
                'pdf_file' => 'lessons/variables-types-python.pdf',
                'page_count' => 20,
                'user_id' => 1,
                'order' => 2,
            ],
            [
                'title' => 'Les structures conditionnelles en Python',
                'description' => 'Maîtrisez les if, elif et else pour contrôler le flux de vos programmes.',
                'slug' => 'structures-conditionnelles-python',
                'level' => 'debutant',
                'access_level' => 'subscribed',
                'pdf_file' => 'lessons/structures-conditionnelles-python.pdf',
                'page_count' => 30,
                'user_id' => 1,
                'order' => 3,
            ],
            [
                'title' => 'Les boucles en Python',
                'description' => 'Apprenez à utiliser les boucles for et while pour répéter des actions.',
                'slug' => 'boucles-python',
                'level' => 'intermediaire',
                'access_level' => 'subscribed',
                'pdf_file' => 'lessons/boucles-python.pdf',
                'page_count' => 35,
                'user_id' => 1,
                'order' => 4,
            ],
            [
                'title' => 'Introduction à JavaScript',
                'description' => 'Découvrez JavaScript et son rôle dans le développement web.',
                'slug' => 'introduction-javascript',
                'level' => 'debutant',
                'access_level' => 'free',
                'pdf_file' => 'lessons/introduction-javascript.pdf',
                'page_count' => 22,
                'user_id' => 1,
                'order' => 5,
            ],
            [
                'title' => 'HTML5 - Les fondamentaux',
                'description' => 'Apprenez à structurer vos pages web avec HTML5.',
                'slug' => 'html5-fondamentaux',
                'level' => 'debutant',
                'access_level' => 'free',
                'pdf_file' => 'lessons/html5-fondamentaux.pdf',
                'page_count' => 28,
                'user_id' => 1,
                'order' => 6,
            ],
        ];

        foreach ($lessons as $lesson) {
            Lesson::create($lesson);
        }
    }
}
