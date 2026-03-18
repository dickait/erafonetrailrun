<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Konfirmasi Pendaftaran ERA Trail Run 2026</title>
</head>

<body style="margin:0; padding:0; background-color:#f5f5f5; font-family: Arial, sans-serif;">

  <table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td align="center">

        <!-- Container -->
        <table width="600" cellpadding="0" cellspacing="0" border="0"
          style="background:#ffffff; margin:20px 0; border-radius:8px; overflow:hidden; border: 1px solid #e0e0e0;">

          <!-- Header -->
          <tr>
            <td style="background:#E02534; color:#ffffff; padding:20px; text-align:center;">
              <table align="center" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                <tr>
                  <td style="vertical-align: middle; padding-right: 15px;">
                    <img src="{{ $message->embed(public_path('erafone-icon-01.png')) }}" alt="Logo" width="50" height="50"
                      style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;">
                  </td>
                  <td style="vertical-align: middle; text-align: left;">
                    <h2 style="margin:0; font-size: 24px; line-height: 1.2;">ERA TRAIL RUN 2026</h2>
                    <p style="margin:2px 0 0; font-size: 14px;">Konfirmasi Pendaftaran</p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:20px; color:#333333;">

              <p>Halo <strong>{{ $participant->full_name }}</strong>,</p>

              <p>
                Terima kasih telah mendaftar pada
                <strong>{{ $participant->event->name ?? 'ERA Trail Run 2026' }}</strong>.
                Berikut adalah detail pendaftaran dan pembayaran Anda:
              </p>

              <!-- Detail Box -->
              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse; margin-top:15px; width:100%;">

                <tr style="background:#f9f9f9;">
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Order ID</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">{{ optional($participant->latestPayment)->order_id ?? '-' }}</td>
                </tr>
                <tr>
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Nama</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">{{ $participant->full_name }}</td>
                </tr>
                <tr style="background:#f9f9f9;">
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Email</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">{{ $participant->email }}</td>
                </tr>
                <tr>
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Kategori</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">{{ $participant->category->name ?? '-' }}</td>
                </tr>
                <tr style="background:#f9f9f9;">
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Acara</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">{{ $participant->event->name ?? 'ERA Trail Run 2026' }}</td>
                </tr>
                <tr>
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Tanggal Daftar</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">{{ $participant->created_at->format('d M Y') }}</td>
                </tr>
                <tr style="background:#f9f9f9;">
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Golongan Darah</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">{{ $participant->blood_type ?? '-' }}</td>
                </tr>
                <tr>
                  <td width="40%" style="padding:10px;"><strong>Ukuran Jersey</strong></td>
                  <td style="padding:10px;">{{ $participant->jersey_size ?? '-' }}</td>
                </tr>
                <tr style="display:none; height:0px;">
                  <td colspan="2" style="height:0px; line-height:0px; padding:0;">&nbsp;</td>
                </tr>
              </table>

              <!-- Payment -->
              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:20px; border-collapse:collapse; width:100%;">
                <tr>
                  <td width="40%" style="background:#F6AE1B; color:#000; padding:10px;">
                    <strong>Biaya Pendaftaran</strong>
                  </td>
                  <td style="background:#F6AE1B; text-align:right; padding:10px;">
                    Rp {{ number_format(optional($participant->latestPayment)->amount ?? 0, 0, ',', '.') }}
                  </td>
                </tr>
                <tr>
                  <td width="40%" style="background:#495355; color:#ffffff; padding:10px;">
                    <strong>Total Pembayaran</strong>
                  </td>
                  <td style="background:#495355; color:#ffffff; text-align:right; padding:10px;">
                    <strong>Rp
                      {{ number_format(optional($participant->latestPayment)->final_amount ?? (optional($participant->latestPayment)->amount ?? 0), 0, ',', '.') }}</strong>
                  </td>
                </tr>
              </table>

              <!-- Note -->
              <p style="margin-top:20px;">
                Pastikan pembayaran sesuai nominal (termasuk 3 digit kode unik). Silakan transfer melalui QRIS pada
                lampiran email ini.
              </p>

              <div style="text-align: center; margin-top: 20px;">
                <img src="{{ $message->embed(public_path('qris.webp')) }}" alt="QRIS" width="250"
                  style="border-radius: 12px; border: 1px solid #ddd;">
              </div>

              <p>
                Informasi: Status pembayaran Anda akan diperbarui dalam waktu 1x24 jam setelah Anda melakukan konfirmasi
                pembayaran.
              </p>

              @php
                $baseAmount = optional($participant->latestPayment)->amount ?? 0;
                $finalAmount = optional($participant->latestPayment)->final_amount ?? $baseAmount;
                $orderId = optional($participant->latestPayment)->order_id ?? '-';

                $waMessage = "Halo Admin ERA Trail Run 2026,\n" .
                  "Saya ingin mengonfirmasi pembayaran atas pendaftaran saya dengan detail sebagai berikut:\n\n" .
                  "*Order ID:* " . $orderId . "\n" .
                  "*Nama:* " . $participant->full_name . "\n" .
                  "*Email:* " . $participant->email . "\n" .
                  "*Kategori:* " . ($participant->category->name ?? '-') . "\n" .
                  "*Acara:* " . ($participant->event->name ?? '-') . "\n" .
                  "*Tanggal Daftar:* " . $participant->created_at->format('d M Y') . "\n" .
                  "*Golongan Darah:* " . ($participant->blood_type ?? '-') . "\n" .
                  "*Ukuran Jersey:* " . ($participant->jersey_size ?? '-') . "\n\n" .
                  "*Biaya Pendaftaran:* Rp " . number_format($baseAmount, 0, ',', '.') . "\n" .
                  "*Total Pembayaran:* Rp " . number_format($finalAmount, 0, ',', '.') . "\n\n" .
                  "Berikut bukti pembayaran melalui QRIS yang terlampir.\n\n" .
                  "Mohon konfirmasi dan pengecekan lebih lanjut.\n" .
                  "Terima kasih.";
                $waUrl = "https://wa.me/628561310130?text=" . urlencode($waMessage);
              @endphp

              <div style="margin-top: 30px; text-align: center;">
                <a href="{{ $waUrl }}" target="_blank"
                  style="background-color: #25D366; color: #ffffff; padding: 15px 25px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">
                  Konfirmasi Pembayaran
                </a>
              </div>

              <p style="margin-top:20px;">
                Terima kasih dan sampai jumpa di garis start! 🏃‍♂️
              </p>

              <p>
                Salam,<br>
                <strong>Panitia Era Trail Run 2026</strong>
              </p>

            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#eeeeee; text-align:center; padding:15px; font-size:12px; color:#777;">
              &copy; 2026 Era Trail Run. All rights reserved.
            </td>
          </tr>

        </table>

      </td>
    </tr>
  </table>

</body>

</html>