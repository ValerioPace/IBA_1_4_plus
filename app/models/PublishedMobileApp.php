<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class PublishedMobileApp extends BaseModel {
	
	use SoftDeletingTrait;
	
	protected $table = 'published_app';

	public function company(){
		return $this->belongsTo('Company', 'company_id');
	}

    public function developer(){
        return $this->belongsTo('User','developer_id');
    }

	public function deviceInstallations(){
		return $this->hasMany('Device', 'device_id');
	}
}
