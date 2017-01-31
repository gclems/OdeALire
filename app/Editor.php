<?php
namespace App;
use App;

class Editor extends OdeALireModel{
	const CREATED_AT = 'CreatedAt';
	const UPDATED_AT = 'UpdatedAt';

	protected $table = 'editor';
	protected $primaryKey = 'Id';

	public function books(){
    	return $this->hasMany('App\Book', 'Editor_Id', 'Id');
  	}
	
	public function creator(){
  	  return $this->belongsTo('App\User', 'Creator_Id', 'Id');
    }
}
