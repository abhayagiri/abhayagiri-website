<?php

namespace App\Models\Setting;

use Illuminate\Support\Str;

trait LocalizedText
{
    /**
     * Getter for text.
     *
     * @return string|null
     */
    public function getTextAttribute(): ?string
    {
        return tp($this, 'text');
    }

    /**
     * Getter for text_en.
     *
     * @return string|null
     */
    public function getTextEnAttribute(): ?string
    {
        return $this->getValueByKey('text_en');
    }

    /**
     * Getter for text_th.
     *
     * @return string|null
     */
    public function getTextThAttribute(): ?string
    {
        return $this->getValueByKey('text_th');
    }

    /**
     * Setter for text_en.
     *
     * @param  string|null  $value
     *
     * @return string|null
     */
    public function setTextEnAttribute(?string $value): ?string
    {
        return $this->setValueByKey('text_en', $value);
    }

    /**
     * Setter for text_th.
     *
     * @param  string|null  $value
     *
     * @return string|null
     */
    public function setTextThAttribute(?string $value): ?string
    {
        return $this->setValueByKey('text_th', $value);
    }

    /**
     * Return the value array by key.
     *
     * @param  string  $key;
     *
     * @return null|string;
     */
    protected function getValueByKey(string $key): ?string
    {
        return $this->value[$key] ?? null;
    }

    /**
     * Set the value array by key.
     *
     * @param  string  $key;
     * @param  string|null  $value;
     *
     * @return null|string;
     */
    protected function setValueByKey(string $key, ?string $value): ?string
    {
        $valueArray = is_array($this->value) ? $this->value : [];
        $valueArray[$key] = $value;
        $this->value = $valueArray;
        return $value;
    }
}
