<?php


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*===ROUTE PER CREAZIONE NUOVE UTENZE*/
Route::get('/cr3at3N3w/{code}', function($code)
{

	$company = Company::where('code', $code)->first();

	if (count($company) > 0)
		return '!!COMPANY GIA\' ESISTENTE!!';

	$user = new User;
	$user->user_name = 'finco';
	$user->password = Hash::make('finco');
	$user->role_id = 1;
	$user->user_status_id = 1;
	$user->save();

	$company = new Company;
	$company->code = $code;
	$company->user_id = $user->id;
	$company->name = 'FINCO';
	$company->download_link_tag = 'finco';
	$company->company_status_id = 1;
	$company->save();

	$sLink = new StoreLink;
	$sLink->company_id = $company->id;
	$sLink->save();

	return '!!CREATED!!';
});
/*===(FINE) - ROUTE PER CREAZIONE NUOVE UTENZE*/


Route::get('/cr3at3C0d3/{amount}', function($amount)
{
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $codes = array();

        for ($j=0; $j<$amount && $j < 20; $j++){
            $randomString = '';
            for ($i = 0; $i < 50; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            $codes[] = $randomString;
        }

    return $codes;
});




/*===ROUTE PER DEVICE MARKET REDIRECT*/
Route::get('/download/{companyTag}', 'RedirectController@deviceRedirect');
/*===(FINE) - ROUTE PER DEVICE MARKET REDIRECT*/

/*===ROUTE PER SETTAGGIO DEVICE TOKEN*/
Route::any('/device/set_token/{platform_code}/{company_code}/{token}', 'DeviceController@setToken');
/*===(FINE) - ROUTE PER SETTAGGIO DEVICE TOKEN*/

/*===ROUTE PER DOWNLOAD EVENTI*/
Route::get('/event_list/{token}', 'EventController@getActiveEventsByToken');
/*===(FINE) - ROUTE PER DOWNLOAD EVENTI*/

/*===ROUTE PER DETTAGLIO EVENTO (SETTA EVENTO COME LETTO)*/
Route::get('/event_details/by_id/{event_id}/{device_token}', 'EventController@getEventDetails');
/*===(FINE) - ROUTE PER DETTAGLIO EVENTO*/


Route::get('/', function()
{
	return View::make('login');
});

Route::get('/login', function(){
	return Redirect::to('/');
});

/*ROUTE PER AUTENTICAZIONE
	restituisce:
	la view di login con i messaggi di errore (login)
	la view principale del pannello in caso di successo (navbar)
*/
Route::post('/', 'UserController@login');

Route::post('/do_logout', 'UserController@logout');

Route::get('/activate_account/{userId}/{aCode}', 'UserController@activateUser');

Route::post('/do_create_user', 'UserController@signUp');

/*CREA UN NUOVO EVENTO
	restituisce:
	la view per la creazione di un nuovo evento con i messaggi di errore (new_event)
	la view degli eventi attivi in caso di successo (active_events)
*/
Route::post('/new_event', 'EventController@createNew');

/*MODIFICA/ELIMINA EVENTO
*/
Route::post('/update_event', 'EventController@edit');

/*RESET PASSWORD UTENTE
*/
Route::post('/reset_password', 'UserController@passwordReset');

/*MODIFICA PASSWORD UTENTE
*/
Route::post('/edit_password', 'UserController@editPassword');

/*MODIFICA IMPOSTAZIONI AZIENDA
*/
Route::post('/update_settings', 'CompanyController@update');

/*RESTITUISCE LE VARIE VIEW DELLA CONSOLE*/
Route::any('/console/{tag}', function ($view)
{
	
	if (Auth::check()){
	
		$params = array();

		$params['userId'] = Auth::user()->id;

		if (Input::has('event_id'))
			$params['event_id'] = Input::get('event_id');
		
		return View::make($view, $params);
	}

	return View::make('login');
});


Route::any('/console/appCount/reload', function ()
{
	if (Auth::check()){
		$androidDevices = Device::whereHas('company', function ($query) {
          $query->whereHas('user', function ($query) {
            $query->where('id', Auth::user()->id);
          });
        })
        ->where('platform_id', 1)
        ->count();

        $iosDevices = Device::whereHas('company', function ($query) {
          $query->whereHas('user', function ($query) {
            $query->where('id', Auth::user()->id);
          });
        })
        ->where('platform_id', 2)
        ->count();

        return 'Android <span class="badge">'.$androidDevices.'</span><span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span><span class="badge">'.$iosDevices.'</span> iOS';
	}
});

/*===ROUTE PER DETTAGLIO EVENTO DA CONSOLE*/
/*Route::get('/active_event_details', function()
{
	return View::make('events.active.details', array('event_id' => Input::get('event_id')));
});

Route::get('/expired_event_details', function()
{
	return View::make('expired_event_details', array('event_id' => Input::get('event_id')));
});*/
/*===(FINE) - ROUTE PER DETTAGLIO EVENTO DA CONSOLE*/


/*===ROUTE PER DOWNLOAD LOCANDINA*/
Route::get('/event_image/full_size/by_event/{eventId}', 'EventController@getFlyerFullByEvent');

Route::get('/event_image/full_size/by_tag/{imageTag}', 'EventController@getFlyerFullByTag');

Route::get('/event_image/thumbnail/by_event/{eventId}', 'EventController@getFlyerThumbByEvent');

Route::get('/event_image/thumbnail/by_tag/{imageTag}', 'EventController@getFlyerThumbByTag');
/*===(FINE) - ROUTE PER DOWNLOAD LOCANDINA*/

Route::get('/createUser', function()
{
	return View::make('index', array('nav' => 'user.new.layouts.navbar_preAuth', 'page' => 'user.new.user_create'));
});

Route::get('/createUser/withErrors', function()
{
	return View::make('user.new.user_create');
});


Route::post('/save_company_data', 'CompanyController@saveCompanyData');

Route::post('/publish_company_app', 'CompanyController@publishCompanyApp');

Route::post('/company/setStatus', 'CompanyController@setCompanyStatus');


Route::post('/upload/companyImages/logo', 'UploadController@uploadLogoImage');

Route::post('/upload/companyImages/cover', 'UploadController@uploadCoverImage');

Route::post('/upload/companyData/iosCert', 'UploadController@uploadPEMCertIos');

Route::post('/upload/companyImages/icon', 'UploadController@uploadIconImage');

Route::post('/upload/companyImages/gallery', 'UploadController@uploadGalleryImages');


Route::any('/delete/companyImages/logo', 'UploadController@deleteLogoImage');

Route::any('/delete/companyImages/cover', 'UploadController@deleteCoverImage');

Route::any('/delete/companyImages/icon', 'UploadController@deleteIconImage');

Route::any('/delete/companyImages/gallery', 'UploadController@deleteGalleryImage');


Route::get('/companyImages/gallery/full_size/by_id/{id}', 'DownloadController@getGalleryImageFullById');

Route::get('/companyImages/gallery/thumbnail/by_id/{id}', 'DownloadController@getGalleryImageThumById');

Route::get('/companyImages/logo/full_size/by_id/{id}', 'DownloadController@getLogoImageFullById');

Route::get('/companyImages/logo/thumbnail/by_id/{id}', 'DownloadController@getLogoImageThumById');

Route::get('/companyImages/icon/full_size/by_id/{id}', 'DownloadController@getIconImageFullById');

Route::get('/companyImages/icon/thumbnail/by_id/{id}', 'DownloadController@getIconImageThumById');

Route::get('/companyImages/cover/full_size/by_id/{id}', 'DownloadController@getCoverImageFullById');

Route::get('/companyImages/cover/thumbnail/by_id/{id}', 'DownloadController@getCoverImageThumById');


Route::get('/admin/downloadCompanyData/{userId}', 'DownloadController@downloadCompanyData');


Route::post('/admin/activationCodes/new', 'ActivationCodeController@newBatch');

Route::get('/admin/activationCodes/getBatchName', 'ActivationCodeController@getBatchName');

Route::post('/admin/activationCodes/setStatus', 'ActivationCodeController@setStatus');

Route::post('/admin/changeCompanyDeveloper', 'CompanyController@changeCompanyDeveloper');



Route::post('/developer/sendTestPushNotification', 'PushController@testSendAll');


/*Route::get('/admin/openUserConsole', function () {

	$userId = Input::get('userId');
	
	$owner = User::find($userId);
	Session::put( 'orig_user', Auth::id() );
	Auth::login($owner);

	return View::make('index', array('nav' => 'user.active.layouts.navbar', 'page' => 'user.active.home'));
});*/



Route::get('/info', function()
{
	echo phpinfo();
});

/*Route::get('codes/get/{amount}', function($amount){

	$codes = ActivationCode::where('buyer_id', null)->take($amount)->get();

	foreach ($codes as $key => $code) {
		echo $code->code.'</br>';
	}
});

Route::get('codes/get', function(){

	$codes = ActivationCode::where('buyer_id', null)->take(1)->get();

	foreach ($codes as $key => $code) {
		echo $code->code.'</br>';
	}
});*/




Route::get('test', function(){

});