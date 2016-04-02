<?php

class ActivationCodeBatch extends BaseModel {

	public function activationCodes(){
		return $this->hasMany('ActivationCode', 'batch_id');
	}

}