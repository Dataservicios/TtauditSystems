<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware'=>['auth']],function(){


});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('companies', 'CompanyController');

Route::get('getGraphStores/{company_id}', 'CompanyStoreController@getGraphStores');
Route::get('getUbigeosForCampaigne/{company_id}', 'CompanyStoreController@getUbigeosForCampaigne');
Route::get('getOfficeForCampaigne/{company_id}', 'ExchangeController@getOfficeForCampaigne');
Route::get('getOfficeForCampaigneWithInconsistency/{company_id}', 'InconsistencyController@getOfficeForCampaigne');

Route::get('getCategoryPoll/{company_id}/{audit_id}', 'PollController@getCategoryForCompanyAudit');
Route::get('getPollsForCategory/{category_id}/{company_audit_id}', 'PollController@getPollsForCategory');
Route::get('getPollDetailsForPollId/{poll_id}/{type}/{ubigeo?}', 'PollDetailsController@getRegsForPollId');
Route::get('getMenus/{company_id}', 'AuditController@getMenusForCompany');
Route::get('getAudits/{company_id}', 'AuditController@getAuditsForCompany');
Route::get('getCampaignes/{customer_id}/{visible}/{estudio}/{active}', 'CompanyController@getCampaigneForClient');
Route::get('getUsersForType/{type}', 'UserController@getUserForType');
Route::get('getPublicitiesForCategory/{category_id}/{company_id}', 'PublicityController@getPublicitiesForCategory');
Route::post('listStoresPublicities', 'PublicityController@ListStoresPublicity');
Route::post('getBaseForCompanyAudit', 'CompanyStoreController@getBaseForCompanyUbigeoAudit');//solo mistery
Route::get('getStore/{store_id}', 'StoreController@show');
Route::get('getStoreMedia/{store_id}/{company_id}/{poll_id}', 'StoreController@getMediasStore');
Route::get('getProductsPublicity/{company_id}/{publicity_id}', 'StockProductPopController@getProductsForPublicity');
Route::post('saveSOD', ['as' => 'saveSOD', 'uses' => 'OperationsController@saveSOD']);
Route::post('getQuestionForSod', ['as' => 'getQuestionForSod', 'uses' => 'PollController@getQuestionForSod']);
Route::post('changeResponseSiNo', 'PollDetailsController@changeSiNo');
Route::post('insertPollOptionDetail', 'PollOptionDetailsController@insertOption');
Route::post('deletePollOptionDetail', 'PollOptionDetailsController@deleteOption');
Route::post('updatedPollOptionDetail', 'PollOptionDetailsController@updateOptions');
Route::get('getResultsVerifyForCompanyId/{company_id}/{ubigeo?}', 'ExchangeController@getResultsVerifyForCompany');
Route::post('insertPhotoError', ['as' => 'insertPhotoError', 'uses' => 'LogController@insertBadPictureTaken']);
Route::post('getProcessingOsa', ['as' => 'getProcessingOsa', 'uses' => 'PollDetailsController@getProcessingOsa']);
Route::post('getFiltersOsa', ['as' => 'getFiltersOsa', 'uses' => 'PollDetailsController@getFilterOsaAll']);
Route::post('getFiltersProductsOsa', ['as' => 'getFiltersProductsOsa', 'uses' => 'PollDetailsController@getFiltersProductsOsaAll']);
Route::post('getEffectivePointsOsa', ['as' => 'getEffectivePointsOsa', 'uses' => 'PollDetailsController@getEffectivePointsOsa']);
Route::post('getCalculateGraphOsa', ['as' => 'getCalculateGraphOsa', 'uses' => 'PollDetailsController@calculateOsaOosVoid']);
Route::post('getValuesOsaCategoriesProducts', ['as' => 'getValuesOsaCategoriesProducts', 'uses' => 'PollDetailsController@getValuesOsaCategoriesProducts']);
Route::post('getValuesOsaProductsCategories', ['as' => 'getValuesOsaProductsCategories', 'uses' => 'PollDetailsController@getValuesOsaProductsCategories']);
Route::post('getValuesOsaRanking1', ['as' => 'getValuesOsaRanking1', 'uses' => 'PollDetailsController@getValuesOsaRanking1']);
Route::post('getEvolutivoOsa', ['as' => 'getEvolutivoOsa', 'uses' => 'PollDetailsController@getEvolutivoOsa']);
Route::post('getVendorsForGroup', ['as' => 'getVendorsForGroup', 'uses' => 'PollDetailsController@getVendorsForGroup']);
Route::post('getClientsOsa', ['as' => 'getClientsOsa', 'uses' => 'PollDetailsController@getClientsOsa']);
Route::post('validacionCanjes', ['as' => 'validacionCanjes', 'uses' => 'CanjesController@validacionCanjes']);
Route::post('getVersions', ['as' => 'getVersions', 'uses' => 'VersionController@versionsForVigent']);
Route::post('getDexOsa', ['as' => 'getDexOsa', 'uses' => 'PollDetailsController@getDexOsa']);
Route::post('loginUser', ['as' => 'loginUser', 'uses' => 'UserController@loginUser']);
Route::post('scalaCanjes', ['as' => 'scalaCanjes', 'uses' => 'CanjesController@scalasCanjes']);
