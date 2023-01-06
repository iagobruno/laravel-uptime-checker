<?php

namespace App\Enums;

enum CheckStatus: string
{
    case Queued = 'queued';
    case InProgress = 'in_progress';
    case Successful = 'successful';
    case Failure = 'failure';
}
