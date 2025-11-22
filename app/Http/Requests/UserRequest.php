<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $userId = $this->route('user') ? $this->route('user')->id : null;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique(env('TABLE_PREFIX', '') . 'm_users', 'username')->ignore($userId)
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique(env('TABLE_PREFIX', '') . 'm_users', 'email')->ignore($userId)
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:M,F'],
            'date_birth' => ['nullable', 'date', 'before:today'],
            'address' => ['nullable', 'string'],
            'province_id' => ['nullable', 'integer'],
            'regency_id' => ['nullable', 'integer'],
            'role' => ['required', 'string', 'in:ADMIN,USER,VIEWER'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'status' => ['required', 'in:PENDING,VERIFIED,REJECTED'],
            'active_flag' => ['nullable', 'boolean'],
            'suspend' => ['nullable', 'boolean'],
            'blocked' => ['nullable', 'boolean'],
        ];

        // Password is required only on create, optional on update
        if ($this->isMethod('POST')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        return $rules;
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nama Lengkap',
            'username' => 'Username',
            'email' => 'Email',
            'phone' => 'No. Telepon',
            'gender' => 'Jenis Kelamin',
            'date_birth' => 'Tanggal Lahir',
            'address' => 'Alamat',
            'province_id' => 'Provinsi',
            'regency_id' => 'Kabupaten/Kota',
            'role' => 'Role',
            'avatar' => 'Foto Profil',
            'password' => 'Password',
            'password_confirmation' => 'Konfirmasi Password',
            'status' => 'Status',
            'active_flag' => 'Status Aktif',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'username.unique' => 'Username sudah digunakan, silakan pilih username lain.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'avatar.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
