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
        DB::statement('DELETE FROM books');
        DB::statement('DELETE FROM reflections');
        DB::statement('DELETE FROM subject_talk');
        DB::statement('DELETE FROM talks');
        DB::statement('DELETE FROM authors');
        DB::statement('DELETE FROM tag_talk');
        DB::statement('DELETE FROM subject_tag');
        DB::statement('DELETE FROM tags');
        DB::statement('DELETE FROM subjects');
        DB::statement('DELETE FROM subject_groups');
        DB::statement('DELETE FROM languages');
        $this->call(LanguageTableSeeder::class);
        $this->call(AuthorTableSeeder::class);
        $this->call(TalkTableSeeder::class);
        $this->call(SubjectGroupTableSeeder::class);
        $this->call(SubjectTableSeeder::class);
        $this->call(TagTableSeeder::class);
    }
}
