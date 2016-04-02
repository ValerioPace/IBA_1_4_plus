<?php

class StoreLink extends BaseModel {

	public $timestamps = false;


	public function company(){
		return $this->belongsTo('Company', 'company_id');
	}

	public function devices(){
		return $this->hasMany('Device', 'store_link_id');
	}

}