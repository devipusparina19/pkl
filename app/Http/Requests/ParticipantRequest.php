<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pembatasan akses (admin) tetap lewat middleware/route yang sudah ada.
        return true;
    }

    /**
     * Rapikan input sebelum divalidasi.
     * - Spasi di awal/akhir dibuang.
     * - Teks kosong / hanya spasi menjadi null (agar field opsional lolos).
     * - Email huruf kecil, jenis kelamin huruf besar.
     */
    protected function prepareForValidation(): void
    {
        $fields = [
            'name', 'nik', 'nip', 'gender', 'rank', 'profession',
            'institution', 'district', 'email', 'phone',
        ];

        $clean = [];

        foreach ($fields as $field) {
            if (!$this->has($field)) {
                continue;
            }

            $value = $this->input($field);

            if (is_string($value)) {
                $value = trim($value);
                $value = $value === '' ? null : $value;
            }

            $clean[$field] = $value;
        }

        if (isset($clean['email']) && is_string($clean['email'])) {
            $clean['email'] = mb_strtolower($clean['email']);
        }

        if (isset($clean['gender']) && is_string($clean['gender'])) {
            $clean['gender'] = mb_strtoupper($clean['gender']);
        }

        $this->merge($clean);
    }

    public function rules(): array
    {
        // Saat edit, abaikan peserta yang sedang diedit pada cek unik.
        // Route parameter bisa berupa model (route model binding) atau ID.
        $participant = $this->route('participant');
        $participantId = is_object($participant)
            ? $participant->getKey()
            : $participant;

        return [
            // ---------- WAJIB ----------
            'name' => ['required', 'string', 'max:255'],

            'nik' => [
                'required',
                'string',
                'max:30',
                Rule::unique('participants', 'nik')->ignore($participantId),
            ],

            'gender' => ['required', Rule::in(['L', 'P'])],

            'institution' => ['required', 'string', 'max:255'],

            'district' => ['required', 'string', 'max:255'],

            'email' => ['required', 'string', 'email', 'max:255'],

            // ---------- OPSIONAL ----------
            'nip' => ['nullable', 'string', 'max:30'],

            'rank' => ['nullable', 'string', 'max:100'],

            'profession' => ['nullable', 'string', 'max:255'],

            'phone' => ['nullable', 'string', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            // Wajib
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.string' => 'Nama lengkap harus berupa teks.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',

            'nik.required' => 'NIK wajib diisi.',
            'nik.string' => 'NIK harus berupa teks.',
            'nik.max' => 'NIK maksimal 30 karakter.',
            'nik.unique' => 'NIK sudah terdaftar pada peserta lain.',

            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Jenis kelamin tidak valid.',

            'institution.required' => 'Instansi/Unit kerja wajib diisi.',
            'institution.string' => 'Instansi/Unit kerja harus berupa teks.',
            'institution.max' => 'Instansi/Unit kerja maksimal 255 karakter.',

            'district.required' => 'Kabupaten/Kota/Mitra/Mandiri wajib dipilih.',
            'district.string' => 'Kabupaten/Kota/Mitra/Mandiri tidak valid.',
            'district.max' => 'Kabupaten/Kota/Mitra/Mandiri maksimal 255 karakter.',

            'email.required' => 'Alamat email wajib diisi.',
            'email.string' => 'Alamat email harus berupa teks.',
            'email.email' => 'Format email tidak valid. Contoh: nama@email.com.',
            'email.max' => 'Alamat email maksimal 255 karakter.',

            // Opsional (hanya pesan jika diisi tetapi tidak sesuai)
            'nip.string' => 'NIP harus berupa teks.',
            'nip.max' => 'NIP maksimal 30 karakter.',
            'rank.string' => 'Pangkat/Golongan harus berupa teks.',
            'rank.max' => 'Pangkat/Golongan maksimal 100 karakter.',
            'profession.string' => 'Profesi harus berupa teks.',
            'profession.max' => 'Profesi maksimal 255 karakter.',
            'phone.string' => 'Nomor telepon harus berupa teks.',
            'phone.max' => 'Nomor telepon maksimal 30 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama lengkap',
            'nik' => 'NIK',
            'nip' => 'NIP',
            'gender' => 'jenis kelamin',
            'rank' => 'pangkat/golongan',
            'profession' => 'profesi',
            'institution' => 'instansi/unit kerja',
            'district' => 'kabupaten/kota',
            'email' => 'alamat email',
            'phone' => 'nomor telepon',
        ];
    }
}