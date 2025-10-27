<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // ==============================
        // 1️⃣ SEEDER UNTUK KATEGORI
        // ==============================
        $categories = [
            ['name' => 'Berita', 'slug' => Str::slug('Berita')],
            ['name' => 'Artikel', 'slug' => Str::slug('Artikel')],
        ];

        DB::table('categories')->insert($categories);

        $categoryMap = DB::table('categories')->pluck('id', 'slug')->toArray();

        // ==============================
        // 2️⃣ SEEDER UNTUK ARTIKEL
        // ==============================
        foreach ($categoryMap as $slug => $categoryId) {
            for ($i = 1; $i <= 20; $i++) {
                $title = ucfirst($slug) . " Keamanan Informasi Website SCSIRT #" . $i;

                DB::table('articles')->insert([
                    'title'       => $title,
                    'slug'        => Str::slug($title),
                    'content'     => $faker->paragraphs(6, true),
                    'image'       => 'images/articles/' . Str::slug($title) . '.jpg',
                    'author_id'   => 1, // sesuaikan dengan user admin
                    'category_id' => $categoryId,
                    'status'      => 'published',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        // ==============================
        // 3️⃣ SEEDER UNTUK HALAMAN (PAGES)
        // ==============================
        $pages = [
            'service'   => 'Layanan Keamanan Informasi',
            'profile'   => 'Profil SCSIRT',
            'contact'   => 'Kontak Kami',
            'rfc2350'   => 'RFC 2350 – Informasi Tim SCSIRT',
            'guide'     => 'Panduan Keamanan Informasi',
            'other'     => 'Informasi Lainnya',
        ];

        foreach ($pages as $type => $title) {
            DB::table('pages')->insert([
                'title'        => $title,
                'slug'         => Str::slug($title),
                'content'      => $faker->paragraphs(8, true),
                'image'        => 'images/pages/' . $type . '.jpg',
                'page_type'    => $type,
                'is_published' => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
