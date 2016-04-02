<?php

class UserController extends BaseController {

	public function login(){
		
		$rules = array(
			'user_name'  =>  'required',
		    'password'    =>  'required'
		);

		$messages = array(
		    'user_name.required'    => 'Immettere nome utente.',
		    'password.required'    => 'Immettere password.'
		);

		$validator = Validator::make(Input::all(), $rules, $messages);

		if ($validator->fails()) {
		    return Redirect::to('/')->withInput()->withErrors($validator);
		}

		$authorized = array(
			'test792',
			'frassenza'
		);

		if (Auth::attempt(array('user_name' => Input::get('user_name'), 'password' => Input::get('password')), Input::get('remember_me') ? true : false)){
			$user = Auth::user();
			if ($user->role_id == '1'){
				if ($user->user_status_id == '1')
					return View::make('index', array('nav' => 'user.active.layouts.navbar', 'page' => 'user.active.home'));
				else if ($user->user_status_id == '2')
					return View::make('index', array('nav' => 'user.new.layouts.navbar', 'page' => 'user.new.company_data_load'));
				else if ($user->user_status_id == '3'){
					$validator->messages()->add('error', 'Non hai ancora verificato la tua mail. Ti stiamo per inviare un messaggio all\'indirizzo "'.$user->email.'", segui il link che trovi al suo interno per attivare l\'account.');
					$user->confirmation_code = str_random(50);
					$user->save();
					$company = $user->company;
					$mailViewData = array('user' => $user, 'company' => $company, 'aURI' => url('/').'/activate_account/'.$user->id.'/'.$user->confirmation_code);
					App::make('MailController')->sendMail('emails.email_confirm',$user->email, $company->name, 'IBA - Verifica Email', $mailViewData);
					Auth::logout();
					return Redirect::to('/')->withInput()->withErrors($validator);
				}
				else if ($user->user_status_id == '4'){
					Auth::logout();
					$validator->messages()->add('error', 'Account bloccato!');
					return Redirect::to('/')->withInput()->withErrors($validator);
				} else
					Auth::logout();

			} else if ($user->role_id == '2'){
				return View::make('index', array('nav' => 'admin.layouts.navbar', 'page' => 'admin.clients.activating.container'));
			
			} else if ($user->role_id == '5'){
				return View::make('index', array('nav' => 'developers.layouts.navbar', 'page' => 'developers.clients.activating.container'));
			
			} else
				Auth::logout();
		}

		$validator->messages()->add('error', 'Credenziali non valide!');
		return Redirect::to('/')->withInput()->withErrors($validator);
		
	}

	public function logout(){
		Auth::logout();
		return View::make('login');
	}

	public function passwordReset (){
		
		$rules = array(
			'email' => 'required|email|exists:users,email',
		);

		$messages = array(
		    'email.required'    => 'Inserire un indirizzo email.',
		    'email.email'    => 'Inserire un indirizzo email valido.',
		    'email.exists'    => 'Indirizzo email non presente in anagrafica. Per assistenza scrivi a:<br><em>'.User::find(0)->email.'</em>',
		);

		$validator = Validator::make(Input::all(), $rules, $messages);

		if ($validator->fails()) {
		    return Redirect::to('/')->withInput()->withErrors($validator);
		}

		$user = User::where('email', Input::get('email'))->first();

		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$newPassword = '';
	    for ($j = 0; $j < 8; $j++) {
	        $newPassword .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    
	    $user->password = Hash::make($newPassword);
		$user->save();

		$company = $user->company;

		$mailViewData = array('user' => $user, 'password' => $newPassword);
		App::make('MailController')->sendMail('emails.password_reset',$user->email, $user->name.' '.$user->last_name, 'IBA - Reset Password', $mailViewData);

		return Redirect::to('/')->with('success', 'A breve riceverai un messaggio all\'indirizzo "'.$user->email.'" con le nuove credenziali per accedere al sistema.');

	}


	public function editPassword(){
		if (Auth::check()){

			$rules = array(
			    'password'  =>'Required|alphanum|Min:8|Confirmed'
			);

	        $messages = array(
			    'password.required'    => 'Immettere password.',
			    'password.alphanum'    => 'Inserire solo caratteri alfanumerici.',
			    'password.min'    => 'La lunghezza minima e\' di 8 caratteri.',
			    'password.confirmed'    => 'Le due password inserite non corrispondono.'
			);

			$validator = Validator::make(Input::all(), $rules, $messages);

			if ($validator->fails()) {
				if (Auth::user()->role_id == 1)
			    	return Redirect::to('console/user.active.settings.user_settings')->withInput()->withErrors($validator);
			    else if (Auth::user()->role_id == 2)
			    	return Redirect::to('console/admin.settings.user_settings')->withInput()->withErrors($validator);
			    else if (Auth::user()->role_id == 5)
			    	return Redirect::to('console/developers.settings.user_settings')->withInput()->withErrors($validator);
			}

			$user = User::find(Auth::user()->id);
			$user->password = Hash::make(Input::get('password'));
			$user->save();


			if (Auth::user()->role_id == 1)
		    	return View::make('user.active.settings.user_settings', array('userId' => Auth::user()->id));
		    else if (Auth::user()->role_id == 2)
		    	return View::make('admin.settings.user_settings', array('userId' => Auth::user()->id));
		    else if (Auth::user()->role_id == 5)
		    	return View::make('developers.settings.user_settings', array('userId' => Auth::user()->id));

			
			
		} else
			return Response::make('Non autenticato!', 401);
	}


	/*METODO PER LA CREAZIONE DI UN NUOVO UTENTE MEDIANTE CODICE DI ATTIVAZIONE
		questa è la prima parte della procedura di attivazione di una nuova utenza;
		il passaggio successivo sarà l'inserimento dei dati aziendali da parte dell'
		utente appena creato.
	*/
	public function signUp(){
		
		$data = Input::all();


		$rules = array(
			'code' => 'required|exists:activation_codes,code,buyer_id,NULL',
			'name' => 'required',
			'last_name' => 'required',
			'phone' => 'required',
			'email' => 'required|email|unique:users',
			'user_name' => 'required|between:6,15|unique:users,user_name',
			'password' => 'required|between:8,16|confirmed',
			'password_confirmation' => 'required'

        );

        $messages = array(
		    'code.required'    => 'Devi inserire un codice di attivazione.',
		    'code.exists'    => 'Codice di attivazione non valido.',
		    'name.required'    => 'Inserire il proprio nome.',
		    'last_name.required'    => 'Inserire il proprio cognome.',
		    'phone.required'    => 'Inserire un recapito telefonico.',
		    'email.required'    => 'Inserire un indirizzo email.',
		    'email.email'    => 'Inserire un indirizzo email valido.',
		    'email.unique'    => 'Indirizzo email già presente in anagrafica.',
		    'user_name.required'    => 'Inserire un nome utente.',
		    'user_name.between'    => 'Il nome utente deve essere di minimo 6 caratteri e di 15 al massimo.',
		    'user_name.unique'    => 'Nome utente non disponibile.',
		    'password.required'    => 'Inserire una password.',
		    'password.between'    => 'La password deve essere di minimo 8 caratteri e di 16 al massimo.',
		    'password.confirmed'    => 'Le due password inserite non corrispondono.',
		    'password_confirmation.required'    => 'Devi inserire la conferma della password.'		    
		);


        $validator = Validator::make($data, $rules, $messages);

		if ( $validator->fails() ) {
			return Redirect::to('createUser/withErrors')->withInput()->withErrors($validator);
		}

		$code = ActivationCode::where('code', $data['code'])->first();

		if ($code->status_id != 1 && $code->status_id != 2) {
			$validator->getMessageBag()->add('code', 'Codice di attivazione non valido.');
			return Redirect::to('createUser/withErrors')->withInput()->withErrors($validator);
		}
		
		$user = new User;
		$user->name = $data['name'];
		$user->last_name = $data['last_name'];
		$user->email = $data['email'];
		$user->user_name = $data['user_name'];
		$user->password = Hash::make($data['password']);
		$user->role_id = 1;
		$user->user_status_id = 3;
		$user->confirmation_code = str_random(50);
		$user->save();

		$data['user_id'] = $user->id;

		$code->buyer_id = $data['user_id'];

		$data['license_id'] = $code->license_id;

		$company = App::make('CompanyController')->createCompany($data);

		$code->company_id = $company->id;

		$code->status_id = 3;
		
		$code->save();

		$mailParams = array('user' => $user, 'company' => $company, 'password' => $data['password'], 'aURI' => url('/').'/activate_account/'.$user->id.'/'.$user->confirmation_code);

		App::make('MailController')->sendMail('emails.welcome', $user->email, $company->name, 'Benvenuto su IBA!', $mailParams);

		return View::make('user.new.user_create_success', array('email' => $user->email, 'companyName' => $company->name));

		/*return 'Nuovo utente creato: user name= '.$user->user_name.'; password= '.$data['password'];*/


	}


	public function activateUser ($userId, $activationCode)	{
		
		if (!$userId || !$activationCode)
			return Redirect::to('/')->with(array('error' => 'Link non valido! Ti invitiamo a contattare il nostro customer care.'));
		
		$user = User::with('company')->find($userId);

		if (!count($user) > 0)
			return Redirect::to('/')->with(array('error' => 'Si è verificato un errore! Ti invitiamo a contattare il nostro customer care.'));

		if ($user->confirmation_code === $activationCode) {

			if ($user->user_status_id != 3)
				return Redirect::to('/')->with(array('error' => 'Questo account è gia\' attivo.'));

			$user->user_status_id = 2;
			$user->save();

			return Redirect::to('/')->with('success', 'Email verificata! Procedi al login da questa pagina quando vuoi...');
		} 

		return Redirect::to('/')->with(array('error' => 'Codice non valido o gia\' utilizzato.'));
	}

}
