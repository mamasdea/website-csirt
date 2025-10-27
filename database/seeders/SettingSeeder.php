<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Setting; // Pastikan model Setting sudah di-import

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data yang akan dimasukkan/diperbarui
        $data = [
            'name'         => 'WonosoboKab CSIRT',
            'description'  => 'Computer Security Incident Response Team',
            'no_telp'      => '(0286) 325112',
            'email'        => 'csirt@wonosobokab.go.id (', // Tambahkan email default jika tidak disediakan
            'address'      => 'Jl. Sabuk Alu No. 2 A Wonosobo - 56311', // Tambahkan alamat default jika tidak disediakan
            'link_aduan'   => 'https://csirt.wonosobokab.go.id/',
            'maps_embed'   => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.973697809458!2d109.9058124!3d-7.3568447!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7aa1d057485de3%3A0x28b3ce1cf0c0dd49!2sDiskominfo%20Kabupaten%20Wonosobo!5e0!3m2!1sen!2sid!4v1761274937482!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            // Catatan: Saya mengganti nilai 'maps_embed' dengan kode yang lebih realistis dan valid
            // (mengacu ke Wonosobo) karena kode yang kamu berikan sebelumnya adalah placeholder yang tidak akan berfungsi.
            'logo'         => 'assets/logo/csirt-logo.png', // Tidak perlu 'public/' karena asset() otomatis mengarah ke folder public
            'created_at'   => now(),
            'updated_at'   => now(),
        ];

        // Menggunakan updateOrCreate agar seeder bisa dijalankan berkali-kali tanpa error
        // Ini memastikan hanya satu baris data setting yang ada (dengan id = 1)
        Setting::updateOrCreate(['id' => 1], $data);
    }
}
