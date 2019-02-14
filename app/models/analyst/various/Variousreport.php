<?php

namespace App\models\analyst\various;

use Illuminate\Database\Eloquent\Model;

class Variousreport extends Model
{

    protected $fillable = [
      'published',
    ];

    public function categories () {
        return $this->hasMany('App\models\analyst\various\Variouscategory');
    }

    public function subcategories () {
        return $this->hasMany('App\models\analyst\various\Varioussubcategory');
    }

    public function articles () {

//        $articles = [];
//
//        if ( !empty($this->categories) ):
//            foreach ( $this->categories as $category ):
//
//                if ( !empty($category->articles) ):
//                    foreach ( $category->articles as $catarticle ):
//
//                        $articles[] = $catarticle;
//
//                    endforeach;
//                endif;
//
//                if ( !empty($category->subcategories) ):
//                    foreach ( $category->subcategories as $subcategory ):
//
//                        if ( !empty($subcategory->articles) ):
//                            foreach ( $subcategory->articles as $article ):
//
//                                $articles[] = $article;
//
//                            endforeach;
//                        endif;
//                    endforeach;
//                endif;
//            endforeach;
//
//        return $articles;

		return $this->hasMany('App\models\analyst\various\Variousarticle');

    }

    public function scopeActive ( $query ) {
        return $query->where('published', 2);
    }
}
