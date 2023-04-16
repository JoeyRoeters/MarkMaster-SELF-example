<?php

namespace App\Domains\Exam\Controller;

use App\Authenticator;
use App\Domains\Class\Repository\StudentClass;
use App\Domains\Class\Repository\StudentClassQuery;
use App\Domains\Exam\Repository\Exam;
use App\Domains\Exam\Repository\ExamClassQuery;
use App\Domains\Exam\Repository\ExamQuery;
use App\Domains\Exam\Repository\ExamUser;
use App\Domains\Mark\Repository\Mark;
use App\Domains\Mark\Repository\MarkQuery;
use App\Domains\User\Repository\User;
use App\Domains\User\Repository\UserQuery;
use App\Helpers\Datatable\DTOs\DatatableHeaderDTO;
use App\Helpers\Datatable\DTOs\DatatableRowDTO;
use App\Helpers\SweetAlert\SweetAlert;
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

    /**
     * @return DatatableResponse
     */
    public function index()
    {
        $datatable = new DatatableResponse('Tentamens');

        $headers = [
            new DatatableHeaderDTO('Examen'),
            new DatatableHeaderDTO('Datum'),
        ];

        if ($this->user->isAdmin() || $this->user->isTeacher()) {
            $datatable->setCreate(environment('APP_URL') . '/exams/create');
        }

        $datatable->setHeaders($headers);

        $rows = array_map(function ($exam) {
            return new DatatableRowDTO($exam->export());
        }, ExamQuery::create()->find()->getObjects());

        $datatable->setRows($rows);

        return $datatable;
    }

    /**
     * @param Request $request
     * @param array $params
     * @return MustacheResponse
     */
    public function indexNewOrEdit(Request $request, array $params): MustacheResponse
    {
        $title = 'Examen maken';
        $model = null;
        $viewParams = [];
        if (isset($params['exam'])) {
            $title = 'Examen wijzigen';
            $model = ExamQuery::create()->findPk($params['exam']);

            if ($model instanceof Exam) {
                $viewParams['exam'] = $model->export(true);
            }
        }

        $viewParams['classes'] =  array_map(
            fn (StudentClass $class) => [
                'id' => $class->id,
                'name' => $class->name,
                'selected' => $model instanceof Exam ? ExamClassQuery::create()->filterByClassId($class->getId())->filterByExamId($model->getId())->exists() : false
            ],
            StudentClassQuery::create()->find()->get()
        );

        return new MustacheResponse('Exams/new_or_edit', $viewParams, $title);
    }

    /**
     * @param Request $request
     * @param array $params
     * @return RedirectResponse
     */
    public function submitNewOrEdit(Request $request, array $params)
    {
        $data = $request->validate([
            'name' => ValidateEnum::NOT_EMPTY,
            'description' => ValidateEnum::NOT_EMPTY,
            'date' => ValidateEnum::NOT_EMPTY,
            'class_ids' => ValidateEnum::NOT_EMPTY,
        ]);

        if ($data === false) {
            SweetAlert::createError('Alle velden zijn verplicht.');
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
            ->setTeacherId($this->user->getId())
            ->setDate($data['date'])
            ->save();

        // Needs to be this way in case it's a new model.
        $exam
            ->syncClassIds($data['class_ids'])
            ->save();

        SweetAlert::createSuccess('Examen is opgeslagen.');

        return new RedirectResponse(route('/exams'));
    }

    /**
     * @param Request $request
     * @param array $params
     * @return RedirectResponse
     */
    public function indexUpdateMarks(Request $request, array $params): MustacheResponse
    {
        /** @var Exam | null $exam */
        $exam = ExamQuery::create()->findPk($params['exam']);

        if ($exam === null) {
            return new MustacheResponse('404', [], '404');
        }

        $data = [
            'exam' => $exam->export(true),
            'marks' => array_map(
                fn (Mark $mark) => $mark->export(),
                $exam->marks()
            ),
            'students' => array_map(
                fn (User $student) => $student->export(),
                UserQuery::create()->find()->get()
            ),
        ];

        $data['students'] = array_filter(
            $data['students'],
            fn (array $student) => $student['is_student'] && in_array($exam->getId(), $student['exam_ids'])
        );

        return new MustacheResponse(
            'Exams/update_marks',
            $data,
            'Cijfers toevoegen'
        );
    }

    /**
     * @param Request $request
     * @param array $params
     * @return RedirectResponse
     */
    public function newOrEditMark(Request $request, array $params): RedirectResponse
    {
        $data = $request->validate([
            'mark' => ValidateEnum::NOT_EMPTY,
            'student_id' => ValidateEnum::NOT_EMPTY,
        ]);

        // Multiply to store in database.
        $data['mark'] *= 10;

        $examId = $params['exam'];

        $mark = MarkQuery::create()
            ->filterByExamId($examId)
            ->filterByStudentId($data['student_id'])
            ->findOne();

        if ($mark === null) {
            $mark = new Mark();
        }

        $mark
            ->setExamId($examId)
            ->setStudentId($data['student_id'])
            ->setMark($data['mark'])
            ->save();

        return $request->back();
    }

    /**
     * @param Request $request
     * @param array $params
     * @return RedirectResponse
     */
    public function deleteMark(Request $request, array $params): RedirectResponse
    {
        $examId = $params['exam'];
        $markId = $params['mark'];

        MarkQuery::create()
            ->filterById($markId)
            ->delete();

        return new RedirectResponse(
            route(
                sprintf('/exams/%s/update-marks', $examId)
            )
        );
    }

    public function registerForExam(Request $request, array $params): RedirectResponse
    {
        $user = Authenticator::user();
        $examId = $params['exam'];

        (new ExamUser())
            ->setUserId($user->id)
            ->setExamId($examId)
            ->save();

        return new RedirectResponse(
            route('/exams/' . $examId)
        );
    }

    /**
     * @param Request $request
     * @param array $params
     * @return MustacheResponse
     */
    public function show(Request $request, array $params)
    {
        $exam = ExamQuery::create()->findPK($params['id']);
        if (!$exam instanceof Exam) {
            return new MustacheResponse('404', [], '404');
        }

        $data['exam'] = $exam->export(true);

        return new MustacheResponse('Exams/show', $data, 'Tentamen');
    }

    /**
     * @param Request $request
     * @param array $params
     * @return RedirectResponse
     */
    public function delete(Request $request, array $params)
    {
        $exam = ExamQuery::create()->findPK($params['exam']);
        if (!$exam instanceof Exam) {
            return new MustacheResponse('404', [], '404');
        }

        if ($exam->hasRights()) {
            SweetAlert::createSuccess('Examen is verwijderd.');

            $exam->delete();
        } else {
            SweetAlert::createError('Examen kon niet verwijderd worden. Geen rechten.');
        }

        return new RedirectResponse('/exams');
    }
}