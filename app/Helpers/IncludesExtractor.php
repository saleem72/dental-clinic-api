<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

use function PHPUnit\Framework\isEmpty;

class IncludesExtractor {
    public static function extract(array $allowedIncludes, array $relationsFromRequest, Collection $replacements): array
    {
         $requestedIncludes = [];

        $trimmedRelations = array_map('trim', $relationsFromRequest);

        $requestedIncludes = array_filter($trimmedRelations, function ($relation) use ($allowedIncludes) {
            return in_array($relation, $allowedIncludes);
        });

        // if (!isEmpty($replacements)) {
        //     foreach ($replacements as $key => $value) {
        //         replace_value_in_array($requestedIncludes, $key, $value);
        //     }
        // }

        // Use Collection methods for cleaner replacement logic
        if (!$replacements->isEmpty()) {
            $requestedIncludes = collect($requestedIncludes)->map(function ($include) use ($replacements) {
                // If the replacement key exists, use the replacement value, otherwise use the original include name
                return $replacements->get($include, $include);
            })->toArray();
        }

        return $requestedIncludes;
    }
}

