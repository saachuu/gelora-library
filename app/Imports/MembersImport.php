<?php

namespace App\Imports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MembersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Member([
            'member_id_number' => $row['nis'],
            'full_name'        => $row['nama'],
            'position'         => $row['kelas'],
            'contact'          => $row['hp'] ?? '-',
            'is_active'        => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'nis'   => 'required|unique:members,member_id_number', // Cek biar NIS gak duplikat
            'nama'  => 'required|string',
            'kelas' => 'required',
        ];
    }
}
