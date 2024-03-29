<?php

namespace App\Domains\Common\Enums\Response;

enum ResponseKey: string
{
    case LINKS = 'links';
    case META = 'meta';
    case QUERY = 'query';
    case DATA = 'data';
    case MESSAGE = 'message';
}
