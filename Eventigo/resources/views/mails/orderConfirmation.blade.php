<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        *{
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }
        h3,h4{
            font-size: 1.25rem; 
            font-weight: 700;
        }
        .text-small{
            font-size: small;
        }
        .text-darkgrey{
            color: rgb(115 115 115)
        }
        .bg-grey{
            background: rgba(231, 229, 229, 0.8);
        }
        .card{
            background: whitesmoke;
            border-radius: 0.5rem;
            margin: 2rem auto 0 auto;
            max-width: 42rem;
        }
        .hero{
            background: linear-gradient(135deg, hsl(18 95% 55%), hsl(340 80% 55%)); 
            border-radius: 0.5rem 0.5rem 0 0 ; 
            color: white;
            text-align: center;
            padding: 2.5rem;
        }
        .event-card{
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 0.6rem;
        }
        .event-card img{
            max-width: 100%; 
            aspect-ratio: 16/7; 
            object-fit:cover;
            border-radius: 0.4rem 0.4rem 0 0;
        }
        .event-detail-container{
            padding: 0.8rem 0.5rem
        }
        .ticket{
            border: 1px dashed grey;
            border-radius: 0.6rem;
            padding: 1.2rem;
            position: relative;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
        }
        .ticket-details{
            display: grid;
            padding-inline: 1rem;
            flex-grow: 1;
        }
        .ticket-totals{
            border-radius: 0.6rem;
            padding: 1.8rem 1.2rem 1.8rem 1.2rem;
        }
        .ticket-totals > div{
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .total{
            padding-top: 0.5rem;
            border-top: 0.5px solid rgba(128, 128, 128, 0.277);
            font-weight: 700;
            margin-bottom: 0;
        }
        .circle-notch-left{
        position: absolute;
        left: -0.5rem;          
        top: 50%;               
        width: 1rem;           
        height: 1rem;          
        transform: translateY(-50%); 
        border-radius: 50%; 
        background-color: whitesmoke; 
        }
        .circle-notch-right{
        position: absolute;
        right: -0.5rem;          
        top: 50%;               
        width: 1rem;           
        height: 1rem;          
        transform: translateY(-50%); 
        border-radius: 50%; 
        background-color: whitesmoke; 
        }
        .tag{
            justify-self: start;
            background-color: rgba(255, 166, 0, 0.3);
            color: hsl(18 95% 55%);
            padding-inline: 0.5rem;
            border-radius: 0.8rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .fake-qr {
            position: relative;
            width: 60px;
            height: 60px;
            background: whitesmoke;
            border: 2px solid whitesmoke;
            border-radius: 1rem
        }

        .finder {
            position: absolute;
            width: 18px;
            height: 18px;
            border: 3px solid black;
            box-sizing: border-box;
        }

        .finder::after {
            content: "";
            position: absolute;
            inset: 3px;
            background: black;
        }

        .tl { top: 4px; left: 4px; }
        .tr { top: 4px; right: 4px; }
        .bl { bottom: 4px; left: 4px; }

        .cell {
            position: absolute;
            width: 5px;
            height: 5px;
            background: black;
        }

        .c1 { top: 30px; left: 30px; }
        .c2 { top: 30px; left: 40px; }
        .c3 { top: 40px; left: 30px; }
        .c4 { top: 45px; left: 45px; }
        .c5 { top: 35px; left: 50px; }
        .c6 { top: 50px; left: 35px; }

        footer{
            margin-top: 1rem;
            border-radius: 0 0 0.5rem 0.5rem;
            padding: 2.5rem;
            text-align: center;
        }
    </style>
</head>
<body style="background: #0e111b;">
    <div class="card">
        <div class="hero">
            <h2 style="padding-bottom: 0.5rem">Your tickets are here!</h2>
            <p style="opacity: 0.8;">Thank you for your order, {{$order->user->name}}</p>
        </div>
        <div style="margin: 1rem auto 0 auto; max-width: 32rem; display: grid; gap: 1rem;">

            <div>
                <p class="text-small" style="text-align: center;">Hi {{$order->user->name}}, your order <span style="font-weight: 700; color: black;">{{$order->order_number}}</span> has been confirmed. Below you will find {{ $order->orderItems->sum(fn($item) => $item->quantity) }} tickets for the event.</p>
            </div>

            <div class="event-card">
                <div class="event-detail-container">
                    <h3 style="padding-bottom: 0.3rem">{{$order->event->title}}</h3>
                    <p class="text-small">🗓️ {{$order->event->start_date->format('d F Y')}} • {{$order->event->start_time->format('H:i')}} -  {{$order->event->end_date->format('d F Y')}} • {{$order->event->end_time->format('H:i')}}</p>
                    <div class="text-small">
                        <p>📍{{$order->event->location}}, {{$order->event->city}}</p>
                        <p>{{$order->event->street}}, {{$order->event->postal_code}} {{$order->event->city}}</p>
                    </div>
                </div>
            </div>

            <div style="display: grid; gap: 1rem;">
                <h4 class="text-darkgrey">your tickets</h4>
                <div style="display: grid; gap: 1rem;">
                    @foreach ($order->orderItems as $item)
                        <div class="ticket bg-grey">
                            <div class="circle-notch-left"></div>
                            <div class="fake-qr">
                                <div class="finder tl"></div>
                                <div class="finder tr"></div>
                                <div class="finder bl"></div>

                                <div class="cell c1"></div>
                                <div class="cell c2"></div>
                                <div class="cell c3"></div>
                                <div class="cell c4"></div>
                                <div class="cell c5"></div>
                                <div class="cell c6"></div>
                            </div>
                            <div class="ticket-details">
                                <div class="tag text-small">
                                    {{$item->ticket->type}}
                                </div> 
                                <div style="display: grid; grid-template-columns: 1fr auto; align-items: center;">
                                    <div>
                                        <h5>{{$order->user->name}}</h5>
                                        <p class="text-small">{{$item->ticket->description}}</p>
                                    </div>
                                <p style="font-weight: 700">{{ $item->ticket->price ? '$' . $item->ticket->price() : 'Free' }}</p> 
                                </div>  
                            </div>  
                    
                            <div class="circle-notch-right"></div>
                        </div>
                    @endforeach
                </div>
                <div class="ticket-totals bg-grey">
                    @foreach ($order->orderItems as $item)
                        <div class="text-small">
                            <p>{{$item->ticket->type}} x {{$item->quantity}}</p>
                            <p>${{$item->totalprice()}}</p>
                        </div>
                    @endforeach
                    <div class="total">
                        <p>Total</p>
                        <p>${{$order->totalPrice()}}</p>
                    </div>
                </div>
            </div>

            <div class="text-small text-darkgrey" >
                <p>Please keep this email in a safe place — 
                    we’ll scan the QR code on each ticket at the entrance. Questions? Email us at 
                    <a href="mailto:{{env('MAIL_SUPPORT_ADDRESS')}}" style="color: hsl(18 95% 55%); text-decoration: underline; cursor: pointer;">{{env('MAIL_SUPPORT_ADDRESS')}}</a>.
                </p>
            </div>
        </div>
        <footer class="bg-grey">
                <p style="font-weight: 700; font-size: 0.8rem">{{env('APP_NAME')}}</p>
                <p class="text-darkgrey text-small">You are receiving this email because you purchased tickets on eventigo.nl</p>
        </footer>
    </div>
</body>
</html>