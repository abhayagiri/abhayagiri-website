<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | name: the route and name used to derive other values if not defined
    | group: which sidebar group this belongs to (if any)
    | icon: the Font Awesome icon
    | route: should a route be created (defaults true)
    | restore: should a restore route be created (defaults true)
    | super_admin: does this require super admin? (defaults false)
    |
    */
    'models' => [
        [
            'name' => 'authors',
            'group' => 'content',
            'icon' => 'child',
        ],
        [
            'name' => 'blobs',
            'group' => 'advanced',
            'icon' => 'quote-left',
            'restore' => false,
        ],
        [
            'name' => 'books',
            'group' => 'content',
            'icon' => 'book',
        ],
        [
            'name' => 'languages',
            'group' => 'advanced',
            'icon' => 'language',
        ],
        [
            'name' => 'news',
            'group' => 'content',
            'icon' => 'bullhorn',
        ],
        [
            'name' => 'settings',
            'path' => 'setting',
            'group' => 'advanced',
            'icon' => 'cog',
            'route' => false,
        ],
        [
            'name' => 'playlist-groups',
            'group' => 'av',
            'icon' => 'houzz',
        ],
        [
            'name' => 'playlists',
            'group' => 'av',
            'icon' => 'delicious',
        ],
        [
            'name' => 'subject-groups',
            'group' => 'av',
            'icon' => 'address-book',
        ],
        [
            'name' => 'subjects',
            'group' => 'av',
            'icon' => 'address-card',
        ],
        [
            'name' => 'tags',
            'group' => 'av',
            'icon' => 'tag',
        ],
        [
            'name' => 'talks',
            'group' => 'av',
            'icon' => 'volume-up',
        ],
        [
            'name' => 'talk-types',
            'group' => 'av',
            'icon' => 'comments-o',
        ],
        [
            'name' => 'users',
            'group' => 'advanced',
            'icon' => 'users',
            'super_admin' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Groups
    |--------------------------------------------------------------------------
    |
    | name: the short name to identify this group
    | label: what is shown in the sidebar
    | icon: the Font Awesome icon
    |
    */
    'groups' => [
        [
            'name' => 'av',
            'label' => 'Audio & Video',
            'icon' => 'podcast',
        ],
        [
            'name' => 'content',
            'label' => 'Content',
            'icon' => 'pencil',
        ],
        [
            'name' => 'advanced',
            'label' => 'Advanced',
            'icon' => 'share-alt',
        ],
    ],

];
