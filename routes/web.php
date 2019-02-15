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
	Route::get('/search/{q?}', 'User\HomeController@advanced_search');
    Route::post('/search', 'User\HomeController@advanced_search');
    Route::get('/simply_search', 'User\HomeController@search');
	Route::post('/simply_search', 'User\HomeController@search');
//	Route::get('/migrate', 'Migrate\MigrateController@migrate');
//	Route::get('/migrate_one', 'Migrate\MigrateController@migrate_one');
//	Route::get('/migrate_two', 'Migrate\MigrateController@migrate_two');
//	Route::get('/migrate_three', 'Migrate\MigrateController@migrate_three');
//	Route::get('/migrate_four', 'Migrate\MigrateController@migrate_four');
//	Route::get('/migrate_five', 'Migrate\MigrateController@migrate_five');
	Route::get('/delete', 'Migrate\MigrateController@delete');

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

Route::middleware('checkanalyst')->group(function()
{
	Route::get('/plannedexhibition/add2/{plannedexhibitionyear}', 'Analyst\PlannedexhibitionController@create2form');
});
/*
 * Analyst routes
 *
 * */
Route::middleware('checkanalyst')->prefix('/report/{slug}')->group(function()
{
    //Route::get('/', 'Analyst\HomeController@index');

    /*Route::put('/plannedexhibition/for_approval/{plannedexhibitionyear}', 'Analyst\PlannedexhibitionController@for_approval');*/
    //Route::put('/plannedexhibition/article_publish/{plannedexhibition}', 'Analyst\PlannedexhibitionController@article_publish');
    //Route::put('/plannedexhibition/article_for_approval/{plannedexhibition}', 'Analyst\PlannedexhibitionController@article_for_approval');

    /*Route::put('/various/for_approval/{variousreport}', 'Analyst\VariousController@for_approval');*/
 // Route::put('/various/article_publish/{variousarticle}/{variousreport}', 'Analyst\VariousController@article_publish');
  //  Route::put('/various/article_for_approval/{variousarticle}', 'Analyst\VariousController@article_for_approval');

    /*Report*/
	Route::get('/add1', 'Report\ReportController@report_add_form');
	Route::post('/add1', 'Report\ReportController@report_add');
	Route::get('/add2/{report}', 'Report\ReportController@report_step_2');
	Route::get('/add3/{report}/{category?}/{subcategory?}', 'Report\ReportController@report_step_3');
	Route::put('/article_publish/{article}', 'Report\ReportController@article_publish' );
	Route::post('/add3/{flag?}', 'Report\ReportController@create3');
	Route::delete('/delete_article/{article}', 'Report\ReportController@delete_article');
	Route::get('/upd/{article}', 'Report\ReportController@upd_form');
	Route::put('/upd/{flag?}', 'Report\ReportController@update');
	Route::put('/publish/{report}', 'Report\ReportController@publish');
	Route::get('/updreport/{report}', 'Report\ReportController@updreportform');
	Route::post('/updreport/{report}', 'Report\ReportController@updreport');
	Route::delete('/deletereport/{report}', 'Report\ReportController@delete_report');
	Route::post('/addcategory', 'Report\ReportController@createcategory');
	Route::get('/addcategory/{report}', 'Report\ReportController@createcategoryform');
	Route::delete('/deletecategory/{category}', 'Report\ReportController@delete_category');
	Route::post('/addsubcategory', 'Report\ReportController@createsubcategory');
	Route::delete('/deletesubcategory/{subcategory}', 'Report\ReportController@delete_subcategory');
	Route::get('/upd_category/{category}', 'Report\ReportController@upd_form_category');
	Route::put('/upd_category/{category}', 'Report\ReportController@update_category');
	Route::get('/upd_subcategory/{subcategory}', 'Report\ReportController@upd_form_subcategory');
	Route::put('/upd_subcategory/{subcategory}', 'Report\ReportController@update_subcategory');
	Route::delete('{article}/deletearticle', 'Report\ReportController@delete_article');

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
	//Route::get('/index', 'User\HomeController@indexes');


});

/*
 * PDF routes
 * */

Route::get('/pdf_subcategory/{report_id}/{cat_id}/{sub_id}', 'Pdf\PdfController@pdf_subcategory');
//Route::get('/{report}/pdf_subcategory/{id}/{id_cat?}/{id_sub?}', 'Pdf\PdfController@pdf_subcategory');

Route::get('/pdf_article/{id}', 'Pdf\PdfController@pdf_article');

Route::get('/pdf_item/{id}', 'Pdf\PdfController@pdf_item');

Route::get('/pdf_category/{report_id}/{category_id}', 'Pdf\PdfController@pdf_category');

Route::post('/pdf_search','Pdf\PdfController@pdf_search');

//Route::get('/{report}/pdf_category/{id}/{cat_id?}', 'Pdf\PdfController@pdf_category');


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
//Route::get('/test', 'IndexController@test');

Auth::routes();


