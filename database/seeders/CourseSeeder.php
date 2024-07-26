<?php

namespace Database\Seeders;

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
                'category' => 'Desenvolvimento de Software',
                'description' => 'Aprenda a criar sites e páginas da web do zero! Neste curso, você vai aprender os fundamentos do HTML, CSS e JavaScript, as linguagens básicas para construir páginas web. Você vai aprender a criar layouts, adicionar estilos, inserir imagens e muito mais. É uma ótima opção para quem quer se aventurar no mundo da criação de conteúdo online e ter um site próprio.'
            ],
            [
                'title' => 'Programação',
                'category' => 'Desenvolvimento de Software',
                'description' => 'Mergulhe no mundo da programação e aprenda a criar programas que resolvem problemas e automatizam tarefas. Neste curso, você vai aprender os conceitos básicos da programação, como variáveis, operadores, estruturas de controle e funções, usando uma linguagem de programação fácil de aprender, como Python ou JavaScript. Com a programação, você pode desenvolver jogos, aplicativos, scripts e muito mais!',
            ],[
                'title' => 'Redes de Computadores',
                'category' => 'Infraestrutura de Tecnologia da Informação',
                'description' => 'Entenda como os computadores se conectam e compartilham informações. Neste curso, você vai aprender sobre os diferentes tipos de redes, como a internet funciona, como configurar roteadores e muito mais. É uma ótima opção para quem quer entender melhor como a tecnologia funciona e como se conectar à internet de forma segura e eficiente.'
            ]
        ];

        foreach ($courses as $data){
            if (Course::where(['title' => $data['title']])->exists()){
                continue;
            }
            Course::create($data);
        }
    }
}
