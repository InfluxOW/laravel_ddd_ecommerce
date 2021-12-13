<?php

namespace App\Domain\Generic\Response\Enums;

enum ResponseKey: string
{
    case LINKS = 'links';
    case META = 'meta';
    case QUERY = 'query';
    case DATA = 'data';
    case MESSAGE = 'message';
}
