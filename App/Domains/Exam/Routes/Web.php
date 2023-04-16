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
        $this->make(
            Route::GET(
                path: '/exams',
                targetClass: ExamController::class,
                targetMethod: 'index',
                middleware: [new RedirectUnauthenticatedMiddleware('/login')]
            )
        );
        $this->make(
            Route::GET(
                path: '/exams/{id}',
                targetClass: ExamController::class,
                targetMethod: 'index',
                middleware: [new RedirectUnauthenticatedMiddleware('/login')]
            )
        );
        $this->make(
            Route::GET(
                path: '/exams',
                targetClass: ExamController::class,
                targetMethod: 'index',
                middleware: [new RedirectUnauthenticatedMiddleware('/login')]
            )
        );
        $this->make(
            Route::GET(
                path: '/exams',
                targetClass: ExamController::class,
                targetMethod: 'index',
                middleware: [new RedirectUnauthenticatedMiddleware('/login')]
            )
        );
    }
}