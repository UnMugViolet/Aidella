<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller
{
    private const CONTACT_FORM_RULE = "required|string";

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'nom' => self::CONTACT_FORM_RULE,
            'prenom' => self::CONTACT_FORM_RULE,
            'email' => 'required|email',
            'message' => self::CONTACT_FORM_RULE,
        ]);

        Mail::to(env('MAIL_USERNAME', 'aidella@elevage-canin-vosges.fr'))->send(new ContactFormMail($validated));

        return response()->json(['message' => 'Message envoyé avec succès !']);
    }
}
