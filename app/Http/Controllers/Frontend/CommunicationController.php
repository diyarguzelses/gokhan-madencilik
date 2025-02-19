<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\CommunicationMail;
use App\Models\Communications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CommunicationController extends Controller
{
    public function index(){
        return view('front.communication.index');
    }


    public function sendMessage(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required|max:255',
        ],
            [
                'name.required' => 'İsim zorunludur',
                'email.required' => 'E-mail zorunludur',
                'email.email' => 'Geçerli bir e-mail adresi giriniz',
                'message.required' => 'Mesaj alanı zorunludur',
                'message.max' => 'Mesaj maksimum 255 karakter olmalıdır',
            ]);

        $emailData = [
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'messageContent' => $validateData['message'],
        ];


        Mail::to(env('MAIL_TO_ADDRESS'))->send(new CommunicationMail($emailData));
        return response()->json(['status' => 'success']);
    }
}
