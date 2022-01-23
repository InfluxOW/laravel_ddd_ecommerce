<?php

namespace App\Domains\Components\Generic\Enums\Lang;

enum TranslationNamespace: string
{
    case DEFAULT = 'app';
    case ADMIN = 'admin';
    case CATALOG = 'catalog';
    case USERS = 'users';
    case ADDRESS = 'address';
    case CART = 'cart';
    case FEEDBACK = 'feedback';
}
