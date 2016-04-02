<?php

class ActivationCodeController extends BaseController {

	public function newBatch () {
		if (Auth::check() && Auth::user()->role_id == 2) {
			if (!Input::has('amount'))
				$amount = 1;
			else
				$amount = Input::get('amount') < 50 ? Input::get('amount') : 50;

			$batch = new ActivationCodeBatch;
			if (Input::has('name'))
				$batch->name = Input::get('name');
			$batch->save();

			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	    	for ($i=0; $i < $amount; $i++) { 
	    		$code = new ActivationCode;

	    		$code->batch_id = $batch->id;

	    		$randomString = '';
			    for ($j = 0; $j < 24; $j++) {
			        $randomString .= $characters[rand(0, strlen($characters) - 1)];
			    }
			    
			    $code->code = $randomString;
			    $code->save();
	    	}

	    	return View::make('admin.activation_codes.container');

		} else
			return Response::make('Non autenticato!', 401);
	}

	public function getBatchName ()	{
		if (Auth::check() && Auth::user()->role_id == 2) {
			
			if (Input::has('batch_id')){
				// Log::info(Input::get('batch_id'));
				$batch = ActivationCodeBatch::find(Input::get('batch_id'));

				if ($batch)
					return $batch->name;
			}

			return null;

		} else
			return Response::make('Non autenticato!', 401);
	}

	public function setStatus ()	{
		if (Auth::check() && Auth::user()->role_id == 2) {
			
			if (Input::has('code_id') && Input::has('status')){

				$newStatus = Input::get('status');

				$code = ActivationCode::find(Input::get('code_id'));

				if ($code) {

					if ($newStatus == 2){
						if ($code->status_id == 1)
							$code->status_id = $newStatus;
						if (Input::has('customer_name'))
							$code->customer_name = Input::get('customer_name');
					} else if ($newStatus == 4){
						if ($code->status_id == 1 || $code->status_id == 2)
							$code->status_id = $newStatus;
					}

					$code->save();

					return $code->batch_id;
				}
			}

			return 'ciao';

		} else
			return Response::make('Non autenticato!', 401);
	}
}
