<?php

namespace App\Domain\Generic\Lang\Enums;

enum TranslationNamespace: string
{
    case DEFAULT = 'app';
    case ADMIN = 'admin';
    case PRODUCTS = 'products';
    case USERS = 'users';
    case ADDRESS = 'address';
}
