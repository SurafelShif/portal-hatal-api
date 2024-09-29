<?php

namespace App\Enums;

enum HttpStatusEnum
{
    case OK;
    case ERROR;
    case CREATED;
    case INVALID;
    case MISSING;
    case CONFLICT;
    case DUPLICATE;
    case NOT_FOUND;
    case FORBIDDEN;
    case BAD_REQUEST;
    case UNAUTHORIZED;
}
