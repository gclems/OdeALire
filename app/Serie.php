<?php
namespace App;
use App;

class Serie extends OdeALireModel{
	protected $table = 'serie';
	protected $primaryKey = 'Id';

  public function books(){
    return $this->hasMany('App\Book', 'Serie_Id', 'Id');
  }
  
  public function creator(){
	return $this->belongsTo('App\User', 'Creator_Id', 'Id');
  }
}
