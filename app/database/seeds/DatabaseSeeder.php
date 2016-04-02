<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

//		$this->call('UserRoleTableSeeder');
//		$this->call('UserStatusTableSeeder');
//		$this->call('UserTableSeeder');
//		$this->call('LicenseTableSeeder');
//		$this->call('CompanyTableSeeder');
//		$this->call('StoreLinkTableSeeder');
//		$this->call('PlatformTableSeeder');
		//$this->call('ActivationCodesTableSeeder');
//		$this->call('CompanyStatusTableSeeder');
//		$this->call('CompanyDataLinkTypeTableSeeder');
        $this->call('PublishedMobileAppTableSeeder');
	}

}

class UserRoleTableSeeder extends Seeder {

    public function run(){
    	$role = new UserRole;
		$role->type = 'Owner';
		$role->save();

    	$role = new UserRole;
		$role->type = 'Administrator';
		$role->save();

    	$role = new UserRole;
		$role->type = 'User';
		$role->save();

    	$role = new UserRole;
		$role->type = 'Guest';
		$role->save();

    	$role = new UserRole;
		$role->type = 'Developer';
		$role->save();
    }

}

class UserStatusTableSeeder extends Seeder {

    public function run(){

    	$ustatus = new UserStatus;
		$ustatus->description = 'active';
		$ustatus->save();

    	$ustatus = new UserStatus;
		$ustatus->description = 'revision';
		$ustatus->save();

    	$ustatus = new UserStatus;
		$ustatus->description = 'pending';
		$ustatus->save();

    	$ustatus = new UserStatus;
		$ustatus->description = 'locked';
		$ustatus->save();
    }

}

class UserTableSeeder extends Seeder {

    public function run(){

    	$user = new User;
		$user->user_name = 'admin';
		$user->password = Hash::make('admin');
		$user->name = 'Domenico';
		$user->last_name = 'Putignano';
		$user->email = 'd.putignano@ermesitalia.it---';
		$user->role_id = 2;
		$user->user_status_id = 1;
		$user->save();

    	$user = new User;
		$user->user_name = 'andreadp';
		$user->password = Hash::make('andreadp');
		$user->name = 'Andrea';
		$user->last_name = 'Della Porta';
		$user->email = 'sfaragnaus@gmail.com';
		$user->role_id = 5;
		$user->user_status_id = 1;
		$user->save();

    	$user = new User;
		$user->user_name = 'salvatoreb';
		$user->password = Hash::make('salvatoreb');
		$user->name = 'Salvatore';
		$user->last_name = 'Bruni';
		$user->email = 'salvatore.bruni@gmail.com';
		$user->role_id = 5;
		$user->user_status_id = 1;
		$user->save();

    	/*$user = new User;
		$user->user_name = 'test792';
		$user->password = Hash::make('test');
		$user->role_id = 1;
		$user->user_status_id = 1;
		$user->save();

    	$user = new User;
		$user->user_name = 'viniba';
		$user->password = Hash::make('viniba');
		$user->role_id = 1;
		$user->user_status_id = 1;
		$user->save();*/
    }

}

class LicenseTableSeeder extends Seeder {

    public function run(){
    	$license = new License;
		$license->name = 'Standard';
		$license->max_event_number = 10;
		$license->save();

    	$license = new License;
		$license->name = 'Elite';
		$license->save();
    }

}

class CompanyTableSeeder extends Seeder {

    public function run(){

    	

    	/*$company = new Company;
		$company->code = 'dYxIPSiloMUtFncTII1J2t44rVt1wA3JzBrotKKSwElc1y5Kg7';
		$company->user_id = 2;
		$company->name = 'Test Company';
		$company->download_link_tag = 'cerocchi';
		$company->company_status_id = 1;
		$company->save();

    	$company = new Company;
		$company->code = 'nD44CFruHMUPGbj213sd1kMiHygU41biNIThN36jWE2JPN8RZF';
		$company->user_id = 3;
		$company->name = 'VinIBA';
		$company->download_link_tag = 'viniba';
		$company->company_status_id = 1;
		$company->save();*/
    }

}

class StoreLinkTableSeeder extends Seeder {

    public function run(){

    	/*$sLink = new StoreLink;
		$sLink->company_id = 2;
		$sLink->save();

    	$sLink = new StoreLink;
		$sLink->company_id = 3;
		$sLink->android = 'https://play.google.com/store/apps/details?id=air.com.vinIBA';
		$sLink->ios = 'http://install.diawi.com/inuzvH';
		$sLink->save();*/
    }

}

class PlatformTableSeeder extends Seeder {

    public function run(){
    	$platform = new Platform;
		$platform->name = 'Android';
		$platform->code = 'PxgLiaL7dBgTYUzUyHZRNGIUlT5NIabyHrkZC57PHoJGiiAQZA';
		$platform->save();
		
    	$platform = new Platform;
		$platform->name = 'iOS';
		$platform->code = 'lpJkwsXpIGgLLAROXQoDbvEMblCgeTjAj2VQuTgdAwZl7Q95Gy';
		$platform->save();
		
    	$platform = new Platform;
		$platform->name = 'Windows Phone';
		$platform->code = 'CSmvdXsUoJgGoSgZJj6peECqWF6WpJZ1BmxPj0KIK1o8UF8DYf';
		$platform->save();
    }

}

class ActivationCodesTableSeeder extends Seeder {

    public function run(){
    	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    	for ($i=0; $i < 100; $i++) { 
    		$code = new ActivationCode;
    		$randomString = '';
		    for ($j = 0; $j < 24; $j++) {
		        $randomString .= $characters[rand(0, strlen($characters) - 1)];
		    }
		    
		    $code->code = $randomString;
		    $code->save();
    	}
    }

}


class CompanyStatusTableSeeder extends Seeder {

    public function run(){
    	$cstatus = new CompanyStatus;
		$cstatus->status = 'published';
		$cstatus->save();

    	$cstatus = new CompanyStatus;
		$cstatus->status = 'develop';
		$cstatus->save();

    	$cstatus = new CompanyStatus;
		$cstatus->status = 'revision';
		$cstatus->save();

    	$cstatus = new CompanyStatus;
		$cstatus->status = 'submitted';
		$cstatus->save();

    	$cstatus = new CompanyStatus;
		$cstatus->status = 'pending';
		$cstatus->save();
    }

}


class CompanyDataLinkTypeTableSeeder extends Seeder {

    public function run(){
    	$cdlType = new CompanyDataLinkType;
		$cdlType->type = 'Social Network Profile';
		$cdlType->save();

    	$cdlType = new CompanyDataLinkType;
		$cdlType->type = 'Youtube Video';
		$cdlType->save();
    }

}

class PublishedMobileAppTableSeeder extends Seeder {
    public function run(){
    	$publishedApp = new PublishedMobileApp;
		$publishedApp->name = 'IIS Via Salvini';
		$publishedApp->android_version_code = 1;
		$publishedApp->android_version_number = '2.0.1';
		$publishedApp->google_cloud_apikey='AIzaSyA81yS1VSj1WZXY_T';
		$publishedApp->ios_cert_filename='ck.pem'
		$publishedApp->company_id = 2;
		$publishedApp->save();
    }

}
