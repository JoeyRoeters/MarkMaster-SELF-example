<?php

namespace App\Domains\Homepage\Controllers;

use App\Authenticator;
use App\Domains\Exam\Repository\ExamQuery;
use SELF\src\Http\Responses\MustacheResponse;
use SELF\src\Http\Responses\Response;

class HomepageController
{
    public function index(): Response
    {
        $data = [];

        // exam stats
        $user = Authenticator::user();
        $exams = ExamQuery::create()->filterByIsVisible($user)->find();
        $data['total']['exams'] = $exams->count();
        $data['total']['exams_done'] = 0;
        $data['total']['exams_todo'] = 0;

        foreach ($exams as $exam) {
            if ($exam->getDate() < new \DateTime()) {
                $data['total']['exams_done']++;
            } else {
                $data['total']['exams_todo']++;
            }
        }

        return new MustacheResponse('Homepage/dashboard', $data, 'Overzicht');
    }
}