<?php

namespace My\Baby\Enums;

enum BabyActivityTypeEnum: string
{
    case SLEEP = 'sleep';
    case WAKEUP = 'wakeup';
    case FEEDING = 'feeding';
    case DIAPER_CHANGE = 'diaper_change';
    case BATHING = 'bathing';
    case MEDICAMENT = 'medicament';
    case VACCINATION = 'vaccination';
    case TOOTH = 'tooth';
    case TEMPERATURE = 'temperature';
    case HEIGHT = 'height';
    case WEIGHT = 'weight';

    public function title(): string
    {
        return match ($this) {
            self::SLEEP => 'Spánok',
            self::WAKEUP => 'Prebudenie',
            self::FEEDING => 'Kŕmenie',
            self::DIAPER_CHANGE => 'Prebaľovanie',
            self::BATHING => 'Kúpanie',
            self::MEDICAMENT => 'Lieky',
            self::VACCINATION => 'Očkovanie',
            self::TOOTH => 'Zuby',
            self::TEMPERATURE => 'Teplota',
            self::HEIGHT => 'Vyška',
            self::WEIGHT => 'Váha',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::SLEEP => '🌜',
            self::WAKEUP => '🌞',
            self::FEEDING => '🍼',
            self::DIAPER_CHANGE => '💩',
            self::BATHING => '🛀',
            self::MEDICAMENT => '💊',
            self::VACCINATION => '💉',
            self::TOOTH => '🦷',
            self::TEMPERATURE => '🌡️',
            self::HEIGHT => '📏',
            self::WEIGHT => '⚖️',
        };
    }
}
