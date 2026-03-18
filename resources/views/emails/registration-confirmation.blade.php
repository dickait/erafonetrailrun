<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
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
              <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse; margin-top:15px;">

                <tr style="background:#f9f9f9;">
                  <td width="40%"><strong>Order ID</strong></td>
                  <td>{{ optional($participant->latestPayment)->order_id ?? '-' }}</td>
                </tr>
                <tr>
                  <td><strong>Nama</strong></td>
                  <td>{{ $participant->full_name }}</td>
                </tr>
                <tr style="background:#f9f9f9;">
                  <td><strong>Email</strong></td>
                  <td>{{ $participant->email }}</td>
                </tr>
                <tr>
                  <td><strong>Kategori</strong></td>
                  <td>{{ $participant->category->name ?? '-' }}</td>
                </tr>
                <tr style="background:#f9f9f9;">
                  <td><strong>Acara</strong></td>
                  <td>{{ $participant->event->name ?? 'ERA Trail Run 2026' }}</td>
                </tr>
                <tr>
                  <td><strong>Tanggal Daftar</strong></td>
                  <td>{{ $participant->created_at->format('d M Y') }}</td>
                </tr>
                <tr style="background:#f9f9f9;">
                  <td><strong>Golongan Darah</strong></td>
                  <td>{{ $participant->blood_type ?? '-' }}</td>
                </tr>
                <tr>
                  <td><strong>Ukuran Jersey</strong></td>
                  <td>{{ $participant->jersey_size ?? '-' }}</td>
                </tr>
              </table>

              <!-- Payment -->
              <table width="100%" cellpadding="10" cellspacing="0" style="margin-top:20px; border-collapse:collapse;">
                <tr>
                  <td style="background:#F6AE1B; color:#000;">
                    <strong>Biaya Pendaftaran</strong>
                  </td>
                  <td style="background:#F6AE1B; text-align:right;">
                    Rp {{ number_format(optional($participant->latestPayment)->amount ?? 0, 0, ',', '.') }}
                  </td>
                </tr>
                <tr>
                  <td style="background:#495355; color:#ffffff;">
                    <strong>Total Pembayaran</strong>
                  </td>
                  <td style="background:#495355; color:#ffffff; text-align:right;">
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