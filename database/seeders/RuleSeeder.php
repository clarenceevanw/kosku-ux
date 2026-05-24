<?php

namespace Database\Seeders;

use App\Models\Rule;
use Illuminate\Database\Seeder;

/**
 * RuleSeeder — seeds the master `rules` lookup table.
 *
 * Rules are grouped by category. Boarding houses then attach
 * a subset of these via the boarding_house_rules pivot.
 */
class RuleSeeder extends Seeder
{
    /**
     * Master rule definitions.
     * icon: Material Symbols Outlined ligature name.
     */
    private array $rules = [
        // ── Tamu & Kunjungan ──────────────────────────────────────────────────
        ['category' => 'Tamu & Kunjungan', 'name' => 'Tamu dilarang menginap',          'icon' => 'no_accounts'],
        ['category' => 'Tamu & Kunjungan', 'name' => 'Tamu hanya boleh di area tamu',   'icon' => 'living'],
        ['category' => 'Tamu & Kunjungan', 'name' => 'Tamu wajib lapor ke pengelola',   'icon' => 'assignment_ind'],
        ['category' => 'Tamu & Kunjungan', 'name' => 'Jam kunjungan 08.00 – 21.00',     'icon' => 'schedule'],
        ['category' => 'Tamu & Kunjungan', 'name' => 'Tamu lawan jenis dilarang masuk', 'icon' => 'block'],

        // ── Keamanan & Akses ──────────────────────────────────────────────────
        ['category' => 'Keamanan & Akses', 'name' => 'Pintu gerbang tutup pukul 23.00', 'icon' => 'lock'],
        ['category' => 'Keamanan & Akses', 'name' => 'Wajib melapor jika keluar malam', 'icon' => 'nightlight'],
        ['category' => 'Keamanan & Akses', 'name' => 'Kunci kamar tidak boleh dipinjam', 'icon' => 'key'],
        ['category' => 'Keamanan & Akses', 'name' => 'CCTV aktif 24 jam di area umum', 'icon' => 'security'],
        ['category' => 'Keamanan & Akses', 'name' => 'Sepeda motor wajib dikunci ganda', 'icon' => 'two_wheeler'],

        // ── Kebersihan ────────────────────────────────────────────────────────
        ['category' => 'Kebersihan', 'name' => 'Kamar wajib dibersihkan sendiri',       'icon' => 'cleaning_services'],
        ['category' => 'Kebersihan', 'name' => 'Sampah dibuang setiap hari',            'icon' => 'delete'],
        ['category' => 'Kebersihan', 'name' => 'Dapur dibersihkan setelah pemakaian',   'icon' => 'kitchen'],
        ['category' => 'Kebersihan', 'name' => 'Dilarang makan di dalam kamar',         'icon' => 'no_food'],
        ['category' => 'Kebersihan', 'name' => 'Pakaian dijemur di area yang disediakan','icon' => 'dry'],

        // ── Larangan ──────────────────────────────────────────────────────────
        ['category' => 'Larangan', 'name' => 'Dilarang merokok di dalam kos',          'icon' => 'smoke_free'],
        ['category' => 'Larangan', 'name' => 'Dilarang membawa hewan peliharaan',      'icon' => 'pets'],
        ['category' => 'Larangan', 'name' => 'Dilarang membawa minuman beralkohol',    'icon' => 'no_drinks'],
        ['category' => 'Larangan', 'name' => 'Dilarang memasak di dalam kamar',        'icon' => 'outdoor_grill'],
        ['category' => 'Larangan', 'name' => 'Dilarang memindahkan furnitur',          'icon' => 'move_item'],

        // ── Pembayaran ────────────────────────────────────────────────────────
        ['category' => 'Pembayaran', 'name' => 'Pembayaran maksimal tgl 5 tiap bulan', 'icon' => 'calendar_today'],
        ['category' => 'Pembayaran', 'name' => 'Denda 5% jika terlambat bayar',        'icon' => 'money_off'],
        ['category' => 'Pembayaran', 'name' => 'Deposit dikembalikan saat keluar',     'icon' => 'savings'],
        ['category' => 'Pembayaran', 'name' => 'Kerusakan ditanggung penghuni',        'icon' => 'handyman'],

        // ── Ketertiban ────────────────────────────────────────────────────────
        ['category' => 'Ketertiban', 'name' => 'Dilarang membuat keributan malam hari','icon' => 'volume_off'],
        ['category' => 'Ketertiban', 'name' => 'Musik/speaker maksimal pukul 22.00',   'icon' => 'music_off'],
        ['category' => 'Ketertiban', 'name' => 'Wajib saling menghormati sesama penghuni','icon' => 'handshake'],
    ];

    public function run(): void
    {
        foreach ($this->rules as $rule) {
            Rule::create($rule);
        }

        $this->command->info('✓ Rules seeded: ' . count($this->rules));
    }
}
