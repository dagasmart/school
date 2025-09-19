<?php

use DagaSmart\School\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::resource('biz/school/index', Controllers\SchoolController::class);
Route::resource('biz/school/teacher', Controllers\TeacherController::class);
Route::resource('biz/school/student', Controllers\StudentController::class);
Route::resource('biz/school/classroom', Controllers\ClassroomController::class);
