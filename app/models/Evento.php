<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;


class Evento extends BaseModel {
	
	use SoftDeletingTrait;

	protected $table = 'eventi';

	public function company(){
		return $this->belongsTo('Company', 'company_id');
	}

	public function readers(){
		return $this->hasMany('EventReader', 'event_id');
	}
}