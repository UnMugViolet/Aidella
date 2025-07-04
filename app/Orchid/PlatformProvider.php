<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [

            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Administration')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),

            Menu::make(__('Ajouter une race de chien'))
                ->icon('bs.plus')
                ->route('plateform.dog-races.post')
                ->permission('platform.systems.users'),
            
            Menu::make(__('Liste des races de chien'))
                ->icon('bs.card-list')
                ->route('platform.dog-races')
                ->permission('platform.systems.users'),

            Menu::make(__('Ajouter une categorie de post'))
                ->icon('bs.bookmarks')
                ->route('platform.post-categories.create')
                ->permission('platform.systems.users')
                ->title(__('Categories de post')),
        
            Menu::make(__('Liste des categories de post'))
                ->icon('bs.list-stars')
                ->route('platform.post-categories')
                ->permission('platform.systems.users'),

            Menu::make(__('Créer un article'))
                ->icon('bs.mailbox')
                ->route('platform.posts.create')
                ->permission('platform.systems.users')
                ->title(__('Articles')),
        
            Menu::make(__('Liste des articles'))
                ->icon('bs.list')
                ->route('platform.posts')
                ->permission('platform.systems.users'),
            
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
