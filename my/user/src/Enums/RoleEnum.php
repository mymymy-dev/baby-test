<?php

declare(strict_types = 1);

namespace My\User\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case PARENT = 'parent';
    case CHILD = 'child';
}