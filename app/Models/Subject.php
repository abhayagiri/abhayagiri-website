<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Backpack\CRUD\CrudTrait;

use App\Models\Talk;

class Subject extends Model
{
    use CamelCaseTrait;
    use ImageUrlTrait;
	use CrudTrait;
    use IconTrait;
    use DescriptionTrait;

	protected $fillable = ['slug', 'group_id', 'title_en', 'title_th',
        'description_en', 'description_th', 'check_translation', 'image_path',
        'rank', 'created_at', 'updated_at'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('titleOrder', function (Builder $builder) {
            $builder->orderBy('title_en');
        });
    }

    /**
     * Get parent subject group.
     */
    public function group()
    {
        return $this->belongsTo('App\Models\SubjectGroup');
    }

    /**
     * Get the related talks IDs.
     */
    public function getTalkIds()
    {
        $talkIds = DB::table('subject_tag')
            ->join('tag_talk', 'tag_talk.tag_id', '=', 'subject_tag.tag_id')
            ->where('subject_tag.subject_id', '=', $this->id)
            ->pluck('tag_talk.talk_id');
        return $talkIds;
    }

    /**
     * Get the related tags.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array = $this->convertDescriptionsToHtml($array);
        $array = $this->camelizeArray($array);
        $array = $this->addImageUrl($array);
        return $array;
    }
}
