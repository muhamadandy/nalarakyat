<?php

namespace Database\Seeders;

use App\Models\ArticleNews;
use App\Models\Author;
use App\Models\BannerAdvertisement;
use App\Models\Category;
use Database\Seeders\Concerns\GeneratesImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    use GeneratesImages;

    public function run(): void
    {
        Storage::disk('public')->deleteDirectory('seed');

        $categories = $this->seedCategories();
        $authors = $this->seedAuthors();
        $this->seedArticles($categories, $authors);
        $this->seedBanners();
    }

    private function seedCategories(): array
    {
        $data = [
            'Politik'    => [185, 28, 28],
            'Ekonomi'    => [29, 78, 216],
            'Olahraga'   => [21, 128, 61],
            'Teknologi'  => [109, 40, 217],
        ];

        $categories = [];

        foreach ($data as $name => $rgb) {
            $categories[$name] = Category::create([
                'name' => $name,
                'icon' => $this->makeImage('seed/categories/' . Str::slug($name) . '.png', 96, 96, substr($name, 0, 1), $rgb),
            ]);
        }

        return $categories;
    }

    private function seedAuthors(): array
    {
        $data = [
            ['Siti Nurhaliza', 'Jurnalis'],
            ['Budi Santoso', 'Editor Ekonomi'],
            ['Dewi Lestari', 'Reporter Olahraga'],
            ['Rizky Pratama', 'Penulis Teknologi'],
        ];

        $authors = [];

        foreach ($data as $i => [$name, $occupation]) {
            $initials = collect(explode(' ', $name))->map(fn ($w) => substr($w, 0, 1))->join('');

            $authors[] = Author::create([
                'name' => $name,
                'occupation' => $occupation,
                'avatar' => $this->makeImage('seed/authors/' . Str::slug($name) . '.png', 240, 240, $initials, [71 + $i * 30, 85, 105 + $i * 20]),
            ]);
        }

        return $authors;
    }

    private function seedArticles(array $categories, array $authors): void
    {
        $colors = [
            'Politik'   => [185, 28, 28],
            'Ekonomi'   => [29, 78, 216],
            'Olahraga'  => [21, 128, 61],
            'Teknologi' => [109, 40, 217],
        ];

        $titles = [
            'Politik' => [
                'Pemerintah Daerah Buka Layanan Aduan Warga Secara Daring',
                'Warga Antusias Ikuti Musyawarah Perencanaan Pembangunan Desa',
                'Transparansi Anggaran Daerah Jadi Sorotan Publik',
                'Forum Warga Bahas Rencana Tata Ruang Kota',
                'Pelayanan Administrasi Kependudukan Kini Lebih Singkat',
                'Pemilih Muda Diajak Ikut Mengawasi Pembangunan',
            ],
            'Ekonomi' => [
                'UMKM Lokal Manfaatkan Pasar Digital untuk Perluas Jangkauan',
                'Harga Kebutuhan Pokok Stabil Menjelang Akhir Pekan',
                'Koperasi Desa Kembangkan Usaha Olahan Hasil Tani',
                'Tips Mengatur Keuangan Keluarga di Tengah Kenaikan Harga',
                'Pasar Tradisional Mulai Terima Pembayaran Nontunai',
                'Pelatihan Kewirausahaan Bantu Pemuda Memulai Usaha',
            ],
            'Olahraga' => [
                'Turnamen Sepak Bola Antarkampung Berlangsung Meriah',
                'Komunitas Lari Pagi Kian Ramai Peminat',
                'Atlet Muda Daerah Siap Tampil di Kejuaraan Pelajar',
                'Bulu Tangkis Tetap Jadi Olahraga Favorit Masyarakat',
                'Lapangan Olahraga Baru Dibuka untuk Warga Sekitar',
                'Pentingnya Pemanasan Sebelum Berolahraga',
            ],
            'Teknologi' => [
                'Aplikasi Layanan Publik Permudah Warga Mengurus Dokumen',
                'Literasi Digital Dikenalkan di Sekolah Menengah',
                'Mengenali Modus Penipuan Daring yang Marak Beredar',
                'Internet Desa Perluas Akses Belajar Jarak Jauh',
                'Kecerdasan Buatan Mulai Dipakai di Ruang Redaksi',
                'Cara Aman Menyimpan Kata Sandi di Banyak Akun',
            ],
        ];

        $paragraphs = [
            'Politik' => [
                'Partisipasi warga dalam proses pengambilan keputusan dinilai semakin penting untuk menjaga kualitas pelayanan publik.',
                'Sejumlah tokoh masyarakat berharap keterbukaan informasi terus ditingkatkan agar warga dapat mengawasi program secara langsung.',
                'Dalam pertemuan tersebut, peserta menyampaikan masukan mengenai prioritas pembangunan di lingkungan masing-masing.',
                'Ke depan, forum serupa direncanakan digelar secara berkala sehingga aspirasi warga dapat terus tertampung.',
            ],
            'Ekonomi' => [
                'Pelaku usaha kecil menilai perubahan cara berjualan membuka peluang baru yang sebelumnya sulit dijangkau.',
                'Pengamat menyebut pengelolaan keuangan yang disiplin menjadi kunci bertahan ketika harga-harga bergerak naik.',
                'Beberapa pedagang mengaku omzet meningkat setelah memanfaatkan layanan pembayaran yang lebih praktis.',
                'Pelatihan dan pendampingan dinilai perlu diperluas supaya manfaatnya dirasakan lebih banyak pelaku usaha.',
            ],
            'Olahraga' => [
                'Kegiatan olahraga bersama tidak hanya menyehatkan tubuh, tetapi juga mempererat hubungan antarwarga.',
                'Panitia mengatakan antusiasme peserta melampaui perkiraan, baik dari kalangan muda maupun orang tua.',
                'Pelatih mengingatkan pentingnya latihan bertahap dan istirahat cukup agar terhindar dari cedera.',
                'Dukungan fasilitas yang memadai diharapkan melahirkan atlet-atlet berprestasi dari daerah sendiri.',
            ],
            'Teknologi' => [
                'Perkembangan teknologi digital membawa kemudahan, namun juga menuntut kewaspadaan dalam menggunakannya.',
                'Para pengajar menekankan bahwa kemampuan memilah informasi sama pentingnya dengan kemampuan mengoperasikan perangkat.',
                'Pengguna disarankan mengaktifkan verifikasi dua langkah dan tidak membagikan kode rahasia kepada siapa pun.',
                'Pemerataan akses internet dinilai menjadi fondasi agar manfaat teknologi dapat dirasakan di semua wilayah.',
            ],
        ];

        $counter = 0;

        foreach ($titles as $categoryName => $list) {
            foreach ($list as $index => $title) {
                $intro = '<p>Berikut perkembangan terbaru mengenai ' . Str::lower($title) . '.</p>';
                $body = collect($paragraphs[$categoryName])->map(fn ($p) => "<p>{$p}</p>")->join('');

                $slug = Str::slug($title);

                $article = new ArticleNews([
                    'name' => $title,
                    'slug' => $slug,
                    'thumbnail' => $this->makeImage('seed/articles/' . $slug . '.png', 1200, 675, $categoryName, $colors[$categoryName]),
                    'content' => $intro . $body,
                    'category_id' => $categories[$categoryName]->id,
                    'author_id' => $authors[$counter % count($authors)]->id,
                    'is_featured' => $index === 0 ? 'featured' : 'not_featured',
                ]);

                // Sebar tanggal ke beberapa bulan terakhir agar grafik dasbor terisi
                $article->created_at = now()->subDays($counter * 6)->subHours($counter);
                $article->updated_at = $article->created_at;
                $article->save();

                $counter++;
            }
        }
    }

    private function seedBanners(): void
    {
        $banners = [
            ['header',  900, 120, 'Iklan Header',  [180, 83, 9]],
            ['sidebar', 300, 250, 'Iklan Sidebar 1', [15, 118, 110]],
            ['sidebar', 300, 250, 'Iklan Sidebar 2', [157, 23, 77]],
            ['article', 600, 200, 'Iklan Dalam Artikel', [67, 56, 202]],
            ['footer',  900, 120, 'Iklan Footer',  [51, 65, 85]],
        ];

        foreach ($banners as $i => [$type, $w, $h, $label, $rgb]) {
            BannerAdvertisement::create([
                'link' => 'https://example.com/iklan-' . $type . '-' . ($i + 1),
                'type' => $type,
                'thumbnail' => $this->makeImage('seed/banners/' . $type . '-' . ($i + 1) . '.png', $w, $h, $label, $rgb),
                'is_active' => 'active',
            ]);
        }
    }
}
