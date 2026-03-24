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
                    <img src="{{ asset('erafone-icon-01.png') }}" alt="Logo" width="50"
                      height="50"
                      style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;">
                  </td>
                  <td style="vertical-align: middle; padding-right: 15px;">
                    <img src="{{ asset('eratrailrun-putih.png') }}" alt="Era Trail Run"
                      width="50" height="50"
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
                <strong>{{ $participant->event->name ?? 'Era Trail Run 2026' }}</strong>.
                Berikut adalah detail pendaftaran dan pembayaran Anda:
              </p>

              @php
                $latestPayment = $participant->latestPayment;
                $baseAmount = optional($latestPayment)->amount ?? 0;
                $discountAmount = optional($latestPayment)->discount_amount ?? 0;
                $feeAmount = optional($latestPayment)->fee_amount ?? 0;
                $finalAmount = (optional($latestPayment)->final_amount !== null)
                  ? $latestPayment->final_amount
                  : max(0, $baseAmount - $discountAmount);
                $totalToPay = $finalAmount + $feeAmount;
                $orderId = optional($latestPayment)->order_id ?? '-';
                $promoCode = optional($latestPayment ? $latestPayment->promotion : null)->code;
                $isFamily = $participant->familyMembers && $participant->familyMembers->count() > 0;

                // Category logic
                $categoryNameForEmail = $participant->category->name ?? '-';
                if ($participant->category && !str_contains(strtolower($categoryNameForEmail), 'family') && $participant->date_of_birth) {
                  $regYear = $participant->created_at->year;
                  $birthYear = $participant->date_of_birth->year;
                  $ageAtRegForEmail = $regYear - $birthYear;
                  if ($ageAtRegForEmail >= 40) {
                    $categoryNameForEmail .= ' (Master)';
                  } elseif ($ageAtRegForEmail >= 17) {
                    $categoryNameForEmail .= ' (Open)';
                  }
                }
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
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">{{ $categoryNameForEmail }}
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

              @if ($isFamily)
                <!-- Family Members -->
                <div style="margin-top:20px; border: 1px solid #eeeeee; border-radius: 8px; overflow: hidden;">
                  <div style="background:#f9f9f9; padding: 10px; border-bottom: 1px solid #eeeeee;">
                    <strong style="font-size: 14px; color: #333;">Anggota Keluarga (Family Members)</strong>
                  </div>
                  <table border="0" cellpadding="0" cellspacing="0" width="100%"
                    style="border-collapse:collapse; width:100%; font-size: 13px;">
                    <thead>
                      <tr style="background:#fcfcfc;">
                        <th align="left" style="padding:10px; border-bottom: 1px solid #eeeeee;">Nama</th>
                        <th align="left" style="padding:10px; border-bottom: 1px solid #eeeeee;">Golongan Darah</th>
                        <th align="left" style="padding:10px; border-bottom: 1px solid #eeeeee;">Jersey</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td style="padding:10px; border-bottom: 1px solid #eeeeee;">
                          {{ $participant->full_name }} <span
                            style="font-size: 11px; background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px;">Leader</span>
                        </td>
                        <td align="left" style="padding:10px; border-bottom: 1px solid #eeeeee;">
                          {{ $participant->blood_type ?? '-' }}
                        </td>
                        <td align="left" style="padding:10px; border-bottom: 1px solid #eeeeee;">
                          {{ $participant->jersey_size ?? '-' }}
                        </td>
                      </tr>
                      @foreach ($participant->familyMembers as $member)
                        <tr>
                          <td style="padding:10px; border-bottom: 1px solid #eeeeee;">
                            {{ $member->full_name }} <span
                              style="font-size: 11px; background: #f3f4f6; color: #4b5563; padding: 2px 6px; border-radius: 4px;">Member</span>
                          </td>
                          <td align="left" style="padding:10px; border-bottom: 1px solid #eeeeee;">
                            {{ $member->blood_type ?? '-' }}
                          </td>
                          <td align="left" style="padding:10px; border-bottom: 1px solid #eeeeee;">
                            {{ $member->jersey_size ?? '-' }}
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif

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
                @if ($feeAmount > 0)
                  <tr>
                    <td width="40%" style="background:#F6AE1B; color:#000; padding:10px; border-top: 1px dashed #ca8a04;">
                      <strong>Biaya Layanan Pembayaran (+11% PPN)</strong>
                    </td>
                    <td style="background:#F6AE1B; text-align:right; padding:10px; border-top: 1px dashed #ca8a04;">
                      + Rp {{ number_format($feeAmount, 0, ',', '.') }}
                    </td>
                  </tr>
                @endif
                <tr>
                  <td width="40%" style="background:#495355; color:#ffffff; padding:10px;">
                    <strong>Total Pembayaran</strong>
                  </td>
                  <td style="background:#495355; color:#ffffff; text-align:right; padding:10px;">
                    <strong>Rp {{ number_format($totalToPay, 0, ',', '.') }}</strong>
                  </td>
                </tr>
                @if ($feeAmount <= 0 && $finalAmount > 0)
                  <tr>
                    <td colspan="2"
                      style="text-align:right; padding:5px 10px; font-size:11px; color:#666; font-style:italic;">
                      * Belum termasuk biaya layanan pembayaran
                    </td>
                  </tr>
                @endif
              </table>

              @if ($finalAmount > 0)
                @if (optional($latestPayment)->payment_method === 'midtrans')
                  <!-- Midtrans Payment Instruction -->
                  <p style="margin-top:20px;">
                    Silakan lakukan pembayaran melalui tombol di bawah ini. Biaya di atas belum termasuk biaya layanan
                    metode pembayaran. Anda dapat memilih berbagai metode pembayaran seperti QRIS, Virtual Account, atau
                    E-Wallet.
                  </p>

                  <div style="margin-top: 25px; text-align: center;">
                    <a href="{{ route('registration.payment', ['email' => $participant->email]) }}" target="_blank"
                      style="background-color: #E02534; color: #ffffff; padding: 14px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">
                      Bayar Sekarang
                    </a>
                  </div>

                  <p style="font-size: 13px; color: #666; margin-top: 15px;">
                    *Status pembayaran Anda akan diperbarui secara otomatis setelah transaksi berhasil.
                  </p>
                @else
                  <!-- Manual (QRIS) Payment Instruction -->
                  <p style="margin-top:20px;">
                    Pastikan pembayaran sesuai nominal. Silakan transfer melalui QRIS pada lampiran email ini atau scan
                    gambar
                    di bawah:
                  </p>

                  <div style="text-align: center; margin-top: 20px;">
                    <img src="{{ asset('qris.webp') }}" alt="QRIS" width="250"
                      style="border-radius: 12px; border: 1px solid #ddd;">
                  </div>

                  <p>
                    Informasi: Status pembayaran Anda akan diperbarui dalam waktu 1x24 jam setelah Anda melakukan
                    konfirmasi pembayaran.
                  </p>
                @endif
              @else
                <p
                  style="margin-top:20px; border: 1px solid #22c55e; background: #f0fdf4; color: #166534; padding: 15px; border-radius: 8px; text-align: center;">
                  <strong>Pendaftaran Berhasil!</strong><br>
                  Pendaftaran Anda telah terkonfirmasi secara otomatis karena total biaya adalah Rp 0.
                </p>

                <p style="margin-top:30px;">
                  Selanjutnya, informasi penting terkait acara seperti <i>race pack collection</i>, <i>technical
                    meeting</i>,
                  serta update lainnya akan kami sampaikan melalui email berikutnya dan juga melalui instagram <a
                    href="https://www.instagram.com/erafonestores_bogor/" target="_blank"
                    style="color: #E02534; text-decoration: underline;">Erafone Store Bogor</a>.
                </p>

                <p>
                  Pastikan Anda terus memantau email dan media sosial kami agar tidak ketinggalan informasi terbaru.
                </p>

                <p>
                  Kami tidak sabar menyambut Anda di lintasan!
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
                  $categoryNameForEmail .
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
                  "\n\n";

                if ($participant->familyMembers && $participant->familyMembers->count() > 0) {
                  $waMessage .= "*Anggota Keluarga:*\n";
                  foreach ($participant->familyMembers as $m) {
                    $waMessage .= "- " . $m->full_name . " (" . ($m->jersey_size ?? '-') . ")\n";
                  }
                  $waMessage .= "\n";
                }

                $waMessage .= "*Biaya Pendaftaran:* Rp " . number_format($baseAmount, 0, ',', '.') . "\n";


                if ($discountAmount > 0) {
                  $waMessage .= '*Diskon ' . ($promoCode ? "($promoCode)" : '') . ':* - Rp ' . number_format($discountAmount, 0, ',', '.') . "\n";
                }

                if ($feeAmount > 0) {
                  $waMessage .= '*Biaya Layanan Pembayaran (+11% PPN):* + Rp ' . number_format($feeAmount, 0, ',', '.') . "\n";
                }

                if ($totalToPay > 0) {
                  if (optional($latestPayment)->payment_method === 'midtrans') {
                    $waMessage .= "*Total Pembayaran:* Rp " . number_format($totalToPay, 0, ',', '.') . "\n" . "Saya akan segera melakukan pembayaran.\n\n";
                  } else {
                    $waMessage .= '*Total Pembayaran:* Rp ' . number_format($totalToPay, 0, ',', '.') . "\n\n" . "Berikut bukti pembayaran melalui QRIS yang terlampir.\n\n";
                  }
                } else {
                  $waStatus = " (Lunas/Diskon 100%)";
                  $waMessage .= "*Total Pembayaran:* Rp 0" . $waStatus . "\n";
                }

                $waMessage .= "\nMohon konfirmasi dan pengecekan lebih lanjut.\n" . "Terima kasih.";
                $waUrl = 'https://wa.me/628561310130?text=' . urlencode($waMessage);
              @endphp

               <div style="margin-top: 30px; text-align: center;">
                <!--[if mso]>
                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $waUrl }}" style="height:50px;v-text-anchor:middle;width:250px;" arcsize="16%" stroke="f" fillcolor="#25D366">
                  <w:anchorlock/>
                  <center>
                <![endif]-->
                <a href="{{ $waUrl }}" target="_blank"
                  style="background-color: #25D366; color: #ffffff; padding: 14px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; min-width: 200px;">
                  {{ $finalAmount > 0 ? 'Konfirmasi Pembayaran via WhatsApp' : 'Hubungi Panitia via WhatsApp' }}
                </a>
                <!--[if mso]>
                  </center>
                </v:roundrect>
                <![endif]-->
              </div>

              <p style="margin-top:20px;">
                Terima kasih dan sampai jumpa di garis start!
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