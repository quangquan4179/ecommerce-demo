<?php

namespace App\Traits;

use App\Exceptions\InvalidEnumValue;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

trait ValidateEnumTrait
{
    public function validateEnum(array $values)
    {
        return Attribute::make(
            get: function ($value) {
                if (is_string($value)) {
                    return Str::of($value)->rtrim();
                }
                return $value;
            },
            set:function ($value) use ($values) {
                if (collect($values)->contains($value)) {
                    return $value;
                }
                throw new InvalidEnumValue($value, $values);
            }
        );
    }
}
