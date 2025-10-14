<?php

use DagaSmart\School\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

Route::group([
    'prefix' => 'biz',
], function (Router $router) {
    $router->resource('school/index', Controllers\SchoolController::class);
    $router->resource('school/teacher', Controllers\TeacherController::class);
    $router->resource('school/student', Controllers\StudentController::class);
    $router->resource('school/classroom', Controllers\ClassroomController::class);
});

//一键导入文件
Route::post('school/teacher/import', [Controllers\TeacherController::class, 'import']);
Route::post('school/student/import', [Controllers\StudentController::class, 'import']);
Route::post('school/teacher/importChunk', [Controllers\TeacherController::class, 'importChunk']);

//删除导入文件
Route::post('school/common/remove', [Controllers\CommonController::class, 'remove']);
