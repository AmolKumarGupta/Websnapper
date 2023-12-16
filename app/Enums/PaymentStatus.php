<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Incomplete     = 'incomplete';
    case Failed         = 'failed';
    case Succeeded      = 'succeeded';
}