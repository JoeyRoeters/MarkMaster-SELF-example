<?php

namespace App\Domains\Navigation\Controllers;

use App\Authenticator;
use App\Domains\Navigation\DTO\NavigationDTO;
use App\Domains\Role\Repository\Role;
use App\Domains\Role\Repository\RoleQuery;
use App\Domains\User\Repository\User;
use App\Enums\RoleEnum;

class NavigationController
{
    public function getNavigation(): array
    {
        $user = Authenticator::user();
        if (!$user instanceof User) {
            return [];
        }

        $items = [];

        $items[] = new NavigationDTO(
            name: 'Overzicht',
            url: '/',
            icon: 'fas fa-home'
        );
        $items[] = new NavigationDTO(
            name: 'Tentamens',
            url: '/exams',
            icon: 'fas fa-scroll'
        );
        if ($user->hasRole(RoleQuery::findOrCreate(RoleEnum::STUDENT))) {
            $items[] = new NavigationDTO(
                name: 'Cijfers',
                url: '/exams',
                icon: 'fas fa-marker'
            );
        }

        if ($user->hasRole(RoleQuery::findOrCreate(RoleEnum::ADMIN))) {
            $items[] = new NavigationDTO(
                name: 'Gebruikers',
                url: '/users',
                icon: 'fas fa-users'
            );
            $items[] = new NavigationDTO(
                name: 'Klassen',
                url: '/groups',
                icon: 'fas fa-user-group'
            );
        }

        $items[] = new NavigationDTO(
            name: 'Profiel',
            url: '/profile',
            icon: 'fas fa-user',
            isSubMenu: true
        );

        $items[] = new NavigationDTO(
            name: 'Uitloggen',
            url: '/logout',
            icon: 'fas fa-sign-out-alt',
            isSubMenu: true
        );

        return array_map(fn(NavigationDTO $item) => $item->export(), $items);
    }
}