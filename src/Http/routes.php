<?php

use Biz\School\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('school', [Controllers\SchoolController::class, 'index']);
Route::resource('biz/school/index', Controllers\BizSchoolController::class);
Route::resource('biz/school/teacher', Controllers\BizSchoolTeacherController::class);
Route::resource('biz/school/student', Controllers\BizSchoolStudentController::class);
Route::resource('biz/school/grade', Controllers\BizSchoolGradeController::class);
Route::resource('biz/school/classroom', Controllers\BizSchoolClassRoomController::class);
