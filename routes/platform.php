<?php

declare(strict_types=1);

use App\Orchid\Screens\BlogPostDogRaceEditScreen;
use App\Orchid\Screens\BlogPostDogRaceListScreen;
use App\Orchid\Screens\BlogPostDogRaceScreen;
use App\Orchid\Screens\PlatformScreen;

use App\Orchid\Screens\BlogPostScreen;
use App\Orchid\Screens\BlogPostEditScreen;
use App\Orchid\Screens\BlogPostListScreen;


use App\Orchid\Screens\PostCategoryScreen;
use App\Orchid\Screens\PostCategoryEditScreen;
use App\Orchid\Screens\PostCategoryListScreen;

use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;

use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;

use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/
// Main
Route::screen('/main', PlatformScreen::class)
    ->name('custom.dashboard');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));


// Dog races
Route::screen('dog-races', BlogPostDogRaceListScreen::class)->name('platform.dog-races');
Route::screen('dog-races/post', BlogPostDogRaceScreen::class)->name('plateform.dog-races.post');
Route::screen('dog-races/{dogRace}/edit', BlogPostDogRaceEditScreen::class)->name('platform.dog-races.edit');

// Post categories
Route::screen('post-categories', PostCategoryListScreen::class)->name('platform.post-categories');
Route::screen('post-categories-add', PostCategoryScreen::class)->name('platform.post-categories.create');
Route::screen('post-categories/{postCategory}/edit', PostCategoryEditScreen::class)->name('platform.post-categories.edit');

// Blog posts
Route::screen('posts', BlogPostListScreen::class)->name('platform.posts');
Route::screen('posts-add', BlogPostScreen::class)->name('platform.posts.create');
Route::screen('posts/{blogPost}/edit', BlogPostEditScreen::class)->name('platform.posts.edit');

