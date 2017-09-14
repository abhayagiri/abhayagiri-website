<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Author;
use App\Models\Book;
use App\Models\Language;
use App\Models\Redirect;
use App\Markdown;
use App\Util;

class MigrateBooksData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('old_books')->get()
                ->each(function($book, $key) {

            $language = Language::where('title_en', $book->language)->firstOrFail();

            if ($book->author) {
                $author = Author::where('title_en', $book->author)->firstOrFail();
            } else {
                $author = Author::where('slug', 'abhayagiri-sangha')->firstOrFail();
            }

            if ($book->author2) {
                $author2 = Author::where('title_en', $book->author2)->firstOrFail();
            } else {
                $author2 = null;
            }

            $description = Markdown::fromHtml($book->body);
            $subtitle = $book->subtitle;

            if ($subtitle == '' || $subtitle == $description) {
                $subtitle = null;
            }

            if (ends_with($book->url_title, 'thai') ||
                    str_contains($book->url_title, '%u0E')) {
                $descriptionEn = null;
                $descriptionTh = $description;
            } else {
                $descriptionEn = $description;
                $descriptionTh = null;
            }

            $imagePath = function($orig) {
                return $orig ? 'images/books/' . $orig : null;
            };

            $bookPath = function($orig) {
                return $orig ? 'books/' . $orig : null;
            };

            $date = Util::createDateFromPacificDb($book->date);

            $status = strtolower($book->status);
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

            Book::forceCreate([
                'id' => $book->id,
                'language_id' => $language->id,
                'author_id' => $author->id,
                'author2_id' => $author2 ? $author2->id : null,
                'title' => $book->title,
                'subtitle' => $subtitle,
                'description_en' => $descriptionEn,
                'description_th' => $descriptionTh,
                'check_translation' => true,
                'weight' => $book->weight ? $book->weight : null,
                'image_path' => $imagePath($book->cover),
                'pdf_path' => $bookPath($book->pdf),
                'epub_path' => $bookPath($book->epub),
                'mobi_path' => $bookPath($book->mobi),
                'request' => $book->request == 'Print Copy',
                'draft' => $draft,
                'published_on' => $date->toDateString(),
                'posted_at' => $date,
                'deleted_at' => $deletedAt,
            ]);

            Redirect::createFromOld('books/' . $book->url_title, [
                'type' => 'Book',
                'id' => $book->id,
            ]);
        });

        DB::table('pages')
            ->where('title', '=', 'Books')
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
            ->where('title', '=', 'Books')
            ->update(['mahapanel' => 'yes']);

        DB::table('books')->delete();
        DB::table('redirects')
            ->where('from', 'LIKE', 'books/%')
            ->orWhere('from', 'LIKE', 'th/books/%')
            ->delete();
    }
}
