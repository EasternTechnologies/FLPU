<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Country;
use App\Models\Personality;
use App\Models\VvtType;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function __construct () {
        $this->middleware('auth');
    }

    public function test () {
        $sessions = \Tracker::sessions(60 * 24);

        foreach ( $sessions as $session ) {
            if ( isset($session->user) ) {


                foreach ( $session->log as $log ) {
                    $users[ $session->user->email ][] = $log->path->path;


                }
            }
        }
        return view('admin.users', compact('users'));

    }

    public function showtags () {
        $countries     = Country::orderBy('title')->get();
        $companies     = Company::orderBy('title')->get();
        $vvt_types     = VvtType::orderBy('title')->get();
        $personalities = Personality::orderBy('title')->get();

        return compact('countries', 'companies', 'vvt_types', 'personalities');
    }

    public function filtertags ( Request $request ) {

        if(isset($request->tag) && !empty($request->tag))  {
            if($request->name_tag=='company'){
                $countries = Company::find($request->tag)->countries->pluck('id','title')->toArray();
                $vvt = Company::find($request->tag)->vvttypes->pluck('id','title')->toArray();
            }
            if($request->name_tag=='personalities'){
                $countries = Personality::find($request->tag)->countries->pluck('id','title')->toArray();
            }
            
            return compact('countries','vvt');
        }
            else {
                $countries   = Country::orderBy('title')->get();
                $country_id  = $request->input('countries');
                $vvt_type_id = $request->input('vvt_type');

                $companies     = Company::orderBy('title')->get();
                $personalities = Personality::orderBy('title')->get();

                if ( isset($country_id) && !empty($country_id) ) {
                    if ( is_array($country_id) ) {

                        $companies = collect();
                        $personalities = collect();

                        foreach($country_id as $country) {

                            $companies = $companies->merge(Country::find( $country )->companies()->get())->unique('id');

                        }


                        foreach($country_id as $country) {

                            $personalities = $personalities->merge(Country::find( $country )->personalities()->get())->unique('id');

                        }

                    }

                }

                if ( isset($vvt_type_id) && !empty($vvt_type_id) ) {
                    if ( is_array( $vvt_type_id ) ) {

                        $vvts = collect();
                        foreach ( $companies as $company ) {

                            $vvts = $vvts->merge( $company->vvttypes()->get());


                        }


                        $companies = collect();
                        foreach ( $vvt_type_id as $vvt_type ) {
                            foreach ( $vvts as $vvt ) {
                                if ( $vvt->pivot->vvt_type_id == $vvt_type ) {
                                    $companies = $companies->merge([$vvt->pivot->pivotParent])->unique('id');
                                }

                            }
                        }

                        $vvts = collect();
                        foreach ( $personalities as $personality ) {

                            $vvts = $vvts->merge( $personality->vvttypes()->get());


                        }

                        foreach ( $vvt_type_id as $vvt_type ) {
                            foreach ( $vvts as $vvt ) {
                                if ( $vvt->pivot->vvt_type_id == $vvt_type ) {
                                    $personalities = $personalities->merge([$vvt->pivot->pivotParent])->unique('id');
                                }
                            }
                        }
                    }
                }

                $vvt_types = VvtType::orderBy('title')->get();

                $country_id_array = Country::select(['id','title'])->get()->keyBy('id');
                $vvt_id_array = VvtType::select(['id','title'])->get()->keyBy('id');

                return compact('countries', 'companies', 'vvt_types', 'personalities', 'country_id','country_id_array','vvt_id_array');
            }
    }

    public function search_country(Request $request)
    {

        $countries     = Country::where('title', 'like', $request->search_country."%")->orderBy('title')->get();
        $companies     = Company::where('title', 'like', $request->search_company."%")->orderBy('title')->get();
        $vvt_types     = VvtType::where('title', 'like', $request->search_vvt."%")->orderBy('title')->get();
        $personalities = Personality::where('title', 'like', "%".$request->search_person."%")->orderBy('title')->get();


        return compact('countries', 'companies', 'vvt_types', 'personalities');
    }
}
