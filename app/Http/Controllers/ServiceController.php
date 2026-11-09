<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    protected $services = [
        [
            'name' => 'Surat Keterangan Kematian',
            'slug' => 'pengurusan-kematian',
            'tagline' => 'Surat keterangan kematian resmi dari kelurahan',
            'icon' => 'file-check',
            'fee_label' => 'Gratis',
            'estimate_label' => '± 3 hari kerja',
            'sort_order' => 1,
            'description' => 'Surat keterangan kematian diterbitkan untuk warga yang meninggal.',
            'fee_note' => null,
            'form_title' => null,
            'form_fields' => [],
            'requirements' => [
                ['name' => 'Kartu Keluarga', 'is_mandatory' => true, 'note' => null],
                ['name' => 'Surat keterangan kematian dari rumah sakit/puskesmas', 'is_mandatory' => true, 'note' => null],
                ['name' => 'KTP pelapor', 'is_mandatory' => true, 'note' => null],
            ],
        ],
        [
            'name' => 'Surat Ahli Waris',
            'slug' => 'pengurusan-ahli-waris',
            'tagline' => 'Surat ahli waris untuk penerima hak waris',
            'icon' => 'users',
            'fee_label' => 'Gratis',
            'estimate_label' => '± 5 hari kerja',
            'sort_order' => 2,
            'description' => 'Surat ahli waris digunakan untuk pengurus waris.',
            'fee_note' => null,
            'form_title' => null,
            'form_fields' => [],
            'requirements' => [
                ['name' => 'Kartu Keluarga', 'is_mandatory' => true, 'note' => null],
                ['name' => 'Surat keterangan kematian', 'is_mandatory' => true, 'note' => null],
                ['name' => 'KTP ahli waris', 'is_mandatory' => true, 'note' => null],
            ],
        ],
        [
            'name' => 'Surat Nikah',
            'slug' => 'pengurusan-nikah',
            'tagline' => 'Surat nikah resmi dari kelurahan',
            'icon' => 'heart',
            'fee_label' => 'Gratis',
            'estimate_label' => '± 7 hari kerja',
            'sort_order' => 3,
            'description' => 'Surat nikah untuk pasangan yang menikah.',
            'fee_note' => null,
            'form_title' => null,
            'form_fields' => [],
            'requirements' => [
                ['name' => 'Kartu Keluarga pria dan wanita', 'is_mandatory' => true, 'note' => null],
                ['name' => 'Surat keterangan dari agama', 'is_mandatory' => true, 'note' => null],
                ['name' => 'KTP pria dan wanita', 'is_mandatory' => true, 'note' => null],
            ],
        ],
        [
            'name' => 'Surat Keterangan Domisili Yayasan / Sekretariat',
            'slug' => 'pengurusan-domisili-yayasan',
            'tagline' => 'Surat keterangan domisili untuk yayasan, lembaga, atau sekretariat organisasi.',
            'icon' => 'home',
            'fee_label' => 'Gratis',
            'estimate_label' => '± 3 hari kerja',
            'sort_order' => 4,
            'description' => 'Layanan penerbitan Surat Keterangan Domisili bagi yayasan, lembaga swadaya masyarakat, ormas, atau sekretariat organisasi di wilayah kelurahan. Digunakan untuk keperluan legalitas administrasi, perbankan, perizinan, dan operasional kelembagaan.',
            'fee_note' => null,
            'form_title' => 'Formulir Keterangan Domisili Yayasan / Sekretariat',
            'form_fields' => [
                ['label' => 'Nama yayasan / organisasi', 'type' => 'text', 'width' => 'full'],
                ['label' => 'Nomor akta notaris pendirian', 'type' => 'text', 'width' => 'half'],
                ['label' => 'Nama notaris', 'type' => 'text', 'width' => 'half'],
                ['label' => 'Nama ketua / penanggung jawab', 'type' => 'text', 'width' => 'half'],
                ['label' => 'NIK penanggung jawab', 'type' => 'text', 'width' => 'half'],
                ['label' => 'Nomor telepon / WhatsApp', 'type' => 'text', 'width' => 'half'],
                ['label' => 'Bidang kegiatan', 'type' => 'text', 'width' => 'half'],
                ['label' => 'Alamat sekretariat / kantor', 'type' => 'textarea', 'width' => 'full'],
                ['label' => 'Status bangunan / lahan', 'type' => 'text', 'width' => 'half'],
                ['label' => 'Luas bangunan / tanah', 'type' => 'text', 'width' => 'half'],
            ],
            'requirements' => [
                ['name' => 'Surat permohonan ditujukan kepada Lurah dan Camat', 'is_mandatory' => true, 'note' => 'Asli permohonan tertulis yang ditandatangani pimpinan yayasan/organisasi.'],
                ['name' => 'Surat pengantar RT/RW', 'is_mandatory' => true, 'note' => 'Asli surat pengantar dari RT dan RW setempat sesuai domisili.'],
                ['name' => 'Surat keterangan sempadan yang diketahui RT/RW & Lurah', 'is_mandatory' => true, 'note' => 'Persetujuan batas sempadan tetangga sekitar.'],
                ['name' => 'Fotokopi Akta Notaris / Pendirian (Pusat)', 'is_mandatory' => true, 'note' => 'Salinan akta pendirian yayasan/organisasi dari notaris.'],
                ['name' => 'Fotokopi SK Cabang / Penunjukan', 'is_mandatory' => false, 'note' => 'Bagi kantor cabang atau sekretariat perwakilan.'],
                ['name' => 'Fotokopi SK Kepengurusan', 'is_mandatory' => true, 'note' => 'Susunan pengurus yayasan/organisasi yang masih berlaku.'],
                ['name' => 'Legalitas lahan/tempat (Akta Kepemilikan atau Perjanjian Sewa Menyewa)', 'is_mandatory' => true, 'note' => 'Fotokopi sertifikat kepemilikan tanah/bangunan atau surat perjanjian sewa menyewa.'],
                ['name' => 'PL / Denah lokasi', 'is_mandatory' => true, 'note' => 'Peta situasi atau denah lokasi bangunan kantor/sekretariat.'],
                ['name' => 'Foto lokasi tampak depan, dalam, dan samping kanan-kiri', 'is_mandatory' => true, 'note' => 'Dokumentasi foto fisik bangunan/ruangan sekretariat dari berbagai sisi.'],
                ['name' => 'Fotokopi KTP pemohon / penanggung jawab', 'is_mandatory' => true, 'note' => 'KTP ketua yayasan atau penanggung jawab yang masih berlaku.'],
                ['name' => 'Pasfoto 3x4 (4 lembar)', 'is_mandatory' => true, 'note' => 'Foto terbaru pemohon/penanggung jawab.'],
                ['name' => 'Surat kuasa jika bukan yang bersangkutan', 'is_mandatory' => false, 'note' => 'Bermaterai, dilampirkan apabila pengurusan dikuasakan kepada orang lain.'],
            ],
        ],
    ];

    public function index()
    {
        $services = $this->services;

        usort($services, function($a, $b) {
            return $a['sort_order'] <=> $b['sort_order'];
        });

        return view('services.index', compact('services'));
    }

    public function show($slug)
    {
        $service = collect($this->services)->firstWhere('slug', $slug);

        abort_if(!$service, 404);

        return view('services.show', compact('service'));
    }

    public function downloadForm($slug)
    {
        $service = collect($this->services)->firstWhere('slug', $slug);

        abort_if(!$service, 404);

        $pdf = Pdf::loadView('pdf.formulir', [
            'service' => (object) $service,
            'site' => config('kelurahan'),
        ])->setPaper('a4');

        $filename = 'Formulir-'.Str::slug($service['form_title'] ?: $service['name']).'.pdf';

        return $pdf->download($filename);
    }
}
