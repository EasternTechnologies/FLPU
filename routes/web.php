<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/reglament', function()
{
    return view('reglament');
});

/*
 * Tags routes
 * */
Route::get('/tags', 'IndexController@showtags');
Route::post('/tags', 'IndexController@filtertags');


/*
 *
 * User routes
 *
 */
Route::get('/cabinet/{user}', 'User\HomeController@cabinet')->name('cabinet');

Route::middleware('checkuser')->group(function()
{
	Route::get('/', 'User\HomeController@index')->name('home');
	Route::get('/report', 'User\HomeController@index')->name('home');
    Route::post('/api/search', 'User\HomeController@apisearch');
    Route::get('/search/form', 'User\HomeController@advanced_search_form');
	Route::post('/search/choose','User\HomeController@search_choose');
	Route::get('/search/{q?}', 'User\HomeController@advanced_search');
    Route::post('/search', 'User\HomeController@advanced_search');
    Route::get('/simply_search', 'User\HomeController@search');
	Route::post('/simply_search', 'User\HomeController@search');

    /*Bug*/
	Route::post('/bug', 'User\HomeController@bug')->name('bug');

});

Route::middleware('checkuser')->prefix('/report/{slug}')->group(function()
{
	/*Report*/
	Route::get('/', 'Report\ReportController@report_list');
	Route::get('/show/{report}/', 'Report\ReportController@report_show')->name('show_report');
	Route::get('/article/{article}', 'Report\ReportController@item_article');

    Route::post('/show/{report}/{q}', 'Report\ReportController@report_show');
});

/*
 * Analyst routes
 *
 * */
Route::middleware('checkanalyst')->prefix('/report/{slug}')->group(function()
{
   /*Report*/
	Route::get('/add1', 'Report\CreateController@report_add_form');
	Route::post('/add1', 'Report\CreateController@report_add');
	Route::get('/add2/{report}', 'Report\CreateController@report_step_2');
	Route::get('/add3/{report}/{category?}/{subcategory?}', 'Report\CreateController@report_step_3');
	Route::post('/add3/{flag?}', 'Report\CreateController@create3');
	Route::get('/addcategory/{report}', 'Report\CreateController@createcategoryform');
	Route::post('/addsubcategory', 'Report\CreateController@createsubcategory');
	Route::post('/addcategory', 'Report\CreateController@createcategory');


	Route::put('/article_publish/{article}', 'Report\UpdateController@article_publish' );
	Route::put('/publish/{report}', 'Report\UpdateController@publish');


	Route::get('/updreport/{report}', 'Report\UpdateController@updreportform');
	Route::post('/updreport/{report}', 'Report\UpdateController@updreport');
	Route::get('/upd/{article}', 'Report\UpdateController@upd_form');
	Route::put('/upd/{flag?}', 'Report\UpdateController@update');
	Route::get('/upd_category/{category}', 'Report\UpdateController@upd_form_category');
	Route::put('/upd_category/{category}', 'Report\UpdateController@update_category');
	Route::get('/upd_subcategory/{subcategory}', 'Report\UpdateController@upd_form_subcategory');
	Route::put('/upd_subcategory/{subcategory}', 'Report\UpdateController@update_subcategory');


	Route::delete('/delete_article/{article}', 'Report\DeleteController@delete_article');
	Route::delete('/deletereport/{report}', 'Report\DeleteController@delete_report');
	Route::delete('/deletecategory/{category}', 'Report\DeleteController@delete_category');
	Route::delete('/deletesubcategory/{subcategory}', 'Report\DeleteController@delete_subcategory');

});

/*
 * Manager routes
 *
 * */
Route::middleware('checkmanager')->prefix('manager')->group(function()
{
    Route::get('/', 'Manager\ManagerController@index');
    Route::get('/addform/{role}', 'Manager\ManagerController@adduserform');
    Route::post('/add/{role}', 'Manager\ManagerController@adduser');
    Route::get('/users/{role}', 'Manager\ManagerController@list');
    Route::get('/user/{user}', 'Manager\ManagerController@user_info');
    Route::delete('/users/{user}/delete', 'Manager\ManagerController@deluser');
    Route::get('/users/{user}/update', 'Manager\ManagerController@upduserform');
    Route::put('/users/{user}/update', 'Manager\ManagerController@upduser');

});
/*
 * Admin routes
 * */
Route::middleware('checkadmin')->group(function()
{
    Route::get('/stats/users/{user}', 'Admin\AdminController@users');
    Route::get('/stats/routes/', 'Admin\AdminController@count_visits');
    Route::get('/stats/routes/{route}', 'Admin\AdminController@count_visits_byroutes');

    /*ElasticIndex*/
	Route::get('/index', 'User\HomeController@indexes');

});

/*
 * PDF routes
 * */

Route::get('/pdf_subcategory/{report_id}/{cat_id}/{sub_id}', 'Pdf\PdfController@pdf_subcategory');
Route::get('/pdf_article/{id}', 'Pdf\PdfController@pdf_article');
Route::get('/pdf_item/{id}', 'Pdf\PdfController@pdf_item');
Route::get('/pdf_category/{report_id}/{category_id}', 'Pdf\PdfController@pdf_category');
Route::post('/pdf_search','Pdf\PdfController@pdf_search');



/*
 * resources routes
 * */

Route::resource('/reporttypes', 'ReporttypeController');
Route::resource('/categories', 'CategoryController');
Route::resource('/subcategories', 'SubcategoryController');
Route::resource('/company', 'Analyst\resources\CompanyController');
Route::resource('/country', 'Analyst\resources\CountryController');
Route::resource('/vvttypes', 'Analyst\resources\VvttypesController');
Route::resource('/vvttypes', 'Analyst\resources\VvttypesController');
Route::resource('/personalities', 'Analyst\resources\PersonalitiesController');
Route::resource('/search_countries', 'Analyst\resources\CountryController');


Route::get('/predistest','User\HomeController@predis');

Route::post('/redis','RedisController@newsearch');
Route::post('/redis/change','RedisController@change');

Route::get('/treckertest','User\HomeController@tracker');

Route::post('/search_tag', 'IndexController@search_country');
/*
* CKeditor
**/

Route::post('upload-image', function(
  \Illuminate\Http\Request $request,
  Illuminate\Contracts\Validation\Factory $validator
) {
  $v = $validator->make($request->all(), [
      'upload' => 'required|image',
  ]);

  $funcNum = $request->input('CKEditorFuncNum');

  if ($v->fails()) {
      return response(
          "<script>
              window.parent.CKEDITOR.tools.callFunction({$funcNum}, '', '{$v->errors()->first()}');
          </script>"
      );
  }

  $image = $request->file('upload');
  $image->store('public/uploads');
  $url = asset('storage/uploads/'.$image->hashName()); // /opt/php71/bin/php artisan storage:link

  return response(
      "<script>
          window.parent.CKEDITOR.tools.callFunction({$funcNum}, '{$url}', 'Изображение успешно загружено');
      </script>"
  );
});


Auth::routes();


