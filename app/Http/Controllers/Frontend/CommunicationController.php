<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CommunicationController extends Controller
{
    public function index()
    {
        return view('front.communication.index');
    }

    public function sendMessage(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required|max:255',
        ], [
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

        // E-posta ayarlarını direkt burada tanımlıyoruz
        $smtpConfig = [
            'to_address' => 'cetinsaat.23@gmail.com',
            'from_address' => 'cetinsaat.23@gmail.com',
            'from_name' => 'Çetin İnşaat',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'smtp_encryption' => 'tls',
            'smtp_username' => 'cetinsaat.23@gmail.com',
            'smtp_password' => 'faevkodofqqsoqsg',
        ];

        try {
            Mail::raw(
                "Gönderen: {$emailData['name']} ({$emailData['email']})\n\nMesaj:\n{$emailData['messageContent']}",
                function ($message) use ($emailData, $smtpConfig) {
                    $message->to($smtpConfig['to_address'])
                        ->subject('Yeni İletişim Mesajı')
                        ->from($smtpConfig['from_address'], $smtpConfig['from_name'])
                        ->replyTo($emailData['email'], $emailData['name']); // Kullanıcının mailini yanıt olarak belirtiyoruz
                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Mesaj başarıyla gönderildi.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mesaj gönderilemedi: ' . $e->getMessage()
            ], 500);
        }
    }
}
