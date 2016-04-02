<?php

class DeviceController extends BaseController {

	public function setToken($platformCode, $companyCode, $deviceToken){

		$platform = Platform::where('code', $platformCode)->first();
		$company = Company::where('code', $companyCode)->first();

		if (!count($platform) > 0 || !count($company) > 0 || strcasecmp($deviceToken, 'null') == 0)
			return Response::make('Codici non validi', 400);

		$device = Device::where('platform_id', $platform->id)->where('company_id', $company->id)->where('token', $deviceToken)->first();

		if (!count($device) > 0){

			$device = new Device;
			$device->platform_id = $platform->id;
			$device->company_id = $company->id;
			$device->token = $deviceToken;
			$device->save();

			// Log::info('NUOVO TOKEN SETTATO: piattaforma => '.$platform->name.', azienda => '.$company->name.', token => '.$deviceToken);
		} else{
			$device->touch();
			// Log::info('TANTATIVO DI SETTARE UN TOKEN GIA\' ESISTENTE: piattaforma => '.$platform->name.', azienda => '.$company->name.', token => '.$deviceToken);
		}


		// tutti gli eventi di una company
		$totalEvents = Evento::where('company_id', $device->company_id)->count();

		// gli eventi di una company letti da questo specifico device
		$readEvents = Evento::where('company_id', $device->company_id)
		->whereHas('readers', function ($query) use ($device) {
			$query->where('device_id', $device->id);
		})
		->count();

		return $totalEvents-$readEvents;

	}

}
