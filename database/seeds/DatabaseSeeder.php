<?php

use Illuminate\Database\Seeder;

/**
 * After making changes to the seeds, you may need to regenerate Composer's
 * autoloader using the command:
 *
 *     composer dump-autoload
 *
 * Then you can create the seeds with:
 *
 *     php artisan db:seed
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SettingsTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(AuthorsTableSeeder::class);

        $this->call(PhotosTableSeeder::class);
        $this->call(AlbumsTableSeeder::class);
        $this->call(AlbumPhotoTableSeeder::class);

        $this->call(ContactOptionsTableSeeder::class);
        $this->call(BooksTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(ReflectionsTableSeeder::class);
        $this->call(ResidentsTableSeeder::class);
        $this->call(SubpagesTableSeeder::class);
        $this->call(TalesTableSeeder::class);

        $this->call(PlaylistGroupsTableSeeder::class);
        $this->call(PlaylistsTableSeeder::class);
        $this->call(SubjectGroupsTableSeeder::class);
        $this->call(SubjectsTableSeeder::class);

        $this->call(TalksTableSeeder::class);
        $this->call(PlaylistTalkTableSeeder::class);
    }
}
