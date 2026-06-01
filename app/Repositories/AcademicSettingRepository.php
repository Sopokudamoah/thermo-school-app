<?php

namespace App\Repositories;

use App\Interfaces\AcademicSettingInterface;
use App\Models\AcademicSetting;

class AcademicSettingRepository implements AcademicSettingInterface {
    public function getAcademicSetting(){
        return AcademicSetting::find(1);
    }

    public function updateAttendanceType($request) {
        try {
            AcademicSetting::where('id', 1)->update($request);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update attendance type. '.$e->getMessage());
        }
    }

    public function updateFinalMarksSubmissionStatus($request) {
        $status = "off";
        if(isset($request['marks_submission_status'])) {
            $status = "on";
        }
        try {
            AcademicSetting::where('id', 1)->update(['marks_submission_status' => $status]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update final marks submission status. '.$e->getMessage());
        }
    }

    public function updateActiveSemester($request)
    {
        try {
            AcademicSetting::where('id', 1)->update(['active_semester_id' => $request['active_semester_id']]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update active semester. ' . $e->getMessage());
        }
    }

    public function findFirst()
    {
        return AcademicSetting::first();
    }
}
