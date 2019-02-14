<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportType extends Model
{
	
    public static $data = [
      'weekly'            =>'Еженедельный обзор ВПО и ВТИ',
      'monthly'           => 'Ежемесячник по ВВСТ',
      'countrycatalog'    => 'Справочник по иностранным государствам',
      'yearly'            => 'Ежегодник по зарубежной военной технике',
      'plannedexhibition' => 'Перечень выставок ВВСТ на год',
      //'exhibition'        => 'Справочники к выставкам ВВСТ',
      'various'        => 'Иные материалы',
    ];

    protected $table = 'report_types';

    public function categories () {
        return $this->hasMany('App\Category');
    }

    public function reports () {
        return $this->hasMany('App\Report');
    }
}
