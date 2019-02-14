<?php


namespace App\models\analyst\exhibitions;

use Illuminate\Database\Eloquent\Model;

class Plannedexhibitionyear extends Model
{
	
	protected $fillable = [
        'published',
    ];
	  
    public function plannedexhibitions (  ) {
        return $this->hasMany('App\models\analyst\exhibitions\Plannedexhibition');
    }
    public function scopeActive($query)
    {
        return $query->where('published', 2);
    }
}
