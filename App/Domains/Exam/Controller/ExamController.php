<?php

namespace App\Domains\Exam\Controller;

use App\Domains\Class\Repository\StudentClass;
use App\Domains\Class\Repository\StudentClassQuery;
use App\Domains\Exam\Repository\Exam;
use App\Domains\Exam\Repository\ExamQuery;
use App\Helpers\Datatable\DTOs\DatatableHeaderDTO;
use App\Helpers\Datatable\DTOs\DatatableRowDTO;
use App\Responses\DatatableResponse;
use App\Traits\UserTrait;
use SELF\src\Helpers\Enums\Validation\ValidateEnum;
use SELF\src\Http\Controller;
use SELF\src\Http\Request;
use SELF\src\Http\Responses\MustacheResponse;
use SELF\src\Http\Responses\RedirectResponse;

class ExamController extends Controller
{
    use UserTrait;

    public function index()
    {
        $datatable = new DatatableResponse('Tentamens');

        $headers = [
            new DatatableHeaderDTO('Examen'),
            new DatatableHeaderDTO('Datum'),
        ];

        $datatable->setHeaders($headers);

        $rows = array_map(function ($exam) {
            return new DatatableRowDTO($exam->export(), []);
        }, ExamQuery::create()->filterByIsVisible($this->user)->find()->getObjects());

        $datatable->setRows($rows);


        return $datatable;
    }

    public function indexNewOrEdit(Request $request, array $params): MustacheResponse
    {
        $title = 'Examen maken';
        $viewParams = [
            'classes' => array_map(
                fn (StudentClass $class) => [
                    'id' => $class->id,
                    'name' => $class->name,
                ],
                StudentClassQuery::create()->find()->get()
            ),
        ];

        if (isset($params['exam'])) {
            $title = 'Examen wijzigen';
            $model = ExamQuery::create()->findPk($params['exam']);

            if ($model instanceof Exam) {
                $viewParams['exam'] = $model->export(true);
            }
        }

        return new MustacheResponse('Exams/new_or_edit', $viewParams, $title);
    }

    public function submitNewOrEdit(Request $request, array $params)
    {
        $data = $request->validate([
            'name' => ValidateEnum::NOT_EMPTY,
            'description' => ValidateEnum::NOT_EMPTY,
            'date' => ValidateEnum::NOT_EMPTY,
            'class_ids' => ValidateEnum::NOT_EMPTY,
        ]);

        if ($data === false) {
            return $request->back();
        }

        if (isset($params['exam'])) {
            /** @var Exam | null $exam */
            $exam = ExamQuery::create()->findPk($params['exam']);
            if ($exam === null) {
                // Should not happen, but check anyway.
                die('Exam can not be found.');
            }
        } else {
            $exam = new Exam();
        }

        $exam
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setDate($data['date'])
            ->syncClassIds($data['class_ids'])
            ->save();

        return new RedirectResponse(route('/exams'));
    }

    public function show(Request $request, array $params)
    {
        $exam = ExamQuery::create()->findPK($params['id']);
        if (!$exam instanceof Exam) {
            return new MustacheResponse('404', [], '404');
        }

        $data['exam'] = $exam->export(true);

        return new MustacheResponse('Exams/show', $data, 'Tentamen');
    }
}