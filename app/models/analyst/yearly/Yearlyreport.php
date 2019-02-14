<?php

namespace App\models\analyst\yearly;

use Illuminate\Database\Eloquent\Model;

class Yearlyreport extends Model
{
    protected $fillable =[
      'published'
    ];

    public function categories (  ) {
        return $this->hasMany('App\models\analyst\yearly\Yearlycategory');
    }
	
    public function subcategories (  ) {
        return $this->hasMany('App\models\analyst\yearly\Yearlysubcategory');
    }
	
	public function articles () {
		$articles = [];

		if(!empty($this->categories)):
			foreach($this->categories as  $category):

				if(!empty($category->articles)):
					foreach($category->articles as  $catarticle):

						$articles[] = $catarticle;

					endforeach;
				endif;

				if(!empty($category->subcategories)):
					foreach($category->subcategories as  $subcategory):

						if(!empty($subcategory->articles)):
							foreach($subcategory->articles as  $article):

								$articles[] =  $article;

							endforeach;
						endif;
					endforeach;
				endif;
			endforeach;
		endif;

		return $articles;

    }

    public function articlesReport () {

	    return $this->hasMany('App\models\analyst\yearly\Yearlyarticle');

    }
	
    public function scopeActive($query)
    {
        return $query->where('published', 2);
    }
}
