<?php

namespace App\Jobs;

use App\Models\Event;
use App\Models\Participant;
use App\Mail\EventBlast;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailBlast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public \App\Models\Event $event,
        public string $subject,
        public string $body,
        public string $template = 'EventBlast',
    ) {}

    public function handle(): void
    {
        $participants = Participant::where('event_id', $this->event->id)
            ->where('payment_status', 'paid')
            ->get();

        $class = "App\\Mail\\" . $this->template;

        foreach ($participants as $participant) {
            if ($this->template == 'EventBlast') {
                Mail::to($participant->email)->send(
                    new $class($this->subject, $this->body, $participant->full_name)
                );
            } else {
                Mail::to($participant->email)->send(new $class($participant));
            }
        }
    }
}