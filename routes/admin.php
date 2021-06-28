<?php

use Illuminate\Support\Facades\Route;

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

/******************************************************************************************************************************************************************************************************************/
Route::resource ('/course'                                                  , 'CourseController')                  ->except('show');
Route::get      ('/course/trash'                                              , 'CourseController@trash')               ->name('course.trash');
Route::post     ('/course/restore/{course}'                                   , 'CourseController@restore')             ->name('course.restore');
Route::delete   ('/course/trash/{course}'                                     , 'CourseController@forceDelete')         ->name('course.forceDelete');
Route::get      ('/course/{course}/steps'                                     , 'StepController@index')                 ->name('course.steps');
Route::get      ('/course/{course}/steps/create'                              , 'StepController@create')                ->name('course.steps.create');
Route::post     ('/course/{course}/steps'                                     , 'StepController@store')                 ->name('course.steps.store');
Route::get      ('/course/{course}/steps/{step}'                              , 'StepController@edit')                  ->name('course.steps.edit');
Route::delete   ('/course/{course}/steps/{step}'                              , 'StepController@delete')                ->name('course.steps.delete');
Route::put      ('/course/{course}/steps/{step}'                              , 'StepController@update')                ->name('course.steps.update');
/******************************************************************************************************************************************************************************************************************/
Route::resource ('/art'                                                     , 'ArtController')                     ->except('show');
Route::get      ('/art/trash'                                                 , 'ArtController@trash')                  ->name('art.trash');
Route::post     ('/art/restore/{art}'                                         , 'ArtController@restore')                ->name('art.restore');
Route::delete   ('/art/trash/{art}'                                           , 'ArtController@forceDelete')            ->name('art.forceDelete');
/******************************************************************************************************************************************************************************************************************/
Route::resource ('/comment'                                                 , 'CommentController')                 ->except('show');
Route::put      ('/comment/{comment}/reply'                                   , 'CommentController@replyUpdate')        ->name('comment.reply.update');
/******************************************************************************************************************************************************************************************************************/
Route::resource ('/gift'                                                    ,'GiftController')                      ->except('show');
Route::get      ('/gift/trash'                                                , 'GiftController@trash')                  ->name('gift.trash');
Route::post     ('/gift/restore/{gift}'                                       , 'GiftController@restore')                ->name('gift.restore');
Route::delete   ('/gift/trash/{gift}'                                         , 'GiftController@forceDelete')            ->name('gift.forceDelete');
/******************************************************************************************************************************************************************************************************************/
Route::get      ('/banner'                                                    , 'BannerController@index')                ->name('banner.index');
Route::get      ('/banner/most-popular'                                       , 'BannerController@mostPopular')          ->name('banner.mostPopular.index');
Route::get      ('/banner/most-popular/{banner}'                              , 'BannerController@mostPopularEdit')      ->name('banner.mostPopular.edit');
Route::put      ('/banner/most-popular/{banner}'                              , 'BannerController@mostPopularUpdate')    ->name('banner.mostPopular.update');
Route::get      ('/banner/our-offer'                                          , 'BannerController@ourOffer')             ->name('banner.ourOffer.index');
Route::get      ('/banner/our-offer/{banner}'                                 , 'BannerController@ourOfferEdit')         ->name('banner.ourOffer.edit');
Route::put      ('/banner/our-offer/{banner}'                                 , 'BannerController@ourOfferUpdate')       ->name('banner.ourOffer.update');
/******************************************************************************************************************************************************************************************************************/
Route::get      ('/transaction'                                               , 'TransactionController@index')           ->name('transaction.index');
Route::get      ('/add-user-class'                                            ,'TransactionController@addUserClass')     ->name('transaction.addUserClass');
Route::post     ('/add-user-class'                                            ,'TransactionController@storeUserClass')   ->name('transaction.storeUserClass');
/******************************************************************************************************************************************************************************************************************/
//Route::get      ('/teacher'                                                   ,'TeacherController@index')                ->name('teacher.index');
//Route::get      ('/teacher/{teacher}/edit'                                    ,'TeacherController@edit')                 ->name('teacher.edit');
//Route::put      ('/teacher/{teacher}/edit'                                    ,'TeacherController@update')               ->name('teacher.update');
//Route::get      ('/teacher/create'                                            ,'TeacherController@create')               ->name('teacher.create');
//Route::post     ('/teacher/create/{user}'                                     ,'TeacherController@store')                ->name('teacher.store');
/******************************************************************************************************************************************************************************************************************/
Route::resource   ('/bundle'                                              ,'BundleController');
/******************************************************************************************************************************************************************************************************************/
Route::post('transaction-excel-export', 'ExcelController@transactionExport')->name('transaction.excel');
Route::get('transaction-excel-export', 'ExcelController@transactionExportIndex')->name('transaction.excel.index');
Route::post('user-excel-export', 'ExcelController@userExport')->name('user.excel');
Route::get('user-excel-export', 'ExcelController@userExportIndex')->name('user.excel.index');


Route::get('/', "DashboardController@index")->name('dashboard');


Route::get('/query/courseclass_result', "DashboardController@courseclass_result")->name('courseclass_result');
