<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | These are settings with their default values.
    |
    */

    'books' => [

        'request_form' => [
            'type' => 'markdown',
            'default_value' => [
                'text_en' => 'The "Selection" box below contains the list of books that will be requested.',
                'text_th' => 'กรุณาตรวจสอบรายการหนังสือและสื่อธรรมะที่ท่านต้องการขอ',
            ],
            'old_key' => [
                'text_en' => 'books.request_form_en',
                'text_th' => 'books.request_form_th',
            ],
        ],

    ],

    'contact' => [

        'preamble' => [
            'type' => 'markdown',
            'default_value' => [
                'text_en' => 'Please use this page to contact the monastery.',
                'text_th' => 'กรุณาใช้หน้าเว็บนี้เป็นช่องทางหลักในการติดต่อทางวัด',
            ],
            'old_key' => [
                'text_en' => 'contact.preamble_en',
                'text_th' => 'contact.preamble_th',
            ],
        ],

    ],

    'default_images' => [

        'authors' => [
            'type' => 'image',
            'default_value' => 'default.jpg',
            'old_key' => 'authors.default_image_file',
        ],
        'books' => [
            'type' => 'image',
            'default_value' => 'default.jpg',
            'old_key' => 'books.default_image_file',
        ],
        'news' => [
            'type' => 'image',
            'default_value' => 'default.jpg',
            'old_key' => 'news.default_image_file',
        ],
        'playlist_groups' => [
            'type' => 'image',
            'default_value' => 'default.jpg',
            'old_key' => 'playlistgroups.default_image_file',
        ],
        'playlists' => [
            'type' => 'image',
            'default_value' => 'default.jpg',
            'old_key' => 'playlists.default_image_file',
        ],
        'reflections' => [
            'type' => 'image',
            'default_value' => 'default.jpg',
            'old_key' => 'reflections.default_image_file',
        ],
        'residents' => [
            'type' => 'image',
            'default_value' => 'default.jpg',
            'old_key' => 'residents.default_image_file',
        ],
        'subject_groups' => [
            'type' => 'image',
            'default_value' => 'default.jpg',
            'old_key' => 'subjectgroups.default_image_file',
        ],
        'subjects' => [
            'type' => 'image',
            'default_value' => 'default.jpg',
            'old_key' => 'subjects.default_image_file',
        ],
        'talks' => [
            'type' => 'image',
            'default_value' => 'default.jpg',
            'old_key' => 'talks.default_image_file',
        ],

    ],

    'home' => [

        'news_count' => [
            'type' => 'number',
            'default_value' => 3,
            'old_key' => 'home.news.count',
        ],

    ],

    'talks' => [

        'authors' => [

            'description' => [
                'type' => 'text',
                'default_value' => [
                    'text_en' => 'Dhamma talks by teacher',
                    'text_th' => null,
                ],
                'old_key' => [
                    'text_en' => 'talks.latest.authors.description_en',
                    'text_th' => 'talks.latest.authors.description_th',
                ],
            ],

            'image' => [
                'type' => 'image',
                'default_value' => 'images/talks/latest/authors.jpg',
                'old_key' => 'talks.latest.authors.image_file',
            ],
        ],

        'playlists' => [

            'description' => [
                'type' => 'text',
                'default_value' => [
                    'text_en' => 'Browse by groups of talks, retreats, chanting, readings',
                    'text_th' => null,
                ],
                'old_key' => [
                    'text_en' => 'talks.latest.playlists.description_en',
                    'text_th' =>  'talks.latest.playlists.description_th',
                ],
            ],

            'image' => [
                'type' => 'image',
                'default_value' => 'images/talks/latest/playlists.jpg',
                'old_key' => 'talks.latest.playlists.image_file',
            ],

        ],

        'latest' => [

            'alt_count' => [
                'type' => 'number',
                'default_value' => 3,
                'old_key' => 'talks.latest.alt.count',
            ],
            'alt_playlist_group' => [
                'type' => 'playlist_group',
                'default_value' => null,
                'old_key' => 'talks.latest.alt.playlist_group_id',
            ],
            'main_count' => [
                'type' => 'number',
                'default_value' => 3,
                'old_key' => 'talks.latest.main.count',
            ],
            'main_playlist_group' => [
                'type' => 'playlist_group',
                'default_value' => null,
                'old_key' => 'talks.latest.main.playlist_group_id',
            ],

        ],

        'subjects' => [

            'description' => [
                'type' => 'text',
                'default_value' => [
                    'text_en' => 'Browse by themes or topics discussed in the talk: metta, energy, mindfulness, etc.',
                    'text_th' => null,
                ],
                'old_key' => [
                    'text_en' => 'talks.latest.subjects.description_en',
                    'text_th' => 'talks.latest.subjects.description_th',
                ],
            ],
            'image' => [
                'type' => 'image',
                'default_value' => 'images/talks/latest/subjects.jpg',
                'old_key' => 'talks.latest.subjects.image_file',
            ],

        ],

    ],

];
