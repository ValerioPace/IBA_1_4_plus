<?php

class UserStatus extends BaseModel {

	public $timestamps = false;
	protected $table = 'user_status';


	public function user(){
		return $this->hasMany('User', 'user_status_id');
	}

}