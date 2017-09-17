<?php

namespace App\Models\Traits;

/**
 * Classes using this trait should set the $slugFrom property.
 */
trait AutoSlugTrait
{
    /**
     * The attribute or method that derives the slug.
     *
     * @var string
     */
    //protected $slugFrom = 'title';

    /**
     * The slug attribute.
     *
     * @var array
     */
    protected $slugAttribute = 'slug';

    /**
     * Sets (and returns) the slug.
     *
     * @return string
     */
    public function setSlug()
    {
        if (method_exists($this, $this->slugFrom)) {
            $slug = $this->{$this->slugFrom}();
        } else {
            $value = $this->getAttribute($this->slugFrom);
            $slug = $value ? str_slug($value) : null;
        }
        $this->setAttribute($this->slugAttribute, $slug);
        return $slug;
    }

    /**
     * Helper method making slugs with models with title and alt_title_en.
     * This uses alt_title_en for the slug, otherwise it uses title.
     *
     * @return string
     */
    protected function getSlugFromTitleAndAltTitleEn()
    {
        $slugFromTitle = str_slug($this->getAttribute('title'));
        $slugFromAltTitleEn = str_slug($this->getAttribute('alt_title_en'));
        return $slugFromAltTitleEn ? $slugFromAltTitleEn :
            ($slugFromTitle ? $slugFromTitle : 'unknown');
    }

    /**
     * Boot the auto slug trait for a model.
     *
     * @return void
     */
    public static function bootAutoSlugTrait()
    {
        static::saving(function ($model) {
            $model->setSlug();
        });
    }
}
