<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Communications;
use Illuminate\Http\Request;

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
                'message.max' => 'Mesaj Maximum 255 karakter olmalıdır',
            ]);

        $communication = new Communications();
        $communication->name = $request->name;
        $communication->email = $request->email;
        $communication->message = $request->message;
        $communication->save();

        return response()->json(['Success' => 'success']);
    }
}
