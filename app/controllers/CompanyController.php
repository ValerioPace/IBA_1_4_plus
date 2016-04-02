<?php

class CompanyController extends BaseController {

	public function update(){

		if (Auth::check()){

			$rules = array(
			    'email'    =>  'email'
			);

	        $messages = array(
			    'email'    => 'Quello inserito non e\' un indirizzo email valido.'
			);

			$validator = Validator::make(Input::all(), $rules, $messages);

			if ($validator->fails()) {
			    return Redirect::to('console/user.active.settings.company_settings')->withInput()->withErrors($validator);
			}

			$company = Company::where('user_id', Auth::user()->id)->first();
			$company->contact = Input::get('contact');
			$company->phone = Input::get('phone');
			$company->email = Input::get('email');
			$company->save();

			return View::make('user.active.settings.company_settings', array('userId' => Auth::user()->id));
			
		}

		return Response::make('Non autenticato!', 401);

	}


	public function createCompany($data){

		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$companyData = new CompanyData;
		$companyData->name = $data['name'];
		$companyData->last_name = $data['last_name'];
		$companyData->phone = $data['phone'];
		$companyData->email = $data['email'];
		$companyData->save();

		$company = new Company;
		$randomString = '';
	    for ($i = 0; $i < 50; $i++)
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
		$company->code = $randomString;
		$company->license_id = $data['license_id'];		
		$company->user_id = $data['user_id'];
		$company->data_id = $companyData->id;
		$company->name = $data['name'].' '.$data['last_name'];
		$company->contact = $data['name'];
		$company->phone = $data['phone'];
		$company->email = $data['email'];
		$company->company_status_id = 5;
		$company->save();

		$storeLink = new StoreLink;
		$storeLink->company_id = $company->id;
		$storeLink->save();

		return $company;	

	}
	
	public function publishCompanyApp(){
		if(Auth::check()){
			if (Auth::user()->role_id != 5){
				App::abort(403, 'Unauthorized action.');
            }

            $confirm = Input::get('action') === 'confirm' ? true : false;
            
            $userAppId = Input::get("userAppId");
			
			$rules = array(
					'app_name'  =>  'required|max:30',
                    'android_version_code' => 'required',
                    'android_version_number' => 'required',
                    'google_cloud_apikey' => 'required',
					'googleplay_link'  =>  array('required','regex:/((http|https):\/\/)?(www[.])?play\.google\.com\/store\/apps\/details\?id=(?:\w+\.)*(?:\w+)/'),
					'applestore_link'  =>  array('required','regex:/((http|https):\/\/)?(install\.diawi\.com\/.*|itunes\.apple\.com\/us\/app(\/.*)?\?id=[0-9]+.*|www\.apple\.com\/itunes\/download(\/.*)?\?id=[0-9]+.*)/')
				);
			
			$messages = array(
				    'app_name.required'    => 'Il nome dell\'app è un campo obbligatorio.',
				    'app_name.max'    => 'Non puoi inserire piu di 12 caratteri per il nome dell\'app.',
					'googleplay_link.required' => 'Il link di Google Play non è presente.',
				    'googleplay_link.regex' => 'Il link di Google Play non è valido.',
					'applestore_link.required' => 'Il link di Apple Store non è presente.',
					'applestore_link.regex' => 'Il link di Apple Store non è valido.',
					'google_cloud_apikey.required' => 'Specificare una Google API Key per le notifiche push',
					'ios_cert_file.required' => 'Effettuare l\' upload del certificato Ios per le notifiche push',
					'android_version_code.required' => 'Il version code su Android deve essere specificato',
					'android_version_number.required' => 'Il version code su Android deve essere specificato'		    
				);
				
			$validator = Validator::make(Input::all(), $rules, $messages);

			$validator->sometimes('ios_cert_file', 'required', function($input) use ($confirm){
				    return $confirm;
			});
			
			if ($validator->fails()) {
				    return Redirect::to('console/developers.clients.publishing.form')
                    ->with('userAppId',$userAppId)->withErrors($validator);
			}
            
			/*
			if ($confirm && (!$companyData->logo || !$companyData->cover_image)){
					if (!$companyData->logo)
						$validator->messages()->add('error', 'Bisogna caricare il logo.');
					if (!$companyData->cover_image)
						$validator->messages()->add('error', 'Bisogna caricare l\'immagine di copertina.');
					if (!$companyData->icon_image)
						$validator->messages()->add('error', 'Bisogna caricare l\'icona.');

					return Redirect::to('console/user.new.company_data_load')->withInput()->withErrors($validator);
				}
			*/

            $companyData = CompanyData::whereHas('company', function($query) use ($userAppId) {
					$query->where('user_id', $userAppId);
				})
				->first();

            $company = $companyData->company()->first();
            $companyTag = $company->download_link_tag;
            
            if (!file_exists(public_path().'/cert/'.$companyTag.'/ck.pem')){
               $validator->messages()->add('error', 'Bisogna caricare il certificato Ios.'); 
               return Redirect::to('console/developers.clients.publishing.form')
                    ->with('userAppId',$userAppId)->withErrors($validator);
            }

            $publishedApp = PublishedMobileApp::whereHas('developer', function($query) {
					$query->where('developer_id', Auth::user()->id);
					$query->orderBy('created_at', 'desc');
				})->first();

            if($publishedApp == null){
			    $publishedApp = new PublishedMobileApp;
                $publishedApp->developer_id = Auth::user()->id;
                $publishedApp->company_id = $company->id;
            }

            $androidLink = Input::get('googleplay_link');
            $iosLink = Input::get('applestore_link');

            $link = StoreLink::whereHas('company', function($query) use ($companyTag){
			   $query->where('download_link_tag', $companyTag);
		    })->first();

            if($link == null){
                $link = new StoreLink;
                $link->company_id = $company->id;
            }

            $link->android = $androidLink;
            $link->ios = $iosLink;
            $link->save();

            $publishedApp->name = Input::get('app_name');
		    $publishedApp->android_version_code = Input::get('android_version_code');
		    $publishedApp->android_version_number = Input::get('android_version_number');
		    $publishedApp->google_cloud_apikey=Input::get('google_cloud_apikey');
            $publishedApp->ios_cert_filename = '/certs/' . $companyTag . '/ck.pem';

			$publishedApp->save();

			return View::make('developers.clients.publishing.form', array('success' => 'Applicazione pubblicata con successo!'));
		}

	}

	public function saveCompanyData(){
		if (Auth::check()){

			if (Auth::user()->role_id == 1) {

				if (Auth::user()->user_status_id == 3 || Auth::user()->user_status_id == 4 || Auth::user()->company()->first()->company_status_id != 5)
					App::abort(403, 'Unauthorized action.');

				$confirm = Input::get('action') === 'confirm' ? true : false;

				$userId = Auth::user()->id;

				$rules = array(
					'company_name'  =>  'max:60',
					'app_name'  =>  'max:12',
					'company_description'  =>  'max:1000',
					'position'  =>  'max:40',
					'top_slogan'  =>  'max:42',
					'bottom_slogan'  =>  'max:42',
					'email'  =>  'email',
					'email_2'  =>  'email',
					'note'  =>  'max:500',
					'youtube_video1'  =>  array('regex:/^(https?\:\/\/)?(www\.youtube\.com)\/.+$/'),
					'youtube_video2'  =>  array('regex:/^(https?\:\/\/)?(www\.youtube\.com)\/.+$/'),
					'youtube_video3'  =>  array('regex:/^(https?\:\/\/)?(www\.youtube\.com)\/.+$/'),
					'youtube_video4'  =>  array('regex:/^(https?\:\/\/)?(www\.youtube\.com)\/.+$/'),
					'facebook_social'  =>  array('regex:/^(http\:\/\/|https\:\/\/)?(?:www\.)?facebook\.com\/(?:(?:\w\.)*#!\/)?(?:pages\/)?(?:[\w\-\.]*\/)*([\w\-\.]*)/'),
					'google_social'  =>  array('regex:/((http|https):\/\/)?(www[.])?plus\.google\.com\/.?\/?.?\/?([0-9]*)/'),
					'twitter_social'  =>  array('regex:/http(s)?:\/\/twitter\.com\/(#!\/)?[a-zA-Z0-9_]+/'),
					'linkedin_social'  =>  array('regex:/^(http(s)?:\/\/)?([\w]+\.)?linkedin\.com\/(pub|in|profile)/'),
				);

		        $messages = array(
				    'name.required'    => 'Il nome è un campo obbligatorio.',
				    'last_name.required'    => 'Il cognome è un campo obbligatorio.',
				    'company_name.required'    => 'Il nome dell\'azienda è un campo obbligatorio.',
				    'app_name.required'    => 'Il nome dell\'app è un campo obbligatorio.',
				    'app_name.max'    => 'Non puoi inserire piu di 12 caratteri per il nome dell\'app.',
				    'company_name.max'    => 'Non puoi inserire piu di 60 caratteri per il nome dell\'azienda.',
				    'company_description.required'    => 'Devi inserire una descrizione dell\'azienda.',
				    'company_description.max'    => 'Non puoi inserire piu\' di 1000 caratteri per la descrizione.',
				    'position.max'    => 'Non puoi inserire piu\' di 40 caratteri per il ruolo/posizione.',
				    'position.required'    => 'E\' necessario specificare un Titolo/Nome attivita\'.',
				    'top_slogan.required'    => 'Devi inserire lo slogan "alto".',
				    'top_slogan.max'    => 'Non puoi inserire piu di 42 caratteri per lo slogan alto.',
				    'bottom_slogan.required'    => 'Devi inserire lo slogan "basso".',
				    'bottom_slogan.max'    => 'Non puoi inserire piu di 42 caratteri per lo slogan basso.',
				    'phone.required'    => 'E\' necessario specificare un numero di telefono.',
				    'email.required'    => 'E\' necessario specificare un indirizzo email.',
				    'email.email'    => 'L\'inirizzo email principale non è valido.',
				    'email_2.email'    => 'L\'inirizzo email secondario non è valido.',
				    'note.max'    => 'Non puoi inserire piu di 500 caratteri per le note.',
				    'youtube_video1.regex' => 'Il link al video 1 di YouTube non è valido.',
				    'youtube_video2.regex' => 'Il link al video 2 di YouTube non è valido.',
				    'youtube_video3.regex' => 'Il link al video 3 di YouTube non è valido.',
				    'youtube_video4.regex' => 'Il link al video 4 di YouTube non è valido.',
				    'facebook_social.regex' => 'Il link di Facebook non è valido.',
				    'google_social.regex' => 'Il link di Google+ non è valido.',
				    'twitter_social.regex' => 'Il link di Twitter non è valido.',
				    'linkedin_social.regex' => 'Il link di Linkedin non è valido.'
				);

				$validator = Validator::make(Input::all(), $rules, $messages);


				$validator->sometimes('name', 'required', function($input) use ($confirm){
				    return $confirm;
				});
				$validator->sometimes('last_name', 'required', function($input) use ($confirm){
				    return $confirm;
				});
				$validator->sometimes('company_name', 'required', function($input) use ($confirm){
				    return $confirm;
				});
				$validator->sometimes('company_description', 'required', function($input) use ($confirm){
				    return $confirm;
				});
				$validator->sometimes('top_slogan', 'required', function($input) use ($confirm){
				    return $confirm;
				});
				$validator->sometimes('bottom_slogan', 'required', function($input) use ($confirm){
				    return $confirm;
				});
				$validator->sometimes('phone', 'required', function($input) use ($confirm){
				    return $confirm;
				});
				$validator->sometimes('email', 'required', function($input) use ($confirm){
				    return $confirm;
				});
				$validator->sometimes('app_name', 'required', function($input) use ($confirm){
				    return $confirm;
				});
				$validator->sometimes('position', 'required', function($input) use ($confirm){
				    return $confirm;
				});


				if ($validator->fails()) {
				    return Redirect::to('console/user.new.company_data_load')->withInput()->withErrors($validator);
				}

				$companyData = CompanyData::whereHas('company', function($query) use ($userId) {
					$query->where('user_id', $userId);
				})
				->first();

				if ($confirm && (!$companyData->logo || !$companyData->cover_image)){
					if (!$companyData->logo)
						$validator->messages()->add('error', 'Bisogna caricare il logo.');
					if (!$companyData->cover_image)
						$validator->messages()->add('error', 'Bisogna caricare l\'immagine di copertina.');
					if (!$companyData->icon_image)
						$validator->messages()->add('error', 'Bisogna caricare l\'icona.');

					return Redirect::to('console/user.new.company_data_load')->withInput()->withErrors($validator);
				}

			} else if (Auth::user()->role_id == 2 || Auth::user()->role_id == 5){

				$userId = Input::get('userId');
				$confirm = true;

			
				$companyData = CompanyData::whereHas('company', function($query) use ($userId) {
					$query->where('user_id', $userId);
				})
				->first();

			} else {

				App::abort(403, 'Unauthorized action.');
			}
			

			$companyData->name = Input::get('name');
			$companyData->last_name = Input::get('last_name');
			$companyData->app_name = Input::get('app_name');
			$companyData->company_name = Input::get('company_name');
			$companyData->company_description = Input::get('company_description');
			$companyData->position = Input::get('position');
			$companyData->top_slogan = Input::get('top_slogan');
			$companyData->bottom_slogan = Input::get('bottom_slogan');
			$companyData->phone = Input::get('phone');
			$companyData->email = Input::get('email');

			if (Input::has('email_2'))
				$companyData->email_2 = Input::get('email_2');
			else
				$companyData->email_2 = null;
			
			if (Input::has('mobile'))
				$companyData->mobile = Input::get('mobile');
			else
				$companyData->mobile = null;

			if (Input::has('fax'))
				$companyData->fax = Input::get('fax');
			else
				$companyData->fax = null;

			if (Input::has('address'))
				$companyData->address = Input::get('address');
			else
				$companyData->address = null;

			if (Input::has('web_site'))
				$companyData->web_site = Input::get('web_site');
			else
				$companyData->web_site = null;

			if (Input::has('web_site_2'))
				$companyData->web_site_2 = Input::get('web_site_2');
			else
				$companyData->web_site_2 = null;

			if (Input::has('note'))
				$companyData->note = Input::get('note');
			else
				$companyData->note = null;



			$companySocial = CompanyDataLink::
				where('company_data_id', $companyData->id)
				->where('type_id', 1)
				->where('network', 'Facebook')
			->first();
			if (count($companySocial)>0){
				if (Input::has('facebook_social')){
					$companySocial->link = Input::get('facebook_social');
					$companySocial->save();
				} else
					$companySocial->delete();				
						
			} else if (Input::has('facebook_social')){
				$companySocial = new CompanyDataLink;
				$companySocial->company_data_id = $companyData->id;
				$companySocial->type_id = 1;
				$companySocial->network = 'Facebook';
				$companySocial->link = Input::get('facebook_social');
				$companySocial->save();
			}


			$companySocial = CompanyDataLink::
				where('company_data_id', $companyData->id)
				->where('type_id', 1)
				->where('network', 'Google+')
			->first();
			if (count($companySocial)>0){
				if (Input::has('google_social')){
					$companySocial->link = Input::get('google_social');
					$companySocial->save();
				} else
					$companySocial->delete();				
						
			} else if (Input::has('google_social')){
				$companySocial = new CompanyDataLink;
				$companySocial->company_data_id = $companyData->id;
				$companySocial->type_id = 1;
				$companySocial->network = 'Google+';
				$companySocial->link = Input::get('google_social');
				$companySocial->save();
			}


			$companySocial = CompanyDataLink::
				where('company_data_id', $companyData->id)
				->where('type_id', 1)
				->where('network', 'Twitter')
			->first();
			if (count($companySocial)>0){
				if (Input::has('twitter_social')){
					$companySocial->link = Input::get('twitter_social');
					$companySocial->save();
				} else
					$companySocial->delete();				
						
			} else if (Input::has('twitter_social')){
				$companySocial = new CompanyDataLink;
				$companySocial->company_data_id = $companyData->id;
				$companySocial->type_id = 1;
				$companySocial->network = 'Twitter';
				$companySocial->link = Input::get('twitter_social');
				$companySocial->save();
			}


			$companySocial = CompanyDataLink::
				where('company_data_id', $companyData->id)
				->where('type_id', 1)
				->where('network', 'Linkedin')
			->first();
			if (count($companySocial)>0){
				if (Input::has('linkedin_social')){
					$companySocial->link = Input::get('linkedin_social');
					$companySocial->save();
				} else
					$companySocial->delete();				
						
			} else if (Input::has('linkedin_social')){
				$companySocial = new CompanyDataLink;
				$companySocial->company_data_id = $companyData->id;
				$companySocial->type_id = 1;
				$companySocial->network = 'Linkedin';
				$companySocial->link = Input::get('linkedin_social');
				$companySocial->save();
			}




			$youtubeVideo = CompanyDataLink::
				where('company_data_id', $companyData->id)
				->where('type_id', 2)
				->where('network', 'Youtube1')
			->first();
			if (count($youtubeVideo)>0){
				if (Input::has('youtube_video1')){
					$youtubeVideo->link = Input::get('youtube_video1');
					$youtubeVideo->save();
				} else
					$youtubeVideo->delete();				
						
			} else if (Input::has('youtube_video1')){
				$youtubeVideo = new CompanyDataLink;
				$youtubeVideo->company_data_id = $companyData->id;
				$youtubeVideo->type_id = 2;
				$youtubeVideo->network = 'Youtube1';
				$youtubeVideo->link = Input::get('youtube_video1');
				$youtubeVideo->save();
			}

			$youtubeVideo = CompanyDataLink::
				where('company_data_id', $companyData->id)
				->where('type_id', 2)
				->where('network', 'Youtube2')
			->first();
			if (count($youtubeVideo)>0){
				if (Input::has('youtube_video2')){
					$youtubeVideo->link = Input::get('youtube_video2');
					$youtubeVideo->save();
				} else
					$youtubeVideo->delete();				
						
			} else if (Input::has('youtube_video2')){
				$youtubeVideo = new CompanyDataLink;
				$youtubeVideo->company_data_id = $companyData->id;
				$youtubeVideo->type_id = 2;
				$youtubeVideo->network = 'Youtube2';
				$youtubeVideo->link = Input::get('youtube_video2');
				$youtubeVideo->save();
			}

			$youtubeVideo = CompanyDataLink::
				where('company_data_id', $companyData->id)
				->where('type_id', 2)
				->where('network', 'Youtube3')
			->first();
			if (count($youtubeVideo)>0){
				if (Input::has('youtube_video3')){
					$youtubeVideo->link = Input::get('youtube_video3');
					$youtubeVideo->save();
				} else
					$youtubeVideo->delete();				
						
			} else if (Input::has('youtube_video3')){
				$youtubeVideo = new CompanyDataLink;
				$youtubeVideo->company_data_id = $companyData->id;
				$youtubeVideo->type_id = 2;
				$youtubeVideo->network = 'Youtube3';
				$youtubeVideo->link = Input::get('youtube_video3');
				$youtubeVideo->save();
			}

			$youtubeVideo = CompanyDataLink::
				where('company_data_id', $companyData->id)
				->where('type_id', 2)
				->where('network', 'Youtube4')
			->first();
			if (count($youtubeVideo)>0){
				if (Input::has('youtube_video4')){
					$youtubeVideo->link = Input::get('youtube_video4');
					$youtubeVideo->save();
				} else
					$youtubeVideo->delete();				
						
			} else if (Input::has('youtube_video4')){
				$youtubeVideo = new CompanyDataLink;
				$youtubeVideo->company_data_id = $companyData->id;
				$youtubeVideo->type_id = 2;
				$youtubeVideo->network = 'Youtube4';
				$youtubeVideo->link = Input::get('youtube_video4');
				$youtubeVideo->save();
			}
			

			$companyData->save();

			if ($confirm){

				if (Auth::user()->role_id == 1) {
					$user = Auth::user();
					$user->user_status_id = 2;
					$user->save();

					$company = Company::where('user_id', $user->id)->first();
					$company->company_status_id = 3;
					$company->save();

					return View::make('user.new.company_data_load', array('success' => 'Il modulo è stato inviato all\'amministratore. Torna a controllare lo stato d\'avanzamento dei lavori.'));
				} else if (Auth::user()->role_id == 2) {
					return View::make('admin.clients.activating.details', array('success' => 'Dati salvati con successo!'));
				} else if (Auth::user()->role_id == 5) {
					return View::make('developers.clients.activating.details', array('success' => 'Dati salvati con successo!'));
				}

				
			}

			return View::make('user.new.company_data_load', array('success' => 'Dati salvati con successo!'));
						
		}
	}

	public function setCompanyStatus () {
		if (Auth::check() && (Auth::user()->role_id == 2 || Auth::user()->role_id == 5) ){

			$companyId = Input::get('companyId');
			$status = Input::get('status');
			$userId = Input::get('userId');

			$company = Company::find($companyId);

			$user = User::find($userId);

			if (Auth::user()->role_id == 2)
				$availableStatus = [1,2,3,5];
			else if (Auth::user()->role_id == 5)
				$availableStatus = [2,3,5];

			if (count($company) > 0) {
				if (in_array($status, $availableStatus)) {
					
					
					if (count($user) > 0){

						if ($status == 1){
							$user->user_status_id = 1;
							$user->save();
						} else if ($status == 2){
							$user->user_status_id = 2;
							$user->save();
						} else if ($status == 3){
							$user->user_status_id = 2;
							$user->save();
						} else if ($status == 5){
							$user->user_status_id = 2;
							$user->save();
						}
					}

					$company->company_status_id = $status;
					$company->save();

				}
			}
			
			if (Input::has('sendNotificationMail') && Input::get('sendNotificationMail') === 'true') {
				
				$status = $company->company_status_id == 5 ? 'In Sospeso' : ($company->company_status_id == 3 ? 'In Revisione' : ($company->company_status_id == 2 ? 'In Sviluppo' : ($company->company_status_id == 1 ? 'Servizio Attivo' : '')));

				$mailParams = array('user' => $user, 'company' => $company, 'status' => $status, 'msg' => nl2br(Input::get('notificationMessage')));
				App::make('MailController')->sendMail('emails.status_change', $user->email, $company->name, 'Nuovo Status: '.$status, $mailParams);

			}

			return array('success' => 'Stato modificato!');
		}
	}

	public function changeCompanyDeveloper () {
		if (Auth::check() && Auth::user()->role_id == 2){

			$userId = Input::get('userId');

			$companyData = CompanyData::whereHas('company', function($query) use ($userId) {
				$query->where('user_id', $userId);
			})
			->first();

			$developer = User::where('role_id', 5)->find(Input::get('developerId'));

			if ($developer){
				$companyData->developer_id = $developer->id;
				$companyData->save();

				return $developer->name;
			} else if (Input::get('developerId') == 0) {				
				$companyData->developer_id = null;
				$companyData->save();

				return 'NON ASSEGNATO';
			}

			return false;

		} else
			return Response::make('Non autenticato!', 401);
	}
}
