Most of the tests in the directory were automatically created using the
following generator commands:

```sh
php artisan app:make:admin-test Author
php artisan app:make:admin-test Book
php artisan app:make:admin-test Danalist -r danalist -d 'dana wishlist'
php artisan app:make:admin-test Language
php artisan app:make:admin-test News
php artisan app:make:admin-test ContactOption
php artisan app:make:admin-test Playlist
php artisan app:make:admin-test PlaylistGroup
php artisan app:make:admin-test Reflection
php artisan app:make:admin-test Resident
php artisan app:make:admin-test Subject
php artisan app:make:admin-test SubjectGroup
php artisan app:make:admin-test Subpage
php artisan app:make:admin-test Talk
```

The template file is located at `app/Console/Commands/stubs/admin-test.stub`.
