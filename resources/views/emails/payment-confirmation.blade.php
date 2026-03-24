<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Pembayaran Terverifikasi - Era Trail Run 2026</title>
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
                    <p style="margin:2px 0 0; font-size: 14px;">Pembayaran Terverifikasi</p>
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
                <strong>Kabar baik!</strong><br>
                Pembayaran Anda untuk <strong>ERA TRAIL RUN 2026</strong> telah berhasil kami verifikasi, dan
                pendaftaran Anda kini telah resmi dikonfirmasi sebagai peserta. Berikut detail transaksi Anda:
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
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Nomor BIB</strong>
                  </td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">
                    <strong style="color: #22c55e;">{{ $participant->bib_number ?? 'Akan Segera Ditetapkan' }}</strong>
                  </td>
                </tr>
                <tr style="background:#f9f9f9;">
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Kategori</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">{{ $categoryNameForEmail }}
                  </td>
                </tr>
                <tr>
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Acara</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">
                    {{ $participant->event->name ?? 'Era Trail Run 2026' }}
                  </td>
                </tr>
                <tr style="background:#f9f9f9;">
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Tanggal
                      Daftar</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">
                    {{ $participant->created_at->format('d M Y H:i:s') }} WIB
                  </td>
                </tr>
                <tr>
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Waktu Bayar</strong>
                  </td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">
                    {{ optional(optional($latestPayment)->paid_at)->format('d M Y H:i:s') ?? '-' }} WIB
                  </td>
                </tr>
                <tr style="background:#f9f9f9;">
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Golongan
                      Darah</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">{{ $participant->blood_type ?? '-' }}</td>
                </tr>
                <tr>
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Ukuran Jersey</strong>
                  </td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">{{ $participant->jersey_size ?? '-' }}
                  </td>
                </tr>
                <tr style="background:#f0fdf4;">
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #22c55e;"><strong>Status
                      Pembayaran</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #22c55e; color: #166534;"><strong>LUNAS</strong>
                  </td>
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

              <!-- Payment Summary -->
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
                @if (optional($latestPayment)->fee_amount > 0)
                  <tr>
                    <td width="40%" style="background:#F6AE1B; color:#000; padding:10px; border-top: 1px dashed #ca8a04;">
                      <strong>Biaya Layanan Pembayaran (+11% PPN)</strong>
                    </td>
                    <td style="background:#F6AE1B; text-align:right; padding:10px; border-top: 1px dashed #ca8a04;">
                      + Rp {{ number_format($latestPayment->fee_amount, 0, ',', '.') }}
                    </td>
                  </tr>
                @endif
                <tr>
                  <td width="40%" style="background:#495355; color:#ffffff; padding:10px;">
                    <strong>Total Pembayaran</strong>
                  </td>
                  <td style="background:#495355; color:#ffffff; text-align:right; padding:10px;">
                    <strong>Rp
                      {{ number_format($finalAmount + (optional($latestPayment)->fee_amount ?? 0), 0, ',', '.') }}</strong>
                  </td>
                </tr>
              </table>

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
                Terima kasih atas partisipasi Anda.<br>
                Kami tidak sabar menyambut Anda di lintasan!
              </p>

              <p style="margin-top:30px;">
                Sampai jumpa di garis start! 🏃‍♂️
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