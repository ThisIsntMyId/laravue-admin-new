<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $guarded = [];

    /**
     * Scope a query to only include cards whose name contain given name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  number  $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    /**
     * Scope a query to only include cards whose description contain given text.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  number  $text
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByDescription($query, $text)
    {
        return $query->where('description', 'like', '%' . $text . '%');
    }

    /**
     * Scope a query to only include cards whose price is between given range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  number  $range
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByPrice($query, $range)
    {
        return $query->whereBetween('price', $range);
    }

    /**
     * Scope a query to only include cards whose category falls in the given array.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  number  $array
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByCategory($query, $array)
    {
        return $query->whereIn('category', $array);
    }
}
