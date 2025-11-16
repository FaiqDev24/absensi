<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentExport implements FromCollection, WithHeadings, WithMapping
{
    private $index = 0;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Student::all();
    }

    public function headings(): array
    {
        return ['No', 'NIS', 'Nama', 'Gender', 'Kelas', 'Tingkat'];
    }

    public function map($student): array
    {
        $this->index++;
        return [
            $this->index,
            $student->nis,
            $student->name,
            $student->gender,
            $student->classRoom->name ?? '-',
            $student->grade,
        ];
    }
}
