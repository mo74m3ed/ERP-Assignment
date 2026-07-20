<?php

namespace App\Helpers;

use Collator;

class LocaleHelper
{
    private static Collator|false|null $collator = null;

    /**
     * Compare two strings using locale-aware collation when available,
     * falling back to strnatcasecmp() if the intl Collator cannot be created.
     *
     * Mirrors FreshRSS_Context::localeCompare() from FreshRSS/FreshRSS#8985.
     */
    public static function localeCompare(string $a, string $b): int
    {
        if (self::$collator === null) {
            $locale = app()->getLocale();
            if ($locale === '' || ! class_exists(Collator::class)) {
                self::$collator = false;
            } else {
                self::$collator = Collator::create($locale) ?? false;
            }
            if (self::$collator instanceof Collator) {
                self::$collator->setAttribute(Collator::NUMERIC_COLLATION, Collator::ON);
            }
        }

        if (! (self::$collator instanceof Collator)) {
            return strnatcasecmp($a, $b);
        }

        $result = self::$collator->compare($a, $b);

        return $result === false ? strnatcasecmp($a, $b) : $result;
    }

    /**
     * Reset the cached collator (useful in tests when the locale changes).
     */
    public static function resetCollator(): void
    {
        self::$collator = null;
    }
}
