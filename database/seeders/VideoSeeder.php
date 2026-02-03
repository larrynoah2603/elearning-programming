<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videos = [
            [
                'title' => 'Introduction à Python - Partie 1',
                'description' => 'Découvrez les bases de Python dans cette vidéo introductive.',
                'slug' => 'introduction-python-partie-1',
                'level' => 'debutant',
                'access_level' => 'subscribed',
                'video_file' => 'videos/introduction-python-1.mp4',
                'thumbnail' => 'videos/thumbnails/introduction-python-1.jpg',
                'duration' => 900, // 15 minutes
                'user_id' => 1,
                'order' => 1,
            ],
            [
                'title' => 'Les variables en Python',
                'description' => 'Apprenez tout sur les variables et les types de données en Python.',
                'slug' => 'variables-python',
                'level' => 'debutant',
                'access_level' => 'subscribed',
                'video_file' => 'videos/variables-python.mp4',
                'thumbnail' => 'videos/thumbnails/variables-python.jpg',
                'duration' => 720, // 12 minutes
                'user_id' => 1,
                'order' => 2,
            ],
            [
                'title' => 'Les conditions en Python',
                'description' => 'Maîtrisez les structures conditionnelles if, elif et else.',
                'slug' => 'conditions-python',
                'level' => 'debutant',
                'access_level' => 'subscribed',
                'video_file' => 'videos/conditions-python.mp4',
                'thumbnail' => 'videos/thumbnails/conditions-python.jpg',
                'duration' => 840, // 14 minutes
                'user_id' => 1,
                'order' => 3,
            ],
            [
                'title' => 'Introduction à JavaScript',
                'description' => 'Premiers pas avec JavaScript pour le web.',
                'slug' => 'introduction-javascript',
                'level' => 'debutant',
                'access_level' => 'subscribed',
                'video_file' => 'videos/introduction-javascript.mp4',
                'thumbnail' => 'videos/thumbnails/introduction-javascript.jpg',
                'duration' => 600, // 10 minutes
                'user_id' => 1,
                'order' => 4,
            ],
            [
                'title' => 'HTML5 pour débutants',
                'description' => 'Apprenez à structurer vos pages web avec HTML5.',
                'slug' => 'html5-debutants',
                'level' => 'debutant',
                'access_level' => 'subscribed',
                'video_file' => 'videos/html5-debutants.mp4',
                'thumbnail' => 'videos/thumbnails/html5-debutants.jpg',
                'duration' => 780, // 13 minutes
                'user_id' => 1,
                'order' => 5,
            ],
            [
                'title' => 'CSS3 - Les bases',
                'description' => 'Stylisez vos pages web avec CSS3.',
                'slug' => 'css3-bases',
                'level' => 'debutant',
                'access_level' => 'subscribed',
                'video_file' => 'videos/css3-bases.mp4',
                'thumbnail' => 'videos/thumbnails/css3-bases.jpg',
                'duration' => 660, // 11 minutes
                'user_id' => 1,
                'order' => 6,
            ],
        ];

        foreach ($videos as $video) {
            Video::create($video);
        }
    }
}
