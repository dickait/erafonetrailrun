<div
    style="font-family: 'Inter', Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #0c1410; color: #ffffff; padding: 32px;">
    <div style="text-align: center; margin-bottom: 24px;">
        <h1 style="color: #22c55e; font-size: 24px; margin: 0;">Era Trail Run 2026</h1>
    </div>
    <div
        style="background-color: #121f18; border: 1px solid #14532d; border-radius: 12px; padding: 24px; margin-bottom: 16px;">
        <h2 style="color: #22c55e; font-size: 18px; margin: 0 0 16px;">Payment Confirmed! ✅</h2>
        <p style="color: #9ca3af; margin: 0 0 16px;">Hi {{ $participant->full_name }},</p>
        <p style="color: #9ca3af; margin: 0 0 16px;">Your payment for {{ $participant->event->name }} has been
            confirmed. You're all set!</p>
        <p style="color: #9ca3af; margin: 0;">Your BIB number will be assigned soon. Please check your dashboard for
            updates.</p>
    </div>
    <p style="color: #6b7280; font-size: 12px; text-align: center; margin: 0;">© {{ date('Y') }} Erafone Trail Run</p>
</div>