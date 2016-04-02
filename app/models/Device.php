<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Device extends BaseModel {
	
	use SoftDeletingTrait;

	public function company(){
		return $this->belongsTo('Company', 'company_id');
	}

	public function platform(){
		return $this->belongsTo('Platform', 'platform_id');
	}

	
	public function readers(){
		return $this->hasMany('EventReader', 'device_id');
	}
}