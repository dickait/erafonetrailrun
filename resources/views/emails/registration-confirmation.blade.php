<div
    style="font-family: 'Inter', Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #0c1410; color: #ffffff; padding: 32px;">
    <div style="text-align: center; margin-bottom: 24px;">
        <h1 style="color: #22c55e; font-size: 24px; margin: 0;">Era Trail Run 2026</h1>
    </div>
    <div
        style="background-color: #121f18; border: 1px solid #14532d; border-radius: 12px; padding: 24px; margin-bottom: 16px;">
        <h2 style="color: #22c55e; font-size: 18px; margin: 0 0 16px;">Registration Confirmed!</h2>
        <p style="color: #9ca3af; margin: 0 0 16px;">Hi {{ $participant->full_name }},</p>
        <p style="color: #9ca3af; margin: 0 0 16px;">Thank you for registering for {{ $participant->event->name }}. Here
            are your registration details:</p>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="color: #6b7280; padding: 8px 0; font-size: 14px;">Category</td>
                <td style="color: #ffffff; padding: 8px 0; font-size: 14px; text-align: right;">
                    {{ $participant->category->name ?? '-' }}
                </td>
            </tr>
            <tr>
                <td style="color: #6b7280; padding: 8px 0; font-size: 14px;">Email</td>
                <td style="color: #ffffff; padding: 8px 0; font-size: 14px; text-align: right;">
                    {{ $participant->email }}
                </td>
            </tr>
            <tr>
                <td style="color: #6b7280; padding: 8px 0; font-size: 14px;">Status</td>
                <td style="color: #fbbf24; padding: 8px 0; font-size: 14px; text-align: right;">
                    {{ ucfirst($participant->payment_status) }}
                </td>
            </tr>
        </table>
    </div>
    <p style="color: #6b7280; font-size: 12px; text-align: center; margin: 0;">© {{ date('Y') }} Era Trail Run</p>
</div>