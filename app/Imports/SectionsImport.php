<?php

namespace App\Imports;

use App\Models\Admin\Section;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SectionsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Section([
            'course_id'  => $row['course_id'],
            'year_level' => $row['year_level'],
            'section'    => $row['section'],
            'semester'   => $row['semester'],
        ]);
    }
}