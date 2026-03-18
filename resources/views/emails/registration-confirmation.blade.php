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
                    <img src="{{ $message->embed(public_path('erafone-icon-01.png')) }}" alt="Logo" width="50"
                      height="50"
                      style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;">
                  </td>
                  <td style="vertical-align: middle; padding-right: 15px;">
                    <img src="{{ $message->embed(public_path('eratrailrun-putih.webp')) }}" alt="Era Trail Run" width="50" height="50"
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

              @php
                $latestPayment = $participant->latestPayment;
                $baseAmount = optional($latestPayment)->amount ?? 0;
                $discountAmount = optional($latestPayment)->discount_amount ?? 0;
                $finalAmount = (optional($latestPayment)->final_amount !== null)
                  ? $latestPayment->final_amount
                  : max(0, $baseAmount - $discountAmount);
                $orderId = optional($latestPayment)->order_id ?? '-';
                $promoCode = optional($latestPayment ? $latestPayment->promotion : null)->code;
              @endphp

              <!-- Detail Box -->
              <table border="0" cellpadding="0" cellspacing="0" width="100%"
                style="border-collapse:collapse; margin-top:15px; width:100%;">

                <tr style="background:#f9f9f9;">
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Order ID</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">{{ $orderId }}</td>
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
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">{{ $participant->category->name ?? '-' }}
                  </td>
                </tr>
                <tr style="background:#f9f9f9;">
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Acara</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">
                    {{ $participant->event->name ?? 'ERA Trail Run 2026' }}
                  </td>
                </tr>
                <tr>
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Tanggal
                      Daftar</strong>
                  </td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">
                    {{ $participant->created_at->format('d M Y H:i:s') }} WIB
                  </td>
                </tr>
                <tr style="background:#f9f9f9;">
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Golongan
                      Darah</strong>
                  </td>
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
              <table border="0" cellpadding="0" cellspacing="0" width="100%"
                style="margin-top:20px; border-collapse:collapse; width:100%;">
                <tr>
                  <td width="40%" style="background:#F6AE1B; color:#000; padding:10px;">
                    <strong>Biaya Pendaftaran</strong>
                  </td>
                  <td style="background:#F6AE1B; text-align:right; padding:10px;">
                    Rp {{ number_format($baseAmount, 0, ',', '.') }}
                  </td>
                </tr>
                @if ($discountAmount > 0)
                  <tr>
                    <td width="40%" style="background:#F6AE1B; color:#000; padding:10px; border-top: 1px dashed #ca8a04;">
                      <strong>Diskon {{ $promoCode ? '(' . $promoCode . ')' : '' }}</strong>
                    </td>
                    <td style="background:#F6AE1B; text-align:right; padding:10px; border-top: 1px dashed #ca8a04;">
                      - Rp {{ number_format($discountAmount, 0, ',', '.') }}
                    </td>
                  </tr>
                @endif
                <tr>
                  <td width="40%" style="background:#495355; color:#ffffff; padding:10px;">
                    <strong>Total Pembayaran</strong>
                  </td>
                  <td style="background:#495355; color:#ffffff; text-align:right; padding:10px;">
                    <strong>Rp {{ number_format($finalAmount, 0, ',', '.') }}</strong>
                  </td>
                </tr>
              </table>

              @if ($finalAmount > 0)
                <!-- Note -->
                <p style="margin-top:20px;">
                  Pastikan pembayaran sesuai nominal (termasuk 3 digit kode unik). Tiga digit terakhir adalah kode unik
                  untuk verifikasi pembayaran. Silakan transfer melalui QRIS pada
                  lampiran email ini.
                </p>

                <div style="text-align: center; margin-top: 20px;">
                  <img src="{{ $message->embed(public_path('qris.webp')) }}" alt="QRIS" width="250"
                    style="border-radius: 12px; border: 1px solid #ddd;">
                </div>

                <p>
                  Informasi: Status pembayaran Anda akan diperbarui dalam waktu 1x24 jam setelah Anda melakukan
                  konfirmasi
                  pembayaran.
                </p>
              @else
                <p
                  style="margin-top:20px; border: 1px solid #22c55e; background: #f0fdf4; color: #166534; padding: 15px; border-radius: 8px; text-align: center;">
                  <strong>Pendaftaran Berhasil!</strong><br>
                  Pendaftaran Anda telah terkonfirmasi secara otomatis karena total biaya adalah Rp 0.
                </p>
              @endif

              @php
                $waMessage =
                  "Halo Admin ERA Trail Run 2026,\n" .
                  "Saya ingin mengonfirmasi pendaftaran saya dengan detail sebagai berikut:\n\n" .
                  "*Order ID:* " .
                  $orderId .
                  "\n" .
                  "*Nama:* " .
                  $participant->full_name .
                  "\n" .
                  "*Email:* " .
                  $participant->email .
                  "\n" .
                  "*Kategori:* " .
                  ($participant->category->name ?? '-') .
                  "\n" .
                  "*Acara:* " .
                  ($participant->event->name ?? '-') .
                  "\n" .
                  "*Tanggal Daftar:* " .
                  $participant->created_at->format('d M Y H:i:s') . " WIB" .
                  "\n" .
                  "*Golongan Darah:* " .
                  ($participant->blood_type ?? '-') .
                  "\n" .
                  "*Ukuran Jersey:* " .
                  ($participant->jersey_size ?? '-') .
                  "\n\n" .
                  "*Biaya Pendaftaran:* Rp " .
                  number_format($baseAmount, 0, ',', '.') .
                  "\n";

                if ($discountAmount > 0) {
                  $waMessage .= '*Diskon ' . ($promoCode ? "($promoCode)" : '') . ':* - Rp ' . number_format($discountAmount, 0, ',', '.') . "\n";
                }

                if ($finalAmount > 0) {
                  $waMessage .= '*Total Pembayaran:* Rp ' . number_format($finalAmount, 0, ',', '.') . "\n\n" . "Berikut bukti pembayaran melalui QRIS yang terlampir.\n\n";
                } else {
                  $waStatus = " (Lunas/Diskon 100%)";
                  $waMessage .= "*Total Pembayaran:* Rp 0" . $waStatus . "\n";
                }

                $waMessage .= "\nMohon konfirmasi dan pengecekan lebih lanjut.\n" . "Terima kasih.";
                $waUrl = 'https://wa.me/628561310130?text=' . urlencode($waMessage);
              @endphp

              <div style="margin-top: 30px; text-align: center;">
                <a href="{{ $waUrl }}" target="_blank"
                  style="background-color: #25D366; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">
                  <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                    <tr>
                      <td style="vertical-align: middle; padding-right: 10px;">
                        <svg width="20" height="20" fill="white" viewBox="0 0 24 24" style="display: block;">
                          <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                        </svg>
                      </td>
                      <td style="vertical-align: middle;">
                        {{ $finalAmount > 0 ? 'Konfirmasi Pembayaran' : 'Hubungi Panitia via WhatsApp' }}
                      </td>
                    </tr>
                  </table>
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