<?php

namespace App\Models\Concerns;

/**
 * Stores user-facing text as {"es": "...", "en": "..."} and reads it in the active locale,
 * falling back to Spanish when the English copy has not been written yet.
 */
trait HasLocalizedFields
{
    public function localized(string $attribute, ?string $locale = null): mixed
    {
        $values = $this->getAttribute($attribute);

        if (! is_array($values)) {
            return $values;
        }

        $locale ??= app()->getLocale();

        return filled($values[$locale] ?? null) ? $values[$locale] : ($values['es'] ?? null);
    }
}
