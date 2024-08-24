<?php

namespace App\Domains\CourseDomain\Enums;

enum Category : string
{
    case SoftwareDevelopment = 'Desenvolvimento de Software';
    case DataBase = 'Banco de Dados';
    case GameDevelopment = 'Desenvolvimento de Jogos';
    case ITInfrastructure = 'Infraestrutura de Tecnologia da Informação';
}
