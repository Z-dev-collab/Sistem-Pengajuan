<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $phone = preg_replace('/[^0-9]/', '', (string) $this->input('phone'));
        if (str_starts_with($phone, '62')) {
            $phone = '0'.substr($phone, 2);
        }

        $this->merge([
            'nik' => preg_replace('/[^0-9]/', '', (string) $this->input('nik')),
            'kk_number' => preg_replace('/[^0-9]/', '', (string) $this->input('kk_number')),
            'phone' => $phone,
            'rt' => str_pad((string) preg_replace('/[^0-9]/', '', (string) $this->input('rt')), 3, '0', STR_PAD_LEFT),
            'rw' => str_pad((string) preg_replace('/[^0-9]/', '', (string) $this->input('rw')), 3, '0', STR_PAD_LEFT),
        ]);
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', Rule::exists('services', 'id')->where('is_active', true)],
            'applicant_name' => ['required', 'string', 'min:3', 'max:120'],
            'nik' => ['required', 'digits:16'],
            'kk_number' => ['required', 'digits:16'],
            'phone' => ['required', 'regex:/^08[0-9]{8,12}$/'],
            'email' => ['nullable', 'email:rfc', 'max:150'],
            'address' => ['required', 'string', 'min:10', 'max:500'],
            'rt' => ['required', 'digits:3'],
            'rw' => ['required', 'digits:3'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'notification_channel' => ['required', Rule::in(['whatsapp', 'sms'])],
            'consent' => ['accepted'],
            'website' => ['prohibited'],
            'documents' => ['required', 'array'],
            'documents.ktp' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'documents.kk' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'documents.pengantar' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'documents.pendukung' => ['nullable', 'array', 'max:3'],
            'documents.pendukung.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required' => 'Silakan pilih jenis layanan.',
            'service_id.exists' => 'Layanan yang dipilih tidak tersedia.',
            'applicant_name.required' => 'Nama lengkap wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 angka.',
            'kk_number.required' => 'Nomor Kartu Keluarga wajib diisi.',
            'kk_number.digits' => 'Nomor Kartu Keluarga harus terdiri dari 16 angka.',
            'phone.required' => 'Nomor WhatsApp aktif wajib diisi.',
            'phone.regex' => 'Format nomor HP tidak valid. Contoh: 081234567890.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'rt.digits' => 'RT harus berupa angka.',
            'rw.digits' => 'RW harus berupa angka.',
            'notification_channel.required' => 'Pilih saluran notifikasi.',
            'consent.accepted' => 'Anda perlu menyetujui pernyataan kebenaran data.',
            'documents.ktp.required' => 'Unggah foto atau scan KTP.',
            'documents.kk.required' => 'Unggah foto atau scan Kartu Keluarga.',
            'documents.pengantar.required' => 'Unggah surat pengantar RT/RW.',
            'documents.*.max' => 'Ukuran setiap berkas maksimal 2 MB.',
            'documents.*.mimes' => 'Berkas harus berupa JPG, PNG, atau PDF.',
            'website.prohibited' => 'Pengajuan tidak dapat diproses.',
        ];
    }

    public function attributes(): array
    {
        return [
            'applicant_name' => 'nama lengkap',
            'kk_number' => 'nomor Kartu Keluarga',
            'phone' => 'nomor HP',
            'documents.ktp' => 'KTP',
            'documents.kk' => 'Kartu Keluarga',
            'documents.pengantar' => 'surat pengantar RT/RW',
        ];
    }
}
