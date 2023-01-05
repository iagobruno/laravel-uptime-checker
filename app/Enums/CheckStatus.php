<?php

namespace App\Enums;

enum CheckStatus: string
{
    case Queued = 'queued';
    case In_Progress = 'in_progress';
    case Completed = 'completed';
    case Failed = 'failed';
}
