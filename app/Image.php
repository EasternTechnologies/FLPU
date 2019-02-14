<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    protected $fillable = [
        'image',
        'thumbnail',
    ];

    public function articles (  ) {

	    return $this->belongsTo('App\ArticleReports');
    }
}
