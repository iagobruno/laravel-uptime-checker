<?php

namespace App\Enums;

enum CheckResult: string
{
    case Started = 'started';
    case Completed = 'completed';
    case Failed = 'failed';
}
