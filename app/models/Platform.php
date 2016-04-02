<?php

class Platform extends BaseModel {

	public $timestamps = false;


	public function user(){
		return $this->hasMany('Device', 'platform_id');
	}

}