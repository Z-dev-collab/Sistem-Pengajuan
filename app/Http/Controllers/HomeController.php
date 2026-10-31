<?php

namespace App\Http\Controllers;

use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $services = [
            [
                'name' => 'Surat Keterangan Kematian',
                'slug' => 'pengurusan-kematian',
                'tagline' => 'Surat keterangan kematian resmi dari kelurahan',
                'icon' => 'file-check',
                'fee_label' => 'Gratis',
                'estimate_label' => '± 3 hari kerja',
                'sort_order' => 1,
            ],
            [
                'name' => 'Surat Ahli Waris',
                'slug' => 'pengurusan-ahli-waris',
                'tagline' => 'Surat ahli waris untuk penerima hak waris',
                'icon' => 'users',
                'fee_label' => 'Gratis',
                'estimate_label' => '± 5 hari kerja',
                'sort_order' => 2,
            ],
            [
                'name' => 'Surat Nikah',
                'slug' => 'pengurusan-nikah',
                'tagline' => 'Surat nikah resmi dari kelurahan',
                'icon' => 'heart',
                'fee_label' => 'Gratis',
                'estimate_label' => '± 7 hari kerja',
                'sort_order' => 3,
            ],
        ];

        usort($services, function($a, $b) {
            return $a['sort_order'] <=> $b['sort_order'];
        });

        return view('home', compact('services'));
    }
}
