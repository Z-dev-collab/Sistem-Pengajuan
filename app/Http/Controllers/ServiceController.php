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
