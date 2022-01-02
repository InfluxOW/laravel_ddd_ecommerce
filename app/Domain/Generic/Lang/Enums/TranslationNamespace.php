<?php

namespace App\Domain\Generic\Lang\Enums;

enum TranslationNamespace: string
{
    case DEFAULT = 'app';
    case ADMIN = 'admin';
    case CATALOG = 'catalog';
    case USERS = 'users';
    case ADDRESS = 'address';
}
