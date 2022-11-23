<?php

namespace App\Domains\Common\Enums;

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
    case COMMON = 'common';
    case ATTRIBUTE = 'attribute';
    case LOGIN_HISTORY = 'login_history';
    case NEWS = 'news';
}
