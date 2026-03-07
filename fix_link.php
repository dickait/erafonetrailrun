<?php
$p = \App\Models\Participant::where('email', 'dickaar@gmail.com')->first();
if ($p && $p->latestPayment && $p->latestPayment->payment_link === '#') {
    $amount = $p->latestPayment->amount;
    $mobile = str_pad($p->phone, 10, '0', STR_PAD_RIGHT);
    $r = Illuminate\Support\Facades\Http::withToken(config('services.mayar.api_key'))->post(config('services.mayar.api_url') . '/payment/create', [
        'name' => $p->full_name,
        'email' => $p->email,
        'amount' => (int) $amount,
        'mobile' => $mobile,
        'description' => "Registration " . $p->event->name . " - " . $p->category->name,
        'redirectUrl' => route('registration.payment', ['email' => $p->email])
    ]);
    if ($r->successful()) {
        $p->latestPayment->update(['payment_link' => $r->json()['data']['link'], 'invoice_id' => $r->json()['data']['id']]);
        echo "Fixed Mayar link!\n";
    } else {
        echo "Failed to fix: " . $r->body() . "\n";
    }
} else {
    echo "No broken participant found.\n";
}
