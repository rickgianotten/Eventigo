<?php

namespace App\Actions\Order;

use App\Actions\Checkout\CreateTicketsAction;
use App\Models\Order;
use ZipStream\ZipStream;

class DownloadTicketsAction{

    public function __construct(private CreateTicketsAction $createTicketsAction){}

    public function handle(Order $order): ZipStream{
        $qrCodes = $this->createTicketsAction->handle($order);
        
        $zip = new ZipStream(
            outputName: "tickets-{$order->event->slug}.zip"
        );

        foreach ($qrCodes as $qrCode){
            $zip->addFile(
                fileName: "{$qrCode['ticket_code']}.svg",
                data: $qrCode['svg']
            );
        }

        return $zip;
    }
}