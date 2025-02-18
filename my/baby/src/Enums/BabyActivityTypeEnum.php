<?php

namespace My\Baby\Enums;

enum BabyActivityTypeEnum: string
{
    case SLEEP = 'sleep';
    case WAKEUP = 'wakeup';
    case FEEDING = 'feeding';
    case DIAPER_CHANGE = 'diaper_change';
    case BATHING = 'bathing';
    case WEIGHT = 'weight';
    case height = 'height';
    case TOOTH = 'tooth';
    case MEDICAMENT = 'medicament';
    case TEMPERATURE = 'temperature';
}
