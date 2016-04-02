<?php

class RedirectController extends BaseController {

	public function deviceRedirect($companyTag){

		$link = StoreLink::whereHas('company', function($query) use ($companyTag){
			$query->where('download_link_tag', $companyTag);
		})->first();


		if (Agent::isMobile() || Agent::isTablet()){
			if (Agent::is('Windows'))
				header('Location: ' . $link->w_phone, true, 303);
			else if (Agent::is('AndroidOS'))
				header('Location: ' . $link->android, true, 303);
			else if (Agent::is('OS X'))
				header('Location: ' . $link->ios, true, 303);
			else
				echo "Dispositivo non supportato!";	
		} else
			echo "Errore!";

		die();
	}

}
