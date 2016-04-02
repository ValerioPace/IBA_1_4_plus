<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Company extends BaseModel {
	
	use SoftDeletingTrait;
	protected $table = 'companies';

	public function license(){
		return $this->belongsTo('License', 'license_id');
	}

	public function user(){
		return $this->belongsTo('User', 'user_id');
	}

	public function companyData(){
		return $this->belongsTo('CompanyData', 'data_id');
	}




	public function evento(){
		return $this->hasMany('Evento', 'company_id');
	}

	public function devices(){
		return $this->hasMany('Device', 'company_id');
	}

	public function activationCode(){
		return $this->hasOne('ActivationCode', 'company_id');
	}
}