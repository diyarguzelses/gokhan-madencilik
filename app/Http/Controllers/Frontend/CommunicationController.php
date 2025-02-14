<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
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

        try {
            Mail::raw(
                "Gönderen: {$emailData['name']} ({$emailData['email']})\n\nMesaj:\n{$emailData['messageContent']}",
                function ($message) use ($emailData) {
                    $toAddress = env('MAIL_TO_ADDRESS');
                    if (!$toAddress) {
                        throw new \Exception("MAIL_TO_ADDRESS .env dosyasında tanımlı değil!");
                    }
                    $message->to($toAddress)
                        ->subject('Yeni İletişim Mesajı')
                        ->from($emailData['email'], $emailData['name']);
                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Mesaj başarıyla gönderildi.'
            ], 200);

        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Mesaj gönderilemedi: ' . $e->getMessage()
            ], 500);
        }
    }
}
