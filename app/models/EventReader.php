<?php

class EventReader extends BaseModel {

	public $timestamps = false;

	protected $fillable = array('event_id', 'device_id');


	public function event(){
		return $this->belongsTo('Evento', 'event_id');
	}

	public function devices(){
		return $this->belongsTo('Device', 'device_id');
	}

}