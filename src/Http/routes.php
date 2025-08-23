<?php

use DagaSmart\School\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::resource('biz/school/index', Controllers\SchoolController::class);
Route::resource('biz/school/teacher', Controllers\SchoolTeacherController::class);
Route::resource('biz/school/student', Controllers\SchoolStudentController::class);
Route::resource('biz/school/grade', Controllers\SchoolGradeController::class);
Route::resource('biz/school/classroom', Controllers\SchoolClassroomController::class);
