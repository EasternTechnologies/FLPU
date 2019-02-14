<?php

namespace App\Http\Controllers\Analyst\resources;

use App\Http\Controllers\Controller;
use App\models\analyst\exhibitions\Plannedexhibition;
use App\models\analyst\monthly\Monthlyarticle;
use App\models\analyst\various\Variousarticle;
use App\models\analyst\weekly\Weeklyarticle;
use App\models\analyst\yearly\InfoCountry;
use App\models\analyst\yearly\Yearlyarticle;
use App\Models\Country;
use App\Models\Personality;
use App\Models\VvtType;
use Illuminate\Http\Request;

class PersonalitiesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index () {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create () {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store ( Request $request ) {

	    switch ($request->input('report')) {
		    case 'Yearlyarticle':
			    $article = Yearlyarticle::find($request->input('article'));
			    break;
		    case 'Weeklyarticle':
			    $article = Weeklyarticle::find($request->input('article'));
			    break;
		    case 'Variousarticle':
			    $article = Variousarticle::find($request->input('article'));
			    break;
		    case 'Plannedexhibition':
			    $article = Plannedexhibition::find($request->input('article'));
			    break;
		    case 'Monthlyarticle':
			    $article = Monthlyarticle::find($request->input('article'));
			    break;
		    case 'InfoCountry':
			    $article = InfoCountry::find($request->input('article'));
			    break;
	    }

        if ( Personality::where('title', $request->input('title'))->count() != 0 ) {

            $error = 'Такой тег уже существует';
            return ['error'=>$error];
            die();

        }
        
        $personality = Personality::create($request->except('_token'));
        $country = Country::find($request->input('countries'));
        $vvt_tag = VvtType::find($request->input('vvt_tag'));
        $personality->countries()->attach($country);
	    $personality->vvttypes()->attach($vvt_tag);

	    if($request->input('article')) {

		    $article->personalities()->attach($personality);

		    $array_personality = $article->personalities->pluck('id');

		    $new_personality = collect($personality->id);

		    $response = $array_personality->merge($new_personality);

		    return json_encode($response);

	    }


	    return json_encode([$personality->id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show ( $id ) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit ( $id ) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update ( Request $request, $id )
    {
	    $tag = Personality::find($id);
		$tag->title = $request->data['title'];
		$tag->addCountries($request->data['countries']);
	    $tag->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy ( $id ) {
        $tag = Personality::find($id);
		$tag->delete();
    }
}
