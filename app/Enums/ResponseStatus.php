<?php

namespace App\Enums;

enum ResponseStatus: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
    case VALIDATION_ERROR = 'validation_error';
    case NOT_FOUND = 'not_found';
    case UNAUTHORIZED = 'unauthorized';
    case FORBIDDEN = 'forbidden';
    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';
}
