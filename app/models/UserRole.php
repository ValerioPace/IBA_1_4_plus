<?php

class UserRole extends BaseModel {

	public $timestamps = false;
	protected $table = 'user_roles';


	public function user(){
		return $this->hasMany('User', 'role_id');
	}

}