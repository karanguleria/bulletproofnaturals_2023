<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;
use App\Mail\admin\ContactUs;
use App\Models\Pages;

class ContentsController extends Controller {

    public function about() {
        $title = '';
        $meta_desction = '';
        return view('contents.about', [
            'title' => $title,
            'meta_desction' => $meta_desction,
        ]);
    }

    public function contactUs() {
        $title = '';
        $meta_desction = '';
        return view('contents.contactus', [
            'title' => $title,
            'meta_desction' => $meta_desction,
        ]);
    }

    public function thankYou() {
        $title = '';
        $meta_desction = '';
        return view('contents.thank-you', [
            'title' => $title,
            'meta_desction' => $meta_desction,
        ]);
    }

    public function shippingPolicy() {
        $title = '';
        $meta_desction = '';
        $shipping_policy = Pages::where('id', '2')->first();
        return view('contents.shipping-policy', [
            'title' => $title,
            'meta_desction' => $meta_desction,
            'shipping_policy' => $shipping_policy,
        ]);
    }

    public function returnPolicy() {
        $title = '';
        $meta_desction = '';
        $return_policy = Pages::where('id', '3')->first();
        return view('contents.returns-policy', [
            'title' => $title,
            'meta_desction' => $meta_desction,
            'return_policy' => $return_policy,
        ]);
    }

    public function privacyPolicy() {
        $title = '';
        $meta_desction = '';
        $privacy_policy = Pages::where('id', '4')->first();
        return view('contents.privacy-policy', [
            'title' => $title,
            'meta_desction' => $meta_desction,
            'privacy_policy' => $privacy_policy
        ]);
    }

    public function termandConditions() {
        $title = '';
        $meta_desction = '';
        $terms_and_conditions = Pages::where('id', '5')->first();
        return view('contents.termsandconditions', [
            'title' => $title,
            'meta_desction' => $meta_desction,
            'terms_and_conditions' => $terms_and_conditions
        ]);
    }

    public function contactstore(Request $request) {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => '6LfzWNcUAAAAAIGtABcyHplZcoZKEcpxBgYv3xEh',
            'response' => request('recaptcha')
        ];
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultJson = json_decode($result);
        if ($resultJson->success = !true) {
            return back()->with('Error Catpcha');
        }
        $this->validate($request, [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'message' => ['required', 'string']
        ]);
        Contact::create($request->all());
        $contact = new Contact();
        $contact->name = request('name');
        $contact->email = request('email');
        $contact->message = request('message');
        $saved = $contact->save();
        Mail::to(env("ORDER_EMAIL", 'karan@brandsonify.com'))->send(new ContactUs($contact));
        session()->flash('success_mess', 'Message Sent Successfully');
        return redirect()->back();
    }

}
