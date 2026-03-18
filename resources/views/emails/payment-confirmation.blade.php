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
            <td style="background:#22c55e; color:#ffffff; padding:20px; text-align:center;">
              <table align="center" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                <tr>
                  <td style="vertical-align: middle; padding-right: 15px;">
                    <img src="{{ $message->embed(public_path('erafone-icon-01.png')) }}" alt="Logo" width="50"
                      height="50"
                      style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;">
                  </td>
                  <td style="vertical-align: middle; text-align: left;">
                    <h2 style="margin:0; font-size: 24px; line-height: 1.2;">ERA TRAIL RUN 2026</h2>
                    <p style="margin:2px 0 0; font-size: 14px;">Pembayaran Terverifikasi ✅</p>
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
                Kabar gembira! Pembayaran Anda untuk
                <strong>{{ $participant->event->name ?? 'ERA Trail Run 2026' }}</strong> telah berhasil diverifikasi.
                Pendaftaran Anda kini telah resmi dikonfirmasi.
              </p>

              <!-- Detail Box -->
              <table border="0" cellpadding="0" cellspacing="0" width="100%"
                style="border-collapse:collapse; margin-top:15px; width:100%;">
                <tr style="background:#f9f9f9;">
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Order ID</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">
                    {{ optional($participant->latestPayment)->order_id ?? '-' }}</td>
                </tr>
                <tr>
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Nama</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">{{ $participant->full_name }}</td>
                </tr>
                <tr style="background:#f9f9f9;">
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Nomor BIB</strong>
                  </td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">
                    <strong style="color: #22c55e;">{{ $participant->bib_number ?? 'Akan Segera Ditetapkan' }}</strong>
                  </td>
                </tr>
                <tr>
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Kategori</strong></td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">{{ $participant->category->name ?? '-' }}
                  </td>
                </tr>
                <tr style="background:#f9f9f9;">
                  <td width="40%" style="padding:10px; border-bottom: 1px solid #eeeeee;"><strong>Waktu Bayar</strong>
                  </td>
                  <td style="padding:10px; border-bottom: 1px solid #eeeeee;">
                    {{ optional(optional($participant->latestPayment)->paid_at)->format('d M Y H:i') ?? '-' }}
                  </td>
                </tr>
              </table>

              <p style="margin-top:20px;">
                Anda dapat melihat detail pendaftaran dan mengunduh kartu peserta melalui dasbor Anda.
              </p>

              <div style="margin-top: 30px; text-align: center;">
                <a href="{{ route('login') }}" target="_blank"
                  style="background-color: #E02534; color: #ffffff; padding: 15px 25px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">
                  Lihat Dasbor Saya
                </a>
              </div>

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