<?php

namespace App\Domains\Homepage\Controllers;

use App\Domains\Navigation\Controllers\NavigationController;
use SELF\src\Http\Responses\MustacheResponse;
use SELF\src\Http\Responses\Response;

class HomepageController
{
    public function index(): Response
    {
        $navigation = new NavigationController();

        $data = [];
        $data['navigation_items'] = $navigation->getNavigation();
        return new MustacheResponse('Homepage/dashboard', $data, 'Overzicht');
    }
}