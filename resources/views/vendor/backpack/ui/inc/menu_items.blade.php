{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-dropdown title="Authentication" icon="la la-group">
    <x-backpack::menu-dropdown-item title="Users" icon="la la-user" :link="backpack_url('user')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-separator title="Information sur les chiens" />
<x-backpack::menu-item title="Race de chien" icon="la la-dog" :link="backpack_url('dog-race')" />

<x-backpack::menu-separator title="Publication d'articles" />
<x-backpack::menu-item title="Post categories" icon="la la-book" :link="backpack_url('post-category')" />
<x-backpack::menu-item title="Blog posts" icon="la la-bookmark" :link="backpack_url('blog-post')" />
