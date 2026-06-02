<?php

namespace App\Interfaces;

interface AcademicSettingInterface {
    public function getAcademicSetting();

    public function updateAttendanceType($request);

    public function updateFinalMarksSubmissionStatus($request);

    public function updateActiveSemester($request);

    public function updateActiveSession($request);

    public function updateGeneralSettings($request);

    public function findFirst();
}
