<?php

namespace App\Enums;

enum CheckStatus: string
{
    case Started = 'started';
    case Completed = 'completed';
    case Failed = 'failed';
}
