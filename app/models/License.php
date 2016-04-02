<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class License extends BaseModel {
	
	use SoftDeletingTrait;

	public function company(){
		return $this->hasMany('Company', 'license_id');
	}
}