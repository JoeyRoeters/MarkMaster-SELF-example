<?php

namespace App\Domains\Homepage\Controllers;

use App\Authenticator;
use App\Domains\Exam\Repository\ExamQuery;
use App\Domains\Mark\Repository\MarkQuery;
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
        $data['total']['exams_todo_mark'] = 0;

        foreach ($exams as $exam) {
            if ($exam->getDate() < new \DateTime()) {
                if ($exam->getMarks()->count() > 0) {
                    $data['total']['exams_done']++;
                } else {
                    $data['total']['exams_todo_mark']++;
                }
            } else {
                $data['total']['exams_todo']++;
            }
        }

        $marks = MarkQuery::create()->filterByStudentId($user->getId())->orderBy('created_at')->find();
        $data['total']['marks'] = $marks->count();
        $data['total']['marks_average'] = 0;
        foreach ($marks as $mark) {
            $data['total']['marks_average'] += $mark->getMark();
            $data['last_mark'] = $mark->getMark();
        }

        if ($data['total']['marks'] > 0) {
            $data['total']['marks_average'] = round($data['total']['marks_average'] / $data['total']['marks'], 1);
        }

        return new MustacheResponse('Homepage/dashboard', $data, 'Overzicht');
    }
}