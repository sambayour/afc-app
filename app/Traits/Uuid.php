<?php

namespace App\traits;
use Illuminate\Support\Str;

trait uuid{
    protected static function boot()
    {
        // Boot other traits on the Model
        parent::boot();

        static::creating(function ($model) {
            // Check if the primary key doesn't have a value
            if (!$model->getKey()) {
                // Dynamically set the primary key
                $model->setAttribute($model->getKeyName(), Str::uuid()->toString());
            }
        });
    }

    // Tells Eloquent Model not to auto-increment this field
    public function getIncrementing()
    {
        return false;
    }

    // Tells that the IDs on the table should be stored as strings
    public function getKeyType()
    {
        return 'string';
    }
}
