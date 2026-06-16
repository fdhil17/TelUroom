<?php

return [
    'required' => 'Kolom :attribute wajib diisi.',
    'email' => 'Kolom :attribute harus berupa alamat email yang valid.',
    'string' => 'Kolom :attribute harus berupa teks.',
    'max' => [
        'string' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
    ],
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'unique' => ':attribute sudah terdaftar.',
    
    'custom' => [
        'email' => [
            'required' => 'Email wajib diisi.',
            'email' => 'Format email tidak valid.'
        ],
        'password' => [
            'required' => 'Password wajib diisi.',
            'confirmed' => 'Konfirmasi password tidak cocok.'
        ],
        'name' => [
            'required' => 'Nama wajib diisi.'
        ],
    ],
    
    'attributes' => [
        'name' => 'nama',
        'email' => 'email',
        'password' => 'password',
    ],
];
