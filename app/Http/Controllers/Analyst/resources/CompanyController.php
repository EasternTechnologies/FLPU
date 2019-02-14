<?php

namespace App\Http\Controllers\Analyst\resources;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\VvtType;
use App\models\analyst\monthly\Monthlyarticle;
use App\models\analyst\various\Variousarticle;
use App\models\analyst\weekly\Weeklyarticle;
use App\models\analyst\yearly\InfoCountry;
use App\models\analyst\yearly\Yearlyarticle;
use App\models\analyst\exhibitions\Plannedexhibition;

class CompanyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index () {
        return Company::all();
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

        if ( Company::where('title', $request->input('title'))->count() != 0 ) {

            $error = 'Такой тег уже существует';
            return ['error'=>$error];
            die();

        }

        $company = Company::create($request->except('_token'));
        $country = Country::find($request->input('countries'));
	    $vvt_tag = VvtType::find($request->input('vvt_tag'));
        $company->countries()->attach($country);
	    $company->vvttypes()->attach($vvt_tag);

	    if($request->input('article')) {

		    $article->companies()->attach($company);

		    $array_company = $article->companies->pluck('id');

		    $new_company = collect($company->id);

		    $response = $array_company->merge($new_company);

		    return json_encode($response);
	    }

	    return json_encode([$company->id]);

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
	    $tag = Company::find($id);
	    $tag->title = $request->data['title'];
		$tag->addCountries($request->data['countries']);
		$tag->addVvt($request->data['vvt_tag']);
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
        $tag = Company::find($id);
        $tag->delete();
    }
}
