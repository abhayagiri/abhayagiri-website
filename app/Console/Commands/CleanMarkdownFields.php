<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanMarkdownFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-markdown-fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean markdown fields in the database';

    /**
     * The models and fields to check.
     *
     * @var array
     */
    protected $models = [
        'Album' => ['description_en', 'description_th'],
        'Book' => ['description_en', 'description_th'],
        'ContactOption' => ['body_en', 'body_th', 'confirmation_en', 'confirmation_th'],
        'Danalist' => ['summary_en', 'summary_th'],
        'News' => ['body_en', 'body_th'],
        'Playlist' => ['description_en', 'description_th'],
        'PlaylistGroup' => ['description_en', 'description_th'],
        'Resident' => ['description_en', 'description_th'],
        'Reflection' => ['body'],
        'Subject' => ['description_en', 'description_th'],
        'SubjectGroup' => ['description_en', 'description_th'],
        'Subpage' => ['body_en', 'body_th'],
        'Talk' => ['description_en', 'description_th'],
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->models as $className => $fields) {
            $fullClassName = "\\App\\Models\\$className";
            $query = $fullClassName::orderBy('id');
            if (method_exists($query, 'withTrashed')) {
                $query->withTrashed();
            }
            $query->chunk(100, function($objects) use ($className, $fields) {
                foreach ($objects as $object) {
                    $this->checkObject($className, $object, $fields);
                }
            });
        }
    }

    private function checkObject($className, $object, $fields)
    {
        $changed = false;
        foreach ($fields as $field) {
            $original = $object->$field;
            $object->$field = $original;
            if ($original !== $object->$field) {
                if (!$changed) {
                    print "UPDATING $className({$object->id})\n";
                }
                print "  ORIG $field = $original\n";
                print "   NEW $field = {$object->$field}\n";
                $changed = true;
            }
        }
        if ($changed) {
            $object->save();
            print "---\n";
        }
    }

}
