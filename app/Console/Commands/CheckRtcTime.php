<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckRtcTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:rtc-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if the system RTC time is synchronized with the server time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Mendapatkan waktu dari RTC (CMOS)
        $rtcTime = $this->getRtcTime();
        
        // Mendapatkan waktu server
        $serverTime = now();
        // dd($serverTime->timestamp, $rtcTime);
        
        // Menghitung selisih waktu dalam detik
        $timeDifference = abs($serverTime->timestamp - $rtcTime);

        // Validasi apakah perbedaan waktu terlalu besar (misalnya lebih dari 5 menit)
        if ($timeDifference > 300) {
            $this->error('Waktu CMOS tidak sinkron dengan waktu server, proses dihentikan.');
            return 1; // Gagal
        }

        $this->info('Waktu CMOS dan server sinkron.');
        return 0; // Berhasil
    }

    // Fungsi untuk membaca waktu CMOS
    private function getRtcTime()
    {
        // Menjalankan perintah w32tm untuk mendapatkan status sinkronisasi waktu
        shell_exec('runas /user:Administrator "w32tm /resync /force"');
        $output = shell_exec('w32tm /query /status');

        // Memeriksa apakah status sinkronisasi waktu adalah 'not synchronized'
        if (strpos($output, 'Leap Indicator: 3') !== false || strpos($output, 'not synchronized') !== false) {
            // Jika waktu tidak disinkronkan, tampilkan pesan error
            return response()->json(['error' => 'Waktu sistem tidak disinkronkan dengan server NTP. Tidak dapat melanjutkan operasi.'], 400);
        }
        
        // Menggunakan explode untuk memisahkan berdasarkan 'Last Successful Sync Time:'
        $explode1 = explode('Last Successful Sync Time:', $output);
        
        // Jika bagian setelah 'Last Successful Sync Time:' ada, lanjutkan
        if (isset($explode1[1])) {
            // Mengambil bagian sebelum 'Source:', jika ada
            $dateTimeString = trim($explode1[1]);
    
            // Jika ada kata "Source:" dalam string tersebut, kita ambil hanya bagian sebelum "Source:"
            if (strpos($dateTimeString, 'Source:') !== false) {
                $dateTimeString = explode('Source:', $dateTimeString)[0];
            }
    
            // Memisahkan dengan spasi untuk mengambil tanggal dan waktu
            $explode2 = explode(' ', $dateTimeString);
    
            // Menggabungkan tanggal dan waktu menjadi satu string
            if (isset($explode2[0]) && isset($explode2[1])) {
                // Gabungkan tanggal dan waktu
                $dateTime = trim($explode2[0] . ' ' . $explode2[1]);
                $dt = $this->convertToTimestamp($dateTime);
                return $dt; // Mengembalikan timestamp
            }
        }

        // Mengembalikan waktu sinkronisasi yang terakhir jika ada
        return response()->json(['error' => 'Tidak dapat menemukan waktu sinkronisasi terakhir.'], 400);
    }

    private function convertToTimestamp($dateString)
    {
        // Menggunakan format untuk parse tanggal dan waktu
        try {
            if (strpos($dateString, '.') !== false) {
                $dateString = str_replace('.', ':', $dateString);
            }
            $carbonDate = Carbon::createFromFormat('d/m/Y H:i:s', $dateString);

            // Mengembalikan timestamp
            return $carbonDate->timestamp;
        } catch (\Exception $e) {
            // Menangani error jika formatnya tidak valid
            return response()->json(['error' => 'Format tanggal dan waktu tidak valid.'], 400);
        }
    }
}
