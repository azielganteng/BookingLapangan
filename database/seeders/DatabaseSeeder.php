<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\JenisLapangan;
use App\Models\Lapangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Utama',
                'role' => 'admin',
                'password' => Hash::make('123456789'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin2@example.com'],
            [
                'name' => 'Admin 2',
                'role' => 'admin',
                'password' => Hash::make('123456789'),
            ]
        );

        // 2. Akun User Biasa (Hanya 1 User)
        $user1 = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Rizky Pratama',
                'role' => 'user',
                'password' => Hash::make('123456789'),
            ]
        );

        // 3. Jenis / Kategori Lapangan
        $kategori = [
            'Futsal',
            'Mini Soccer',
            'Badminton',
            'Basket',
            'Tenis Lapangan'
        ];

        $jenisMap = [];
        foreach ($kategori as $nama) {
            $jenisMap[$nama] = JenisLapangan::firstOrCreate(['nama_jenis' => $nama]);
        }

        // 4. Lapangan
        $lapanganData = [
            [
                'nama_lapangan' => 'Lapangan Futsal Vinyl A',
                'jenis_lapangan' => $jenisMap['Futsal']->id,
                'gambar_lapangan' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=800&q=80',
                'deskripsi_lapangan' => 'Lapangan futsal standar internasional dengan lantai vinyl berkualitas tinggi, pencahayaan terang LED, dan tribune penonton.',
                'harga_sewa' => 120000,
                'status' => 'Tersedia',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '23:00:00',
            ],
            [
                'nama_lapangan' => 'Mini Soccer Rumput Sintetis B',
                'jenis_lapangan' => $jenisMap['Mini Soccer']->id,
                'gambar_lapangan' => 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?auto=format&fit=crop&w=800&q=80',
                'deskripsi_lapangan' => 'Lapangan mini soccer 7 vs 7 dengan rumput sintetis premium, ruang ganti ber-AC, dan area parkir luas.',
                'harga_sewa' => 250000,
                'status' => 'Tersedia',
                'jam_buka' => '07:00:00',
                'jam_tutup' => '23:00:00',
            ],
            [
                'nama_lapangan' => 'Badminton Court 1 (Karpet Yonex)',
                'jenis_lapangan' => $jenisMap['Badminton']->id,
                'gambar_lapangan' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=800&q=80',
                'deskripsi_lapangan' => 'Karpet badminton standar PBSI anti-slip, sirkulasi udara baik, dan netting presisi.',
                'harga_sewa' => 60000,
                'status' => 'Tersedia',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '22:00:00',
            ],
            [
                'nama_lapangan' => 'Lapangan Basket Indoor Pro',
                'jenis_lapangan' => $jenisMap['Basket']->id,
                'gambar_lapangan' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=800&q=80',
                'deskripsi_lapangan' => 'Lapangan basket indoor lantai kayu parquet dengan ring hidrolik dan scoreboard digital.',
                'harga_sewa' => 150000,
                'status' => 'Tersedia',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '22:00:00',
            ],
        ];

        foreach ($lapanganData as $lap) {
            Lapangan::updateOrCreate(
                ['nama_lapangan' => $lap['nama_lapangan']],
                $lap
            );
        }

        // 5. Sample Bookings untuk Data & Grafik
        if (Booking::count() === 0) {
            $lapangans = Lapangan::all();
            $sampleBookings = [
                [
                    'lapangan_id' => $lapangans[0]->id,
                    'user_id' => $user1->id,
                    'tanggal' => Carbon::today()->format('Y-m-d'),
                    'jam_mulai' => '19:00:00',
                    'jam_selesai' => '21:00:00',
                    'status' => 'confirmed',
                    'total_harga' => $lapangans[0]->harga_sewa * 2,
                ],
                [
                    'lapangan_id' => $lapangans[1]->id,
                    'user_id' => $user1->id,
                    'tanggal' => Carbon::tomorrow()->format('Y-m-d'),
                    'jam_mulai' => '16:00:00',
                    'jam_selesai' => '18:00:00',
                    'status' => 'pending',
                    'total_harga' => $lapangans[1]->harga_sewa * 2,
                ],
                [
                    'lapangan_id' => $lapangans[2]->id,
                    'user_id' => $user1->id,
                    'tanggal' => Carbon::yesterday()->format('Y-m-d'),
                    'jam_mulai' => '09:00:00',
                    'jam_selesai' => '11:00:00',
                    'status' => 'completed',
                    'total_harga' => $lapangans[2]->harga_sewa * 2,
                ],
                [
                    'lapangan_id' => $lapangans[3]->id,
                    'user_id' => $user1->id,
                    'tanggal' => Carbon::now()->subDays(3)->format('Y-m-d'),
                    'jam_mulai' => '14:00:00',
                    'jam_selesai' => '16:00:00',
                    'status' => 'completed',
                    'total_harga' => $lapangans[3]->harga_sewa * 2,
                ],
            ];

            foreach ($sampleBookings as $b) {
                Booking::create($b);
            }
        }
    }
}
