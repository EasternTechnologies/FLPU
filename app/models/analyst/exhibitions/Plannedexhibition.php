<?php

namespace App\models\analyst\exhibitions;

use App\models\imodel;
use App\SearchableTrait;


class Plannedexhibition extends imodel
{
    protected $fillable =['title',
      'region',
      'country',
      'place',
      'start',
      'fin',
      'theme',
      'description',
        'published'
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
          'plannedexhibitions.title' => 10,
          'plannedexhibitions.theme' => 10,
          'plannedexhibitions.description' => 10,

        ],
    ];


    public function images (  ) {
        return $this->hasMany('App\models\analyst\exhibitions\Plannedexhibitionimage');
    }

	public function plannedexhibitionyear (  ) {
	        return $this->belongsTo('App\models\analyst\exhibitions\Plannedexhibitionyear');
	    }

	public function titleTags ( $title ) {

		$this->title = strip_tags($title);

		return $this->title ;
	}

    public function scopeActive($query)
    {
        return $query->where('published', 2);
    }
}
