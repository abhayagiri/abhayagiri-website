<?php

/**
 * Get the _en or _th property of a model depending on the current locale.
 *
 * @param object $model
 * @param string $attribute
 * @return string
 */
function tp($model, $attribute, $lng = null) {
    if (!$lng) {
        $lng = Lang::locale();
    }
    $value = $model->{$attribute . '_' . $lng};
    if (!$value && $lng !== 'en') {
        $value = $model->{$attribute . '_en'};
    }
    return $value;
}
