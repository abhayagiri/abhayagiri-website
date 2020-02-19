<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class ContactOption extends Model
{
    use CrudTrait;
    use Traits\LocalDateTimeTrait;
    use Traits\MarkdownHtmlTrait;
    use Traits\RevisionableTrait;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'published' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['body_html_en', 'body_html_th', 'confirmation_html_en', 'confirmation_html_th'];
}
