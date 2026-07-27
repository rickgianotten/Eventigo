<?php

namespace App\Enums\toast;

enum ToastStatus:string
{
    case Success = 'success';
    case Info = 'info';
    case Warning = 'warning';
    case Error = 'error';
}
