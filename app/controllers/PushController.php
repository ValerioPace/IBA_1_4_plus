<?php

class PushController extends BaseController {

	public function testSendAll(){

		if ( !(Auth::check() && Auth::user()->role_id == 5 ) )
			return Response::make('Non autenticato!', 401);

		$company = Company::find(Input::get('companyId'));

		$devices = Device::where('company_id', Input::get('companyId'))->get();

		if (count($devices) > 0){

			$event = new Evento;
			$event->title = Input::has('title') ? Input::get('title') : 'Notifica Test';

			try {
				$this->sendAndro($company, $devices, $event);
			} catch (Exception $e){
				Log::info('=== ERRORE PUSH ANDROID su: ['.$company->name.'] ===');
				Log::info($e);
			}
			try {
				$this->sendIOS($company, $devices, $event);
			} catch (Exception $e){
				Log::info('=== ERRORE PUSH IOS su: ['.$company->name.'] ===');
				Log::info($e);
				return null;
			}
		}

		return 'OK';

	}

	public function sendAll($event){

		$companyId = $event->company_id;

		$company = Company::find($companyId);

		$devices = Device::where('company_id', $companyId)->get();

		if (count($devices) > 0){

			$event->pushed_devices = count($devices);
			$event->save();

			try {
				$this->sendAndro($company, $devices, $event);
			} catch (Exception $e){
				Log::info('=== ERRORE PUSH ANDROID su: ['.$company->name.'] ===');
				Log::info($e);
			}
			try {
				$this->sendIOS($company, $devices, $event);
			} catch (Exception $e){
				Log::info('=== ERRORE PUSH IOS su: ['.$company->name.'] ===');
				Log::info($e);
				return null;
			}
		}

		return 'OK';

	}

	private function sendAndro($company, $devices, $event){

		$xCompany = array(
			'd_putignano',
			'tesav',
			// 'viniba',
			'p_mariola',
			'g_di_leo',
			'travel_clinic',
			'il_girasole',
			'colap',
			'collura',
			't_roma',
			'p_milvio',
		);

		if (in_array($company->download_link_tag, $xCompany)) {

			$url = Laracurl::buildUrl('http://www.msop.it/'.$company->download_link_tag.'.php', ['msg' => $event->title]);

			$response = Laracurl::get($url);

			Log::info('-----------CURL RESPONSE-----------');
			Log::info('Company: ['.$company->name.']; RESPONSE:');
			Log::info($response->body);
			Log::info('===========END CURL RESPONSE=============');

		} else {

			$message = PushNotification::Message($event->title, array(
				'title'		=> $event->title,
				'badge' => 1
			));
	
			$tks = array();
	
			foreach ($devices as $key => $device) {
				if ($device->platform_id == 1)
					$tks[] = PushNotification::Device($device->token);
			}
	
			$tokens = PushNotification::DeviceCollection($tks);
	
			PushNotification::app($company->code.'_ANDROID')
	            ->to($tokens)
	            ->send($message);
		}

        Log::info('NOTIFICHE ANDROID INVIATE!'); // Device notificati: '.json_encode($devices));

	}

	private function sendIOS($company, $devices, $event){

		$message = $event->title;

		if (file_exists(public_path().'/cert/'.$company->download_link_tag.'/ck.pem'))
			foreach ($devices as $key => $device) {
				if ($device->platform_id == 2)
					$this->iOSender($company, $device, $message);
			}
		else
			Log::info('=== CERTIFICATO IOS NON PRESENTE per: ['.$company->name.'] ===');

	}


	private function iOSender($company, $device, $message){

		// Put your device token here (without spaces):
		$deviceToken = $device->token;

		// Put your private key's passphrase here:
		$passphrase = '1994Fede';


		////////////////////////////////////////////////////////////////////////////////

		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', public_path().'/cert/'.$company->download_link_tag.'/ck.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

		// Open a connection to the APNS server
		$fp = stream_socket_client(
			'ssl://gateway.push.apple.com:2195', $err,
			//'ssl://gateway.sandbox.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

		if (!$fp)
			exit("Failed to connect: $err $errstr" . PHP_EOL);

		//echo 'Connected to APNS' . PHP_EOL;

		// Create the payload body
		$body['aps'] = array(
			'alert' => $message,
			'badge' => 1,
			'sound' => 'default'
			);

		// Encode the payload as JSON
		$payload = json_encode($body);

		// Build the binary notification
		$framedata = chr(1) . pack('n', 32) . pack('H*', $deviceToken) 
			. chr(2) . pack('n', strlen($payload)) . $payload
			. chr(3) . pack('n', 4) . '1234'
			. chr(4) . pack('n', 4) . pack('N', 1457376267)
			. chr(5) . pack('n', 1) . chr(10);
		$msg = chr(2) . pack('N', strlen($framedata)) . $framedata;

		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));

		if (!$result)
			Log::info('Message not delivered' . PHP_EOL);
		else
			Log::info('Message successfully delivered - Company: '.$company->name.' - Device ID: '.$device->id.' - Device token: '.$device->token.'; '. PHP_EOL);

		//read response
		/*$contents = '';
		while (!feof($fp)) 
		{
		   $contents = fread($fp, 1);
		   echo bin2hex($contents).' ';
		}*/
        
		//echo PHP_EOL;

		// Close the connection to the server
		fclose($fp);

        // Log::info('NOTIFICA IOS INVIATA!');
	}

}
