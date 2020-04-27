<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Models
    |--------------------------------------------------------------------------
    |
    | name: the route and name used to derive other values if not defined
    | group: which sidebar group this belongs to
    | icon: the Font Awesome icon
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
            'name' => 'books',
            'group' => 'content',
            'icon' => 'book',
        ],
        [
            'name' => 'contact-options',
            'group' => 'advanced',
            'icon' => 'envelope',
        ],
        [
            'name' => 'danalist',
            'label' => 'Dana Wishlist',
            'group' => 'content',
            'icon' => 'soccer-ball-o',
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
            'name' => 'reflections',
            'group' => 'content',
            'icon' => 'leaf',
        ],
        [
            'name' => 'residents',
            'group' => 'content',
            'icon' => 'user',
        ],
        [
            'name' => 'settings',
            'group' => 'advanced',
            'icon' => 'cog',
        ],
        [
            'name' => 'subject-groups',
            'group' => 'av',
            'icon' => 'th-large',
        ],
        [
            'name' => 'subjects',
            'group' => 'av',
            'icon' => 'th-list',
        ],
        [
            'name' => 'subpages',
            'group' => 'content',
            'icon' => 'file',
        ],
        [
            'name' => 'tales',
            'group' => 'content',
            'icon' => 'tree',
        ],
        [
            'name' => 'talks',
            'group' => 'av',
            'icon' => 'volume-up',
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
    | Admin Model Groups
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
        ],
        [
            'name' => 'content',
            'label' => 'Content',
        ],
        [
            'name' => 'advanced',
            'label' => 'Advanced',
        ],
    ],

];
