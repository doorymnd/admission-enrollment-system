<?php

namespace App\Services;

use App\Models\Admin\Subject;

class SubjectService
{

    public static function savePrerequisites(int $subject_id, array $prerequisites)
    {
        $subject = Subject::find($subject_id);

        if (!$subject) {
            return false;
        }


        $subject->prerequisites()->sync($prerequisites);

        return true;
    }
}