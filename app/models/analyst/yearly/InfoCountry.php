<?php

namespace App\models\analyst\yearly;

use App\models\imodel;
use App\SearchableTrait;

class InfoCountry extends imodel
{
    protected $fillable =['published', 'title', 'overview'];

    use SearchableTrait;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
          'info_countries.title' => 10,
          'info_countries.general_data' => 10,
          'info_countries.expenses' => 10,
          'info_countries.situation' => 10,
          'info_countries.military_structure' => 10,
          'info_countries.major_types_vvt' => 10,
          'info_countries.military_industry' => 10,
          'info_countries.military_technical_cooperation' => 10,
          'info_countries.overview' => 100,

        ],
    ];

    public function region (  ) {
        return $this->belongsTo('App\models\analyst\yearly\Region');
   }
    public function images (  ) {
        return $this->hasMany('App\models\analyst\yearly\Infocountryimages');
    }
    public function users (  ) {
        return $this->belongsTo('App\User');
    }
    public function scopeActive($query)
    {
        return $query->where('published', 2);
    }

}
