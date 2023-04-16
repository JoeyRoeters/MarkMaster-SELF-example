<?php

namespace App\Domains\Exam\Routes;

use App\Domains\Exam\Controller\ExamController;
use App\Middleware\RedirectUnauthenticatedMiddleware;
use SELF\src\Helpers\Route\AbstractRoutable;
use SELF\src\Http\Route;

class Web extends AbstractRoutable
{
    public function buildRoutes(): void
    {
        $this
            ->setBoundMiddleware([new RedirectUnauthenticatedMiddleware('/login')])
            ->make(
                Route::POST(
                    path: '/exams/{exam}/marks/{mark}/delete',
                    targetClass: ExamController::class,
                    targetMethod: 'deleteMark',
                )
            )
            ->make(
                Route::GET(
                    path: '/exams',
                    targetClass: ExamController::class,
                    targetMethod: 'index',
                )
            )
            ->make(
                Route::GET(
                    path: '/exams/create',
                    targetClass: ExamController::class,
                    targetMethod: 'indexNewOrEdit',
                )
            )
            ->make(
                Route::POST(
                    path: '/exams/create',
                    targetClass: ExamController::class,
                    targetMethod: 'submitNewOrEdit',
                )
            )
            ->make(
                Route::GET(
                    path: '/exams/{exam}/edit',
                    targetClass: ExamController::class,
                    targetMethod: 'indexNewOrEdit',
                )
            )
            ->make(
                Route::POST(
                    path: '/exams/{exam}/edit',
                    targetClass: ExamController::class,
                    targetMethod: 'submitNewOrEdit',
                )
            )
            ->make(
                Route::GET(
                    path: '/exams/{exam}/update-marks',
                    targetClass: ExamController::class,
                    targetMethod: 'indexUpdateMarks'
                )
            )
            ->make(
                Route::POST(
                    path: '/exams/{exam}/update-marks',
                    targetClass: ExamController::class,
                    targetMethod: 'newOrEditMark',
                )
            )
            ->make(
                Route::GET(
                    path: '/exams/{id}',
                    targetClass: ExamController::class,
                    targetMethod: 'show',
                )
            )
            ->make(
                Route::DELETE(
                    path: '/exams/{exam}/delete',
                    targetClass: ExamController::class,
                    targetMethod: 'delete',
                )
            );
    }
}