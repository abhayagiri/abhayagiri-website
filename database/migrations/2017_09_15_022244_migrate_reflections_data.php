<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Markdown;
use App\Models\Author;
use App\Models\Language;
use App\Models\Redirect;
use App\Models\Reflection;
use App\Util;

class MigrateReflectionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $english = Language::where('code', 'en')->firstOrFail();

        DB::table('old_reflections')->get()
                ->each(function($reflection) use ($english) {

            $author = Author::where('title_en', $reflection->author)
                ->first();
            if (!$author) {
                $author = Author::where('title_en', 'Abhayagiri Sangha')
                    ->firstOrFail();
                echo "Could not find author {$reflection->author} for " .
                    "reflection {$reflection->id}\n";
            }

            $body = $reflection->body;
            $body = Markdown::fromHtml($body);

            $body = preg_replace('/<!--.*?-->/s', '', $body);

            $date = Util::createDateFromPacificDb($reflection->date);

            $status = strtolower($reflection->status);
            if ($status == 'draft') {
                $draft = true;
                $deletedAt = null;
            } else if ($status == 'closed') {
                $draft = false;
                $deletedAt = $date;
            } else {
                $draft = false;
                $deletedAt = null;
            }

            Reflection::forceCreate([
                'id' => $reflection->id,
                'language_id' => $english->id,
                'author_id' => $author->id,
                'title' => $reflection->title,
                'body' => $body,
                'check_translation' => true,
                'image_path' => null,
                'draft' => $draft,
                'posted_at' => $date,
                'deleted_at' => $deletedAt,
            ]);

            try {
                Redirect::createFromOld(
                    'reflections/' . $reflection->url_title, [
                    'type' => 'Reflection',
                    'id' => $reflection->id,
                ]);
            } catch (\Exception $e) {
                echo "Could not create redirect for {$reflection->id}" .
                    "/reflections/{$reflections->url_title}\n";
            }
        });

        DB::table('pages')
            ->where('title', '=', 'Reflections')
            ->update(['mahapanel' => 'no']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('pages')
            ->where('title', '=', 'Reflections')
            ->update(['mahapanel' => 'yes']);

        DB::table('reflections')->delete();
        DB::table('redirects')
            ->where('from', 'LIKE', 'reflections/%')
            ->orWhere('from', 'LIKE', 'th/reflections/%')
            ->delete();
    }
}
