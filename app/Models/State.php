<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @param Builder $query
     * @param string $search
     * @return mixed
     */
    public function scopeOfSearch($query, $search)
    {
        return $query->where("name", "LIKE", "%$search%");
    }

    /**
     * Scope a query to get states by country id.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $countryId integer
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    public static function getAll($countryId = null, $search = null)
    {
        /** @var State|\Illuminate\Database\Eloquent\Builder $query */
        $query = State::query();
        $query->ofCountry($countryId);
        $query->ofSearch($search);

        $states = $query->get();

        return $states;
    }
     /**
     * @param Builder $query
     * @param string $id
     * @return mixed
     */
    public function scopeStateName($query, $id)
    {
       

        return $query->where("id",$id);
    }
}
