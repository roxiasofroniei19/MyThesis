<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Mail;
use App\Mail\OfferMailFromPresentationPage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendMail(Request $request) {
        try {
            // Mail::send(['text'=>'mail'], $data, function($message) {
            //     $message->to('toniflorea28@gmail.com', 'Test')->subject
            //        ('Laravel Basic Testing Mail');
            //     $message->from('contact@deratdezinvest.ro','DeratDezin Vest');
            //  });
            Mail::to("deratdezinvest@gmail.com")->send(new OfferMailFromPresentationPage($request));
            error_log('Send mail');
        } catch (\Throwable $th) {
            error_log("Error at sending reminder mail for ".$th);
        }
    }
}
