<?php

use App\Mail\ReminderMail;
use App\Models\Email;
use App\Models\Pembayaran;
use App\Models\Penghuni;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Mail;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    Log::info('Scheduler email pengingat berjalan');

    $batas_waktu = Carbon::now()->addDays(7);

    $pembayaran_sebelum_tempo = Penghuni::select('penghunis.*', 'pembayarans.*')
        ->leftJoin('pembayarans', 'penghunis.id', '=', 'pembayarans.penghuni_id')
        ->where('penghunis.status', 'Aktif')
        ->where('pembayarans.tanggal_bayar', '=', $batas_waktu->format('Y-m-d'))
        ->get();

    // $pembayaran_sebelum_tempo = Penghuni::all();

    $emails = Email::where('status', '=', 'Aktif')->get();


    foreach ($pembayaran_sebelum_tempo as $penghuni) {
        foreach ($emails as $email) {
            Log::info('Mengirim email ke: ' . $email);
            Mail::to($email)->send(new ReminderMail($penghuni));
        }
    }
})->dailyAt('01:00');

Schedule::call(function () {
    $bulanTahun = Carbon::now()->format('Y-m-01'); // YYYY-MM-01

    $penghunis = Penghuni::where('status', 'Aktif')->get();



    foreach ($penghunis as $penghuni) {

        $tanggalBayar = Carbon::parse($penghuni->tanggal_masuk);
        $tanggalBayar->addMonth(); // Menambah 1 bulan


        Pembayaran::create(
            [
                'penghuni_id' => $penghuni->id,
                'bulan_tahun' => $bulanTahun,
                'status' => 'Belum Bayar',
                'tanggal_bayar' => $tanggalBayar->format('Y-m-d H:i:s')
            ],
        );
    }

    info('Scheduler berhasil generate pembayaran untuk bulan ' . $bulanTahun);
    Log::info('Scheduler berhasil generate pembayaran untuk bulan ' . $bulanTahun);
})->monthlyOn(31, '16:00:01');


// app()->singleton(Schedule::class, function ($app) {
//     return tap(new Schedule(), function (Schedule $schedule) {
//         $schedule->call(function () {
//             $bulanTahun = Carbon::now()->format('Y-m-01'); // YYYY-MM-01

//             $penghunis = Penghuni::where('status', 'Aktif')->get();

//             foreach ($penghunis as $penghuni) {

//                 $tanggalBayar = Carbon::parse($penghuni->tanggal_bayar);
//                 $tanggalBayar->addMonth(); // Menambah 1 bulan
//                 Pembayaran::create(
//                     [
//                         'penghuni_id' => $penghuni->id,
//                         'bulan_tahun' => $bulanTahun
//                     ],
//                     [
//                         'status' => 'Belum Bayar',
//                         'tanggal_bayar' => $tanggalBayar
//                     ]
//                 );
//             }

//             info('Scheduler berhasil generate pembayaran untuk bulan ' . $bulanTahun);
//         })->monthlyOn(1, '00:00');

//         $schedule->call(function () {
//             $batas_waktu = Carbon::now()->addDays(7); // 7 hari sebelum jatuh tempo

//             $pembayaran_sebelum_tempo = Penghuni::where('status', 'Aktif')
//                 ->where('tanggal_masuk', '<=', $batas_waktu->format('Y-m-d'))
//                 ->get();


//             foreach ($pembayaran_sebelum_tempo as $penghuni) {
//                 Mail::to('juandaent@gmail.com')->send(new ReminderMail($penghuni));
//             }
//         })->everyFiveMinutes();
//     });
// });
