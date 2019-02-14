<?php

namespace App\models\analyst\weekly;

use Illuminate\Database\Eloquent\Model;
use App\models\imodel;
use App\SearchableTrait;

class Weeklyarticle extends imodel
{
    protected $fillable = [
      'body',
      'title',
      'year',
      'month',
      'week',
      'start_period',
      'end_period',
      'published',
      'weeklyreport_id',
      'category_id'
    ];

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
          'weeklyarticles.title' => 10,
          'weeklyarticles.body' => 10,

        ],
    ];


    public function weeklyreport () {
        return $this->belongsTo('App\models\analyst\weekly\Weeklyreport');
    }
    public function category (  ) {
        return $this->belongsTo('App\Category');
    }
    public function subcategory (  ) {
        return $this->belongsTo('App\Subcategory');
    }
    public function users (  ) {
        return $this->belongsTo('App\User');
    }
    public function images (  ) {
        return $this->hasMany('App\models\analyst\weekly\Weeklyimage');
    }
}
