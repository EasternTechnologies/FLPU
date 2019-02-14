<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class VvtType extends Model

{
    protected $fillable= ['title'];


    public function articles () {

        return $this->belongsToMany('App\ArticleReports');

    }

    public function companies () {

        return $this->belongsToMany('App\models\tags\Company');

    }
}
