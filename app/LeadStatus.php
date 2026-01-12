<?php

namespace App;

enum LeadStatus: string
{
    case NEW = 'new';
    case NEGOTIATION = 'negotiation';
    case WON = 'won';
    case LOST = 'lost';

    /**
     * Retorna o label traduzido do status
     */
    public function label(): string
    {
        return match($this) {
            self::NEW => 'Novo',
            self::NEGOTIATION => 'Em Negociação',
            self::WON => 'Ganho',
            self::LOST => 'Perdido',
        };
    }

    /**
     * Retorna a cor badge/tag do status
     */
    public function color(): string
    {
        return match($this) {
            self::NEW => 'blue',
            self::NEGOTIATION => 'yellow',
            self::WON => 'green',
            self::LOST => 'red',
        };
    }

    /**
     * Retorna todos os valores possíveis como array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
