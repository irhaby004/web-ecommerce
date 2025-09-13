<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut berisi pesan kesalahan default yang digunakan oleh
    | validator. Beberapa aturan memiliki beberapa versi seperti aturan ukuran.
    | Silakan sesuaikan pesan-pesan ini sesuai kebutuhan aplikasi Anda.
    |
    */

    'accepted'             => ':attribute harus diterima.',
    'active_url'           => ':attribute bukan URL yang valid.',
    'after'                => ':attribute harus berupa tanggal setelah :date.',
    'after_or_equal'       => ':attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha'                => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka, dan tanda hubung.',
    'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':attribute harus berupa array.',
    'before'               => ':attribute harus berupa tanggal sebelum :date.',
    'before_or_equal'      => ':attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'numeric' => ':attribute harus antara :min dan :max.',
        'file'    => ':attribute harus antara :min dan :max kilobita.',
        'string'  => ':attribute harus antara :min dan :max karakter.',
        'array'   => ':attribute harus memiliki antara :min dan :max item.',
    ],
    'boolean'              => 'Kolom :attribute harus bernilai true atau false.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'date'                 => ':attribute bukan tanggal yang valid.',
    'date_format'          => ':attribute tidak sesuai format :format.',
    'different'            => ':attribute dan :other harus berbeda.',
    'digits'               => ':attribute harus :digits digit.',
    'digits_between'       => ':attribute harus antara :min dan :max digit.',
    'dimensions'           => ':attribute memiliki dimensi gambar yang tidak valid.',
    'distinct'             => 'Kolom :attribute memiliki nilai duplikat.',
    'email'                => ':attribute harus berupa alamat email yang valid.',
    'exists'               => ':attribute yang dipilih tidak valid.',
    'file'                 => ':attribute harus berupa berkas.',
    'filled'               => 'Kolom :attribute harus memiliki nilai.',
    'image'                => ':attribute harus berupa gambar.',
    'in'                   => ':attribute yang dipilih tidak valid.',
    'in_array'             => 'Kolom :attribute tidak ada di :other.',
    'integer'              => ':attribute harus berupa bilangan bulat.',
    'ip'                   => ':attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => ':attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => ':attribute harus berupa string JSON yang valid.',
    'max'                  => [
        'numeric' => ':attribute tidak boleh lebih besar dari :max.',
        'file'    => ':attribute tidak boleh lebih besar dari :max kilobita.',
        'string'  => ':attribute tidak boleh lebih besar dari :max karakter.',
        'array'   => ':attribute tidak boleh memiliki lebih dari :max item.',
    ],
    'mimes'                => ':attribute harus berupa berkas berjenis: :values.',
    'mimetypes'            => ':attribute harus berupa berkas berjenis: :values.',
    'min'                  => [
        'numeric' => ':attribute minimal :min.',
        'file'    => ':attribute minimal :min kilobita.',
        'string'  => ':attribute minimal :min karakter.',
        'array'   => ':attribute minimal memiliki :min item.',
    ],
    'not_in'               => ':attribute yang dipilih tidak valid.',
    'numeric'              => ':attribute harus berupa angka.',
    'present'              => 'Kolom :attribute harus ada.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => 'Kolom :attribute wajib diisi.',
    'required_if'          => 'Kolom :attribute wajib diisi bila :other adalah :value.',
    'required_unless'      => 'Kolom :attribute wajib diisi kecuali :other terdapat pada :values.',
    'required_with'        => 'Kolom :attribute wajib diisi bila :values ada.',
    'required_with_all'    => 'Kolom :attribute wajib diisi bila :values ada.',
    'required_without'     => 'Kolom :attribute wajib diisi bila :values tidak ada.',
    'required_without_all' => 'Kolom :attribute wajib diisi bila tidak ada :values yang ada.',
    'same'                 => ':attribute dan :other harus sama.',
    'size'                 => [
        'numeric' => ':attribute harus berukuran :size.',
        'file'    => ':attribute harus berukuran :size kilobita.',
        'string'  => ':attribute harus berukuran :size karakter.',
        'array'   => ':attribute harus berisi :size item.',
    ],
    'string'               => ':attribute harus berupa string.',
    'timezone'             => ':attribute harus berupa zona waktu yang valid.',
    'unique'               => ':attribute sudah digunakan.',
    'uploaded'             => 'Gagal mengunggah :attribute.',
    'url'                  => 'Format :attribute tidak valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Di sini kamu dapat menentukan pesan validasi khusus untuk atribut tertentu
    | menggunakan format "attribute.rule".
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'pesan-khusus',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | Baris berikut dipakai untuk menggantikan placeholder atribut dengan
    | nama yang lebih mudah dibaca.
    |
    */

    'attributes' => [],

];
