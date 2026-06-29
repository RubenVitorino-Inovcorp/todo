<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            TaskStatus::Pending => 'Pendente',
            TaskStatus::Completed => 'Concluída',
        };
    }
}
