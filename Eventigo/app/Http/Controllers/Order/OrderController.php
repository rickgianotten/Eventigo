<?php

namespace App\Http\Controllers\Order;

use App\Actions\Order\DownloadTicketsAction;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    public function download(Order $order, DownloadTicketsAction $action){
        $zip = $action->handle($order);

        $zip->finish();
    }
}
