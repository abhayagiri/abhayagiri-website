<?php

namespace App\Models\Traits;

/**
 * This provides models with a localized path attribute.
 */
trait HasAltTitle
{
    /**
     * Return title_with_alt_en accessor.
     *
     * @return string|null
     */
     public function getTitleWithAltEnAttribute(): ?string
     {
         $title = $this->getAttribute('title');
         if ($title !== null) {
             if ($altEn = $this->getAttribute('alt_title_en')) {
                 $title .= ' (' . $altEn . ')';
             }
         }
         return $title;
     }

     /**
      * Return title_with_alt_th accessor.
      *
      * @return string|null
      */
      public function getTitleWithAltThAttribute(): ?string
      {
          $title = $this->getAttribute('title');
          if ($title !== null) {
              if ($altTh = $this->getAttribute('alt_title_th')) {
                  $title .= ' (' . $altTh . ')';
              }
          }
          return $title;
      }
}
