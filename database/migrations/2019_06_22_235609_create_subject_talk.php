<?php

use App\Models\Talk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectTalk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_talk', function (Blueprint $table) {
            $table->unsignedInteger('subject_id');
            $table->unsignedInteger('talk_id');
            $table->timestamps();
            $table->unique(['subject_id', 'talk_id']);
            $table->foreign('subject_id')->references('id')
                ->on('subjects')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('talk_id')->references('id')
                ->on('talks')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        DB::table('talks')->get()->each(function($talk) {

            $subjectIds = DB::table('subjects')->select('subjects.id')
                ->join('subject_tag', 'subjects.id', '=', 'subject_tag.subject_id')
                ->join('tag_talk', 'subject_tag.tag_id', '=', 'tag_talk.tag_id')
                ->where('tag_talk.talk_id', '=', $talk->id)
                ->distinct('subjects.id')->orderBy('subjects.id')
                ->get()->pluck('id');

            // The following is only used for verification.
            $oldSubjectIds = Talk::withTrashed()->find($talk->id)->oldSubjects()
                                                ->get()->pluck('id')
                                                ->sort()->values();
            if ($subjectIds != $oldSubjectIds) {
                print("Different subject IDs for talk {$talk->id}\n");
                dump([$subjectIds, $oldSubjectIds]);
            }

            DB::table('subject_talk')->insert(
                $subjectIds->map(function($subjectId) use ($talk) {
                    return [
                        'subject_id' => $subjectId,
                        'talk_id' => $talk->id,
                    ];
                })->toArray()
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subject_talk');
    }
}
