<?php

namespace App\Domains\Generic\Enums;

enum ServiceProviderNamespace: string
{
    case DEFAULT = 'app';
    case ADMIN = 'admin';
    case CATALOG = 'catalog';
    case USERS = 'users';
    case ADDRESS = 'address';
    case MEDIA = 'media';
    case PURCHASE = 'purchase';
    case CART = 'cart';
    case FEEDBACK = 'feedback';
    case GENERIC = 'generic';
}
