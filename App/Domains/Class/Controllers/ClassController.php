<?php

namespace App\Domains\Class\Controllers;

use App\Domains\Class\Repository\StudentClass;
use App\Domains\Class\Repository\StudentClassQuery;
use SELF\src\Helpers\Enums\Validation\ValidateEnum;
use SELF\src\Http\Request;
use SELF\src\Http\Responses\MustacheResponse;
use SELF\src\Http\Responses\RedirectResponse;
use SELF\src\Http\Responses\Response;

class ClassController
{
    /**
     * @return MustacheResponse
     */
    public function index(): MustacheResponse
    {
        return new MustacheResponse('Classes/index');
    }

    /**
     * @param Request $request
     * @param array $params
     * @return MustacheResponse
     */
    public function indexNewOrEdit(Request $request, array $params): MustacheResponse
    {
        $viewParams = [];

        if (isset($params['class'])) {
            $model = StudentClassQuery::create()->findPk($params['class']);

            if ($model instanceof StudentClass) {
                $viewParams['class']['name'] = $model->name;
            }
        }

        return new MustacheResponse('Classes/new_or_edit', $viewParams);
    }

    /**
     * @param Request $request
     * @param array $params
     * @return Response
     */
    public function submitNewOrEdit(Request $request, array $params): Response
    {
        $data = $request->validate([
            'name' => ValidateEnum::NOT_EMPTY,
        ]);

        if ($data === false) {
            return new MustacheResponse('Classes/new_or_edit', $params + ['failed' => true]);
        }

        if (isset($params['class'])) {
            /** @var StudentClass | null $class */
            $class = StudentClassQuery::create()->findPk($params['class']);
            if ($class === null) {
                // Should not happen, but check anyway.
                die('Class can not be found.');
            }
        } else {
            $class = new StudentClass();
        }

        $class
            ->setName($data['name'])
            ->save();

        return new RedirectResponse(route('/classes'));
    }
}