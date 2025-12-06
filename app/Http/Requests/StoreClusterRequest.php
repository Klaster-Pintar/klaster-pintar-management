<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreClusterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Step 1: Informasi Dasar Cluster (WAJIB)
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'radius_checkin' => 'nullable|integer|min:1|max:100',
            'radius_patrol' => 'nullable|integer|min:1|max:100',
            
            // Step 2: Offices (WAJIB - minimal 1)
            'offices' => 'required|array|min:1',
            'offices.*.name' => 'required|string|max:255',
            'offices.*.type_id' => 'required|integer',
            'offices.*.latitude' => 'required|numeric|between:-90,90',
            'offices.*.longitude' => 'required|numeric|between:-180,180',
            
            // Step 3: Patrols (OPSIONAL)
            'patrols' => 'nullable|array',
            'patrols.*.day_type_id' => 'required_with:patrols|integer',
            'patrols.*.pinpoints' => 'required_with:patrols|array|min:1',
            'patrols.*.pinpoints.*.lat' => 'required|numeric|between:-90,90',
            'patrols.*.pinpoints.*.lng' => 'required|numeric|between:-180,180',
            
            // Step 4: Employees (OPSIONAL)
            'employees' => 'nullable|array',
            'employees.*.name' => 'required_with:employees|string|max:255',
            'employees.*.username' => 'required_with:employees|string|max:255|unique:ihm_m_users,username',
            'employees.*.email' => 'nullable|email|max:255|unique:ihm_m_users,email',
            'employees.*.phone' => 'nullable|string|max:20',
            'employees.*.gender' => 'nullable|in:L,P',
            'employees.*.date_birth' => 'nullable|date',
            'employees.*.address' => 'nullable|string',
            'employees.*.role' => 'required_with:employees|in:RT,RW,ADMIN',
            
            // Step 5: Securities (OPSIONAL)
            'securities' => 'nullable|array',
            'securities.*.name' => 'required_with:securities|string|max:255',
            'securities.*.username' => 'required_with:securities|string|max:255|unique:ihm_m_users,username',
            'securities.*.email' => 'nullable|email|max:255|unique:ihm_m_users,email',
            'securities.*.phone' => 'nullable|string|max:20',
            'securities.*.gender' => 'nullable|in:L,P',
            'securities.*.date_birth' => 'nullable|date',
            'securities.*.address' => 'nullable|string',
            
            // Step 6: Bank Accounts (OPSIONAL)
            'bank_accounts' => 'nullable|array',
            'bank_accounts.*.account_number' => 'required_with:bank_accounts|string|max:50',
            'bank_accounts.*.account_holder' => 'required_with:bank_accounts|string|max:255',
            'bank_accounts.*.bank_type' => 'required_with:bank_accounts|string|max:15',
            'bank_accounts.*.bank_code_id' => 'required_with:bank_accounts|integer',
            
            // Step 7: Residents (OPSIONAL - handled separately via CSV upload)
            'residents' => 'nullable|array',
            'residents.*.resident_id' => 'required_with:residents|integer|exists:ihm_m_residents,id',
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nama Cluster',
            'description' => 'Deskripsi',
            'phone' => 'Nomor Telepon',
            'email' => 'Email',
            'logo' => 'Logo',
            'picture' => 'Gambar',
            'radius_checkin' => 'Radius Check-in',
            'radius_patrol' => 'Radius Patrol',
            'offices' => 'Data Kantor',
            'offices.*.name' => 'Nama Kantor',
            'offices.*.type_id' => 'Tipe Kantor',
            'offices.*.latitude' => 'Latitude',
            'offices.*.longitude' => 'Longitude',
            'patrols.*.day_type_id' => 'Tipe Hari',
            'patrols.*.pinpoints' => 'Titik Patroli',
            'employees.*.name' => 'Nama Karyawan',
            'employees.*.username' => 'Username Karyawan',
            'employees.*.role' => 'Role Karyawan',
            'securities.*.name' => 'Nama Security',
            'securities.*.username' => 'Username Security',
            'bank_accounts.*.account_number' => 'Nomor Rekening',
            'bank_accounts.*.account_holder' => 'Nama Pemegang Rekening',
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama cluster harus diisi.',
            'phone.required' => 'Nomor telepon cluster harus diisi.',
            'email.required' => 'Email cluster harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'offices.required' => 'Minimal 1 kantor harus ditambahkan.',
            'offices.min' => 'Minimal 1 kantor harus ditambahkan.',
            'offices.*.name.required' => 'Nama kantor harus diisi.',
            'offices.*.latitude.required' => 'Latitude kantor harus diisi.',
            'offices.*.longitude.required' => 'Longitude kantor harus diisi.',
            'employees.*.username.unique' => 'Username karyawan sudah digunakan.',
            'securities.*.username.unique' => 'Username security sudah digunakan.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
