<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
    public function states() {
        return $this->hasMany(State::class);
    }

    /**
     * @param Builder $query
     * @param string $search
     * @return mixed
     */
    public function scopeOfSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where("name", "LIKE", "%$search%");
    }

     /**
     * @param Builder $query
     * @param string $id
     * @return mixed
     */
    public function scopeCountryName($query, $id)
    {
       

        return $query->where("id",$id);
    }

}
