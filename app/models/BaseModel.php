<?php
use Carbon\Carbon;

class BaseModel extends Eloquent {

    public function getCreatedAtAttribute($attr) {        
        return Carbon::parse($attr)->format('d-m-Y H:i');
    }

    public function getUpdatedAtAttribute($attr) {        
        return Carbon::parse($attr)->format('d-m-Y H:i');
    }
}