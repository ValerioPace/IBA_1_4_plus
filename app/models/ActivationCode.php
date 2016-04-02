<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;


class ActivationCode extends BaseModel {
	
	use SoftDeletingTrait;

	protected $table = 'activation_codes';

	public function reseller(){
		return $this->belongsTo('User', 'reseller_id');
	}

	public function buyer(){
		return $this->belongsTo('User', 'buyer_id');
	}

	public function license(){
		return $this->belongsTo('License', 'license_id');
	}

	public function company(){
		return $this->belongsTo('Company', 'buyer_id');
	}

	public function batch(){
		return $this->belongsTo('ActivationCodeBatch', 'batch_id');
	}

}