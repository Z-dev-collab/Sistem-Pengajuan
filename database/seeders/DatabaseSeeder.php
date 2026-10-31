<?php

namespace Database\Seeders;

use App\Enums\SubmissionStatus;
use App\Models\Service;
use App\Models\Submission;
use App\Models\SubmissionStatusHistory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@batuselicin.go.id'],
            [
                'name' => 'Admin Kelurahan',
                'password' => Hash::make('Admin12345!'),
                'role' => 'admin',
                'phone' => '081234567890',
            ]
        );

        User::updateOrCreate(
            ['email' => 'petugas@batuselicin.go.id'],
            [
                'name' => 'Petugas Pelayanan',
                'password' => Hash::make('Petugas12345!'),
                'role' => 'staff',
                'phone' => '081234567891',
            ]
        );

        $this->seedServices();
        $this->seedDemoSubmissions();
    }

    private function seedServices(): void
    {
        $services = [
            [
                'name' => 'Pengurusan Nikah',
                'slug' => 'pengurusan-nikah',
                'tagline' => 'Surat pengantar nikah dan kelengkapan menuju KUA.',
                'description' => 'Layanan pembuatan surat pengantar nikah (N1–N4) bagi warga Kelurahan Batu Selicin yang akan melangsungkan pernikahan. Petugas membantu memeriksa kelengkapan berkas sebelum diteruskan ke KUA.',
                'icon' => 'rings',
                'keywords' => 'nikah, pernikahan, surat nikah, pengantar nikah, N1, N2, N3, N4, KUA, menikah',
                'fee' => 0,
                'fee_note' => 'Tidak dipungut biaya. Biaya pencatatan di KUA mengikuti ketentuan yang berlaku.',
                'estimated_days' => 3,
                'form_title' => 'Formulir Pengantar Nikah',
                'form_fields' => [
                    ['label' => 'Nama lengkap pemohon', 'type' => 'text', 'width' => 'full'],
                    ['label' => 'NIK', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Tempat / tanggal lahir', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Agama', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Pekerjaan', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Alamat lengkap', 'type' => 'textarea', 'width' => 'full'],
                    ['label' => 'Nama calon pasangan', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'NIK calon pasangan', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Rencana tanggal akad / resepsi', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Nama wali nikah', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Nama ayah pemohon', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Nama ibu pemohon', 'type' => 'text', 'width' => 'half'],
                ],
                'is_active' => true,
                'sort_order' => 1,
                'requirements' => [
                    ['name' => 'Surat pengantar RT/RW', 'note' => 'Asli, ditandatangani ketua RT dan RW.', 'is_mandatory' => true],
                    ['name' => 'Fotokopi KTP pemohon dan calon pasangan', 'note' => 'Pastikan data terbaca jelas.', 'is_mandatory' => true],
                    ['name' => 'Fotokopi Kartu Keluarga', 'note' => null, 'is_mandatory' => true],
                    ['name' => 'Fotokopi akta kelahiran', 'note' => null, 'is_mandatory' => true],
                    ['name' => 'Pas foto 2x3 dan 4x6', 'note' => 'Masing-masing 4 lembar, latar belakang biru.', 'is_mandatory' => true],
                    ['name' => 'Fotokopi akta cerai atau kematian', 'note' => 'Bagi pemohon berstatus cerai hidup/mati.', 'is_mandatory' => false],
                    ['name' => 'Surat izin orang tua', 'note' => 'Bagi calon pengantin yang berusia di bawah 21 tahun.', 'is_mandatory' => false],
                    ['name' => 'Surat keterangan sehat', 'note' => 'Dari puskesmas atau fasilitas kesehatan.', 'is_mandatory' => false],
                ],
            ],
            [
                'name' => 'Pengurusan Ahli Waris',
                'slug' => 'pengurusan-ahli-waris',
                'tagline' => 'Surat keterangan ahli waris untuk keperluan administrasi.',
                'description' => 'Layanan penerbitan surat keterangan ahli waris berdasarkan keterangan keluarga dan dokumen pendukung. Dibutuhkan untuk pengurusan warisan, perbankan, pertanahan, dan administrasi lainnya.',
                'icon' => 'users',
                'keywords' => 'ahli waris, warisan, surat waris, keterangan ahli waris, pewaris, pembagian waris, bank, tanah',
                'fee' => 0,
                'fee_note' => 'Tidak dipungut biaya. Materai sesuai kebutuhan dokumen.',
                'estimated_days' => 2,
                'form_title' => 'Formulir Keterangan Ahli Waris',
                'form_fields' => [
                    ['label' => 'Nama lengkap pemohon', 'type' => 'text', 'width' => 'full'],
                    ['label' => 'NIK pemohon', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Hubungan dengan pewaris', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Nama lengkap pewaris', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'NIK pewaris', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Tanggal meninggal pewaris', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Alamat terakhir pewaris', 'type' => 'textarea', 'width' => 'full'],
                    ['label' => 'Daftar ahli waris (nama, hubungan, NIK)', 'type' => 'textarea', 'width' => 'full'],
                    ['label' => 'Nama saksi 1', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Nama saksi 2', 'type' => 'text', 'width' => 'half'],
                ],
                'is_active' => true,
                'sort_order' => 2,
                'requirements' => [
                    ['name' => 'Surat pengantar RT/RW', 'note' => null, 'is_mandatory' => true],
                    ['name' => 'Fotokopi KTP pewaris', 'note' => 'Jika masih tersedia.', 'is_mandatory' => true],
                    ['name' => 'Fotokopi KTP seluruh ahli waris', 'note' => null, 'is_mandatory' => true],
                    ['name' => 'Fotokopi Kartu Keluarga', 'note' => null, 'is_mandatory' => true],
                    ['name' => 'Surat keterangan kematian pewaris', 'note' => 'Dari rumah sakit, puskesmas, atau kelurahan.', 'is_mandatory' => true],
                    ['name' => 'Fotokopi akta nikah pewaris', 'note' => null, 'is_mandatory' => true],
                    ['name' => 'Fotokopi akta kelahiran ahli waris', 'note' => null, 'is_mandatory' => true],
                    ['name' => 'Surat pernyataan ahli waris', 'note' => 'Bermaterai, ditandatangani seluruh ahli waris.', 'is_mandatory' => true],
                    ['name' => 'Fotokopi KTP dua orang saksi', 'note' => null, 'is_mandatory' => true],
                ],
            ],
            [
                'name' => 'Pengurusan Kematian',
                'slug' => 'pengurusan-kematian',
                'tagline' => 'Surat keterangan kematian dengan proses cepat.',
                'description' => 'Layanan penerbitan surat keterangan kematian untuk keperluan penguburan, administrasi warisan, asuransi, dan pengurusan dokumen kependudukan. Proses diprioritaskan agar keluarga dapat segera mengurus keperluan.',
                'icon' => 'document',
                'keywords' => 'kematian, surat kematian, meninggal, wafat, keterangan kematian, pemakaman, kubur, akta kematian',
                'fee' => 0,
                'fee_note' => 'Tidak dipungut biaya.',
                'estimated_days' => 1,
                'form_title' => 'Formulir Keterangan Kematian',
                'form_fields' => [
                    ['label' => 'Nama lengkap almarhum/almarhumah', 'type' => 'text', 'width' => 'full'],
                    ['label' => 'NIK', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Tempat / tanggal lahir', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Tanggal meninggal', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Pukul', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Tempat meninggal', 'type' => 'text', 'width' => 'full'],
                    ['label' => 'Sebab meninggal', 'type' => 'text', 'width' => 'full'],
                    ['label' => 'Alamat terakhir', 'type' => 'textarea', 'width' => 'full'],
                    ['label' => 'Nama pelapor', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Hubungan dengan almarhum', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Nomor telepon pelapor', 'type' => 'text', 'width' => 'half'],
                    ['label' => 'Tempat pemakaman', 'type' => 'text', 'width' => 'half'],
                ],
                'is_active' => true,
                'sort_order' => 3,
                'requirements' => [
                    ['name' => 'Surat pengantar RT/RW', 'note' => null, 'is_mandatory' => true],
                    ['name' => 'Fotokopi KTP almarhum/almarhumah', 'note' => null, 'is_mandatory' => true],
                    ['name' => 'Fotokopi Kartu Keluarga', 'note' => null, 'is_mandatory' => true],
                    ['name' => 'Surat keterangan kematian', 'note' => 'Dari rumah sakit, puskesmas, atau bidan.', 'is_mandatory' => true],
                    ['name' => 'Fotokopi KTP pelapor', 'note' => null, 'is_mandatory' => true],
                    ['name' => 'Fotokopi akta nikah', 'note' => 'Jika almarhum/almarhumah sudah menikah.', 'is_mandatory' => false],
                ],
            ],
        ];

        foreach ($services as $serviceData) {
            $requirements = $serviceData['requirements'];
            unset($serviceData['requirements']);

            $service = Service::updateOrCreate(['slug' => $serviceData['slug']], $serviceData);

            if ($service->requirements()->doesntExist()) {
                foreach ($requirements as $index => $requirement) {
                    $service->requirements()->create($requirement + [
                        'sort_order' => $index + 1,
                    ]);
                }
            }
        }
    }

    private function seedDemoSubmissions(): void
    {
        $nikah = Service::where('slug', 'pengurusan-nikah')->first();
        $waris = Service::where('slug', 'pengurusan-ahli-waris')->first();
        $kematian = Service::where('slug', 'pengurusan-kematian')->first();

        $demo = [
            [
                'tracking_code' => 'BS-20261015-DEMO1',
                'service_id' => $kematian?->id,
                'applicant_name' => 'Siti Aminah',
                'nik' => '1671015502890001',
                'kk_number' => '1671010101010001',
                'phone' => '081234567892',
                'email' => 'siti@example.com',
                'address' => 'Jl. Yos Sudarso No. 21',
                'rt' => '001',
                'rw' => '002',
                'notes' => 'Dibutuhkan untuk pengurusan asuransi.',
                'status' => SubmissionStatus::Completed,
                'admin_notes' => null,
                'verified_at' => now()->subDays(3),
                'completed_at' => now()->subDays(2),
                'created_at' => now()->subDays(4),
                'histories' => [
                    ['status' => SubmissionStatus::Submitted, 'title' => 'Pengajuan diterima', 'note' => 'Berkas berhasil diunggah.', 'created_at' => now()->subDays(4)],
                    ['status' => SubmissionStatus::Verified, 'title' => 'Berkas diverifikasi', 'note' => 'Seluruh berkas dinyatakan lengkap.', 'created_at' => now()->subDays(3)],
                    ['status' => SubmissionStatus::Completed, 'title' => 'Pengajuan selesai', 'note' => 'Dokumen siap diambil di kantor kelurahan.', 'created_at' => now()->subDays(2)],
                ],
            ],
            [
                'tracking_code' => 'BS-20261019-DEMO2',
                'service_id' => $nikah?->id,
                'applicant_name' => 'Rizky Pratama',
                'nik' => '1671011203950002',
                'kk_number' => '1671010101010002',
                'phone' => '081234567893',
                'email' => null,
                'address' => 'Jl. Garuda No. 8',
                'rt' => '003',
                'rw' => '001',
                'notes' => null,
                'status' => SubmissionStatus::Verified,
                'admin_notes' => null,
                'verified_at' => now()->subDay(),
                'completed_at' => null,
                'created_at' => now()->subDays(2),
                'histories' => [
                    ['status' => SubmissionStatus::Submitted, 'title' => 'Pengajuan diterima', 'note' => 'Berkas berhasil diunggah.', 'created_at' => now()->subDays(2)],
                    ['status' => SubmissionStatus::Verified, 'title' => 'Berkas diverifikasi', 'note' => 'Berkas lengkap, pengajuan sedang diproses.', 'created_at' => now()->subDay()],
                ],
            ],
            [
                'tracking_code' => 'BS-20261020-DEMO3',
                'service_id' => $waris?->id,
                'applicant_name' => 'Hendra Gunawan',
                'nik' => '1671010807880003',
                'kk_number' => '1671010101010003',
                'phone' => '081234567894',
                'email' => 'hendra@example.com',
                'address' => 'Jl. Merdeka No. 45',
                'rt' => '004',
                'rw' => '003',
                'notes' => 'Untuk keperluan pembagian sertifikat tanah.',
                'status' => SubmissionStatus::NeedsRevision,
                'admin_notes' => 'Fotokopi akta kelahiran salah satu ahli waris belum terbaca jelas. Mohon unggah ulang dengan foto yang lebih terang.',
                'verified_at' => null,
                'completed_at' => null,
                'created_at' => now()->subHours(6),
                'histories' => [
                    ['status' => SubmissionStatus::Submitted, 'title' => 'Pengajuan diterima', 'note' => 'Berkas berhasil diunggah.', 'created_at' => now()->subHours(6)],
                    ['status' => SubmissionStatus::NeedsRevision, 'title' => 'Berkas perlu diperbaiki', 'note' => 'Fotokopi akta kelahiran salah satu ahli waris belum terbaca jelas.', 'created_at' => now()->subHours(2)],
                ],
            ],
        ];

        foreach ($demo as $data) {
            $histories = $data['histories'];
            unset($data['histories']);

            $submission = Submission::updateOrCreate(
                ['tracking_code' => $data['tracking_code']],
                $data + ['notification_channel' => 'whatsapp']
            );

            if ($submission->histories()->doesntExist()) {
                foreach ($histories as $history) {
                    $submission->histories()->create($history);
                }
            }
        }
    }

}
