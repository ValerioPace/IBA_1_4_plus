<?php

use Carbon\Carbon;

class EventController extends BaseController {

	public function createNew(){

		if (Auth::check()) {

			$rules = array(
				'title'  =>  'required|max:60',
			    'description'    =>  'required|max:2000',
				'flyer'  =>  'image|mimes:jpeg,png|max:6144',
			    'activated_at'    =>  'required|date_format:d/m/Y',
				'expire_on'  =>  'date_format:d/m/Y',
			    'contact'    =>  'required',
				'phone'  =>  'required',
			    'email'    =>  'required|email'
			);

	        $messages = array(
			    'title.required'    => 'Non puoi creare un evento senza titolo.',
			    'title.max'    => 'Non puoi inserire piu\' di 60 caratteri per il titolo.',
			    'description.required'    => 'Non puoi creare un evento senza parte descrittiva.',
			    'description.max'    => 'Non puoi inserire piu\' di 2000 caratteri per la descrizione.',
		    	'flyer.image'    => 'Il file caricato non e\' un immagine.',
		    	'flyer.mimes'    => 'I formati di immagine supportati sono: jpeg e png.',
		    	'flyer.max'    => 'La dimensione massima delle immagini e\' di 6MB.',
			    'activated_at.required'    => 'Non puoi creare un evento senza data di inizio.',
			    'contact.required'    => 'E\' necessario specificare un referente.',
			    'phone.required'    => 'E\' necessario specificare un numero di telefono.',
			    'email.required'    => 'E\' necessario specificare un indirizzo email.',
			    'email.email'    => 'Quello inserito non e\' un indirizzo email valido.'
			);

			$validator = Validator::make(Input::all(), $rules, $messages);

			if ($validator->fails()) {
			    return Redirect::to('console/user.active.events.new_event')->withInput()->withErrors($validator);
			}

			$companyId = Company::where('user_id', Auth::user()->id)->first()->id;


			$event = new Evento;
			$event->company_id = $companyId;
			$event->title = Input::get('title');
			$event->description = Input::get('description');

			if (Input::hasFile('flyer')){

				$file = Input::file('flyer');

			    $img = Image::make($file);

			    if ($img->mime() == 'png')
			    	$img->encode('jpg');


				$characters = '0123456789';
				$randomString = '';
			    for ($j = 0; $j < 5; $j++) {
			        $randomString .= $characters[rand(0, strlen($characters) - 1)];
			    }

				$fileName = \Carbon\Carbon::now()->format('YmdHis').'_'.$randomString.'.jpg';

				$img->save(public_path().'/uploads/flyer/full_size/'.$fileName, 50);
			    
			    $img->fit(140, 140, function ($constraint) {
				    $constraint->upsize();
				});

				$img->save(public_path().'/uploads/flyer/thumbnail/thumb_'.$fileName);

				$event->image = $fileName;

			    /*$file = Input::file('flyer');

				$characters = '0123456789';
				$randomString = '';
			    for ($j = 0; $j < 5; $j++) {
			        $randomString .= $characters[rand(0, strlen($characters) - 1)];
			    }

				$fileName = \Carbon\Carbon::now()->format('YmdHis').'_'.$randomString.'.'.$file->getClientOriginalExtension();
			    
			    $file->move('uploads/flyer/full_size', $fileName);

			    $img = Image::make(public_path().'/uploads/flyer/full_size/'.$fileName);

				$img->fit(140, 140, function ($constraint) {
				    $constraint->upsize();
				});

				$img->save(public_path().'/uploads/flyer/thumbnail/thumb_'.$fileName);

				$event->image = $fileName;*/
			}


			$dtime_activated_at = DateTime::createFromFormat("d/m/Y", Input::get('activated_at'));
			$event->activated_at = date('Y-m-d H:i:s', $dtime_activated_at->getTimestamp());
			
			if (Input::has('expire_on')){
				$dtime_expire_on = DateTime::createFromFormat("d/m/Y", Input::get('expire_on'));
				$event->expire_on = date('Y-m-d H:i:s', $dtime_expire_on->getTimestamp());
			}


			$event->contact = Input::get('contact');
			$event->phone = Input::get('phone');
			$event->email = Input::get('email');

			$event->product_id = Input::get('product_id');
			$event->product_name = Input::get('product_name');

			if (Input::has('push') && Input::get('push') == 'yes'){
				$event->push_flag = true;
				App::make('PushController')->sendAll($event);
			} else
				$event->push_flag = false;

			$event->save();				

			return View::make('user.active.events.active.container', array('userId' => Auth::user()->id));
						
		}

		return Response::make('Non autenticato!', 401);

	}


	public function edit(){
				
		if (Auth::check()){

			$formData = Input::all();

			if (empty($formData))
				return 'error';

			if ($formData['action_button'] === 'update' || $formData['action_button'] === 'restore')
				return $this->updateAndRestore($formData);
			elseif ($formData['action_button'] === 'delete')
				return $this->delete($formData);
			elseif ($formData['action_button'] === 'permanent_delete')
				return $this->permanent_delete($formData);
			else
				return 'error';
			
		}

		return Response::make('Non autenticato!', 401);
	}


	public function updateAndRestore($formData){

		$rules = array(
			'title'  =>  'required|max:60',
		    'description'    =>  'required|max:2000',
			'flyer'  =>  'image|mimes:jpeg,png|max:6144',
		    'activated_at'    =>  'required|date_format:d/m/Y',
			'expire_on'  =>  'date_format:d/m/Y',
		    'contact'    =>  'required',
			'phone'  =>  'required',
		    'email'    =>  'required'
		);

	    $messages = array(
		    'title.required'    => 'Non puoi creare un evento senza titolo.',
		    'title.max'    => 'Non puoi inserire piu\' di 60 caratteri per il titolo.',
		    'description.required'    => 'Non puoi creare un evento senza parte descrittiva.',
		    'description.max'    => 'Non puoi inserire piu\' di 2000 caratteri per la descrizione.',
	    	'flyer.image'    => 'Il file caricato non e\' un immagine.',
	    	'flyer.mimes'    => 'I formati di immagine supportati sono: jpeg e png.',
	    	'flyer.max'    => 'La dimensione massima delle immagini e\' di 6MB.',
		    'activated_at.required'    => 'Non puoi creare un evento senza data di inizio.',
		    'contact.required'    => 'E\' necessario specificare un referente.',
		    'phone.required'    => 'E\' necessario specificare un numero di telefono.',
		    'email.required'    => 'E\' necessario specificare un indirizzo email.'
		);

		$validator = Validator::make(Input::all(), $rules, $messages);

		if ($validator->fails()) {
			if ($formData['action_button'] === 'update')
		    	return Redirect::to('console/user.active.events.active.details')->withInput()->withErrors($validator);
		    else
		    	return Redirect::to('console/user.active.events.expired.details')->withInput()->withErrors($validator);
		}

		$event = Evento::withTrashed()->find($formData['event_id']);
		$event->title = $formData['title'];
		$event->description = $formData['description'];
		
		if (array_key_exists('flyer', $formData) && $formData['flyer'] != ''){

			$file = $formData['flyer'];;

		    $img = Image::make($file);

		    if ($img->mime() == 'png')
		    	$img->encode('jpg');


			$characters = '0123456789';
			$randomString = '';
		    for ($j = 0; $j < 5; $j++) {
		        $randomString .= $characters[rand(0, strlen($characters) - 1)];
		    }

			$fileName = \Carbon\Carbon::now()->format('YmdHis').'_'.$randomString.'.jpg';

			$img->save(public_path().'/uploads/flyer/full_size/'.$fileName, 50);
		    
		    $img->fit(140, 140, function ($constraint) {
			    $constraint->upsize();
			});

			$img->save(public_path().'/uploads/flyer/thumbnail/thumb_'.$fileName);

			$event->image = $fileName;

		    /*$file = $formData['flyer'];

			$characters = '0123456789';
			$randomString = '';
		    for ($j = 0; $j < 5; $j++) {
		        $randomString .= $characters[rand(0, strlen($characters) - 1)];
		    }

			$fileName = \Carbon\Carbon::now()->format('YmdHis').'_'.$randomString.'.'.$file->getClientOriginalExtension();
		    
		    $file->move('uploads/flyer/full_size', $fileName);

		    $img = Image::make(public_path().'/uploads/flyer/full_size/'.$fileName);

			$img->fit(140, 140, function ($constraint) {
			    $constraint->upsize();
			});

			$img->save(public_path().'/uploads/flyer/thumbnail/thumb_'.$fileName);

			$event->image = $fileName;*/
		}


		$dtime_activated_at = DateTime::createFromFormat("d/m/Y", $formData['activated_at']);
		$event->activated_at = date('Y-m-d H:i:s', $dtime_activated_at->getTimestamp());

		
		if (Input::has('expire_on')){
			$dtime_expire_on = DateTime::createFromFormat("d/m/Y", $formData['expire_on']);
			$event->expire_on = date('Y-m-d H:i:s', $dtime_expire_on->getTimestamp());
		} else
			$event->expire_on = null;					


		$event->contact = $formData['contact'];
		$event->phone = $formData['phone'];
		$event->email = $formData['email'];

		if (array_key_exists('product_id', $formData))
			$event->product_id = $formData['product_id'];
		if (array_key_exists('product_name', $formData))
			$event->product_name = $formData['product_name'];

		$event->deleted_at = null; // server per il restore

		$event->save();

		if (array_key_exists('push', $formData) && $formData['push'] == 'yes') {
			// cancello tutti i record che segnano la lettura di questo evento da parte dei vari device
			EventReader::where('event_id', $event->id)->delete();

			App::make('PushController')->sendAll($event);
		}

		return Redirect::to('console/user.active.events.active.details')->withInput();

	}

	public function getEventDetails($eventId, $token){

		$device = Device::where('token', $token)->first();

		if (!count($device)>0)
			return 'TokenNotRecognizedException';

		$event = Evento::find($eventId);

		if (!count($event)>0)
			return 'EventNotRecognizedException';

		EventReader::firstOrCreate(array('event_id' => $eventId, 'device_id' => $device->id));

		$event->is_read = true;
			

		$event->event_id = $event->id;

		if ($event->image)
			$event->image_tag = $event->image;
		else
			$event->image_tag = '1-px-default-image.png';
		
		$event->activated_at = Carbon::parse($event->activated_at)->format('d/m/Y');

		if ($event->expire_on && $event->expire_on != '')
			$event->expire_on = Carbon::parse($event->expire_on)->format('d/m/Y');
		else
			$event->expire_on = '';

		if (!$event->product_id || $event->product_id == '')
			$event->product_id = '0';

		if (!$event->product_name || $event->product_name == '')
			$event->product_name = '0';

		unset($event->id);
		unset($event->image);
		unset($event->created_at);
		unset($event->updated_at);
		unset($event->deleted_at);
		unset($event->push_flag);

		return $event;
	}

	public function delete ($formData){
		$event = Evento::find($formData['event_id'])->delete();

		EventReader::where('event_id', $formData['event_id'])->delete();

		sleep(2);

		return 'OK';
	}

	public function permanent_delete ($formData){
		$event = Evento::withTrashed()->find($formData['event_id'])->forceDelete();

		sleep(2);

		return 'OK';
	}


	public function getActiveEventsByToken($token){

		$device = Device::where('token', $token)->first();

		if (!count($device)>0)
			return 'TokenNotRecognizedException';

		$events = Evento::where('company_id', $device->company_id)
		->orderBy('updated_at', 'DISC')
		->get();

		foreach ($events as $key => $event) {

			if (EventReader::where('event_id', $event->id)->where('device_id', $device->id)->count() > 0)
				$event->is_read = true;
			else
				$event->is_read = false;
			

			$event->event_id = $event->id;

			if ($event->image)
				$event->image_tag = $event->image;
			else
				$event->image_tag = '1-px-default-image.png';
			
			$event->activated_at = Carbon::parse($event->activated_at)->format('d/m/Y');

			if ($event->expire_on && $event->expire_on != '')
				$event->expire_on = Carbon::parse($event->expire_on)->format('d/m/Y');
			else
				$event->expire_on = '';

			unset($event->id);
			unset($event->image);
			unset($event->created_at);
			unset($event->updated_at);
			unset($event->deleted_at);
			unset($event->push_flag);
		}

		return $events;
	}


	public function getDeletedEvents(){
		$events = Evento::onlyTrashed()
		->whereHas('company', function ($query) use ($userId){
			$query->whereHas('user', function ($query) use ($userId){
				$query->where('id', $userId);
			});
		})
		->orderBy('updated_at', 'DISC')
		->get();
	}



	public function getFlyerFullByEvent($eventId){

		$event = Evento::withTrashed()->find($eventId);

		try {

			if (!$event->image)
				$pathToFile = public_path().'/dist/img/1-px-default-image.png';
			else
				$pathToFile = public_path().'/uploads/flyer/full_size/'.$event->image;

			$image = File::get($pathToFile);
		} catch (Exception $e){
			return null;
		}

		return Response::make($image, 200, array('content-type' => 'image/jpg'));
	}

	public function getFlyerThumbByEvent($eventId){

		$event = Evento::withTrashed()->find($eventId);

		try {

			if (!$event->image)
				$pathToFile = public_path().'/dist/img/1-px-default-image.png';
			else
				$pathToFile = public_path().'/uploads/flyer/thumbnail/thumb_'.$event->image;			
			
			$image = File::get($pathToFile);
		} catch (Exception $e){
			return null;
		}

		return Response::make($image, 200, array('content-type' => 'image/jpg'));
	}

	public function getFlyerFullByTag($imageTag){

		try {
			if ($imageTag == '1-px-default-image.png')
				$pathToFile = public_path().'/dist/img/1-px-default-image.png';
			else
				$pathToFile = public_path().'/uploads/flyer/full_size/'.$imageTag;

			$image = File::get($pathToFile);
		} catch (Exception $e){
			return null;
		}

		return Response::make($image, 200, array('content-type' => 'image/jpg'));
	}

	public function getFlyerThumbByTag($imageTag){

		try {
			if ($imageTag == '1-px-default-image.png')
				$pathToFile = public_path().'/dist/img/1-px-default-image.png';
			else
				$pathToFile = public_path().'/uploads/flyer/thumbnail/thumb_'.$imageTag;
			
			$image = File::get($pathToFile);
		} catch (Exception $e){
			return null;
		}

		return Response::make($image, 200, array('content-type' => 'image/jpg'));
	}



}
