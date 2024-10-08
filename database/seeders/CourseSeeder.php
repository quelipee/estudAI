<?php

namespace Database\Seeders;

use App\Domains\CourseDomain\Enums\Category;
use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Desenvolvimento Web',
                'category' => Category::SoftwareDevelopment,
                'description' => 'Aprenda a criar sites e páginas da web do zero! Neste curso, você vai aprender os fundamentos do HTML, CSS e JavaScript, as linguagens básicas para construir páginas web. Você vai aprender a criar layouts, adicionar estilos, inserir imagens e muito mais. É uma ótima opção para quem quer se aventurar no mundo da criação de conteúdo online e ter um site próprio.'
            ],
            [
                'title' => 'Programação',
                'category' => Category::SoftwareDevelopment,
                'description' => 'Mergulhe no mundo da programação e aprenda a criar programas que resolvem problemas e automatizam tarefas. Neste curso, você vai aprender os conceitos básicos da programação, como variáveis, operadores, estruturas de controle e funções, usando uma linguagem de programação fácil de aprender, como Python ou JavaScript. Com a programação, você pode desenvolver jogos, aplicativos, scripts e muito mais!',
            ],[
                'title' => 'Redes de Computadores',
                'category' => Category::ITInfrastructure,
                'description' => 'Entenda como os computadores se conectam e compartilham informações. Neste curso, você vai aprender sobre os diferentes tipos de redes, como a internet funciona, como configurar roteadores e muito mais. É uma ótima opção para quem quer entender melhor como a tecnologia funciona e como se conectar à internet de forma segura e eficiente.'
            ],
            [
                'title' => 'python para iniciantes',
                'category' => Category::SoftwareDevelopment,
                'description' => 'Aprenda os fundamentos da linguagem Python de forma simples e prática. Ideal para quem está começando na programação.'
            ],
        ];

        foreach ($courses as $data){
            if (Course::where(['title' => $data['title']])->exists()){
                continue;
            }
            Course::create($data);
        }
    }
}
