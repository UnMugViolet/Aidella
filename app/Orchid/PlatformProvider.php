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
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

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
                ->route('platform.dog-races.create')
                ->permission('platform.systems.users')
                ->title(__('Race de chiens')),

            Menu::make(__('Liste de chiens'))
                ->icon('bs.card-list')
                ->route('platform.dog-races')
                ->permission('platform.systems.users'),

            Menu::make(__('Ajouter une categorie de post'))
                ->icon('bs.bookmarks')
                ->route('platform.post-categories.create')
                ->permission('platform.systems.users')
                ->title(__('Posts')),
        
            Menu::make(__('Liste des categories de post'))
                ->icon('bs.card-list')
                ->route('platform.post-categories')
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
