<?php

class CompanyData extends BaseModel {

	protected $table = 'company_data';


	public function developer(){
		return $this->belongsTo('User', 'developer_id');
	}


	public function company(){
		return $this->hasOne('Company', 'data_id');
	}

	public function images(){
		return $this->hasMany('CompanyDataImage', 'company_data_id');
	}

	public function socials(){
		return $this->hasMany('CompanyDataLink', 'company_data_id')->where('type_id', 1);
	}

	public function youtubeVideos(){
		return $this->hasMany('CompanyDataLink', 'company_data_id')->where('type_id', 2);
	}

}