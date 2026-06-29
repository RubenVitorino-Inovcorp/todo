<?php

namespace App\Enums;

enum TaskPriority: string
{
    case High = 'high';
    case Medium = 'medium';
    case Low = 'low';

    public function label(): string
    {
        return match ($this) {
            TaskPriority::High => 'Alta',
            TaskPriority::Medium => 'Média',
            TaskPriority::Low => 'Baixa',
        };
    }
}
