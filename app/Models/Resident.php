<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;

class Resident extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use Traits\LocalDateTimeTrait;
    use Traits\ImageCrudColumnTrait;
    use Traits\ImagePathTrait;
    use Traits\MarkdownHtmlTrait;
    use Traits\MediaPathTrait;
    use Traits\RevisionableTrait;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'check_translation' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['description_html_en', 'description_html_th',
        'image_url', 'body_html_en', 'body_html_th', 'breadcrumbs', 'subnav'];

    /**
     * The attributes that should not be revisioned.
     *
     * @var array
     */
    protected $dontKeepRevisionOf = [
        'check_translation', 'deleted_at',
    ];

    /**
     * Override to store the creation as a revision
     *
     * @var bool
     */
    protected $revisionCreationsEnabled = true;

    /**
     * The friendly name for revisions.
     *
     * @return string
     */
    public function identifiableName()
    {
        return $this->title_en;
    }

    /*
     * Scopes *
     */

    public function scopeCurrent($query)
    {
        return $query
            ->where('residents.status', 'current');
    }

    public function scopeTraveling($query)
    {
        return $query
            ->where('residents.status', 'traveling');
    }

    /*
     * Accessors and Mutators *
     */

    public function getBodyHtmlEnAttribute()
    {
        return View::make('subpages/resident', [
            'resident' => $this,
            'lng' => 'en',
        ])->render();
    }

    public function getBodyHtmlThAttribute()
    {
        return View::make('subpages/resident', [
            'resident' => $this,
            'lng' => 'th',
        ])->render();
    }

    public function getPathAttribute()
    {
        return $this->getPath(Lang::locale());
    }

    public function getBreadcrumbsAttribute()
    {
        $breadcrumbs = Subpage::where('page', 'community')
            ->where('subpath', 'residents')
            ->firstOrFail()
            ->breadcrumbs;
        $breadcrumbs[0]->last = false;
        $breadcrumbs->push((object) [
            'title_en' => $this->title_en,
            'title_th' => $this->title_th,
            'path' => $this->path,
            'last' => true,
        ]);
        return $breadcrumbs;
    }

    public function getSubnavAttribute()
    {
        return Subpage::where('page', 'community')
            ->where('subpath', 'residents')
            ->firstOrFail()
            ->subnav;
    }

    /*
     * Other *
     */

    public function getPath($lng = 'en')
    {
        return ($lng === 'th' ? '/th' : '') .
            '/community/residents/' . $this->slug;
    }

    public static function getMacroAllHtml($lng)
    {
        $current = Resident::current()->orderBy('rank')->orderBy('title_en')->get();
        $traveling = Resident::traveling()->orderBy('rank')->orderBy('title_en')->get();
        return View::make('subpages/residents', [
            'current' => $current,
            'traveling' => $traveling,
            'lng' => $lng,
        ])->render();
    }

    public static function getMacroSingleHtml($id, $lng)
    {
        $resident = self::where('slug', $id)->first();
        if (!$resident) {
            $resident = self::find($id);
        }
        if ($resident) {
            return View::make('subpages/resident', [
                'resident' => $resident,
                'lng' => $lng,
            ])->render();
        } else {
            return '<p>No such resident: ' . e($id) . '</p>';
        }
    }
}
