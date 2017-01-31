<?php
namespace App;
use App;

class Borrower extends OdeALireModel{
	const CREATED_AT = 'CreatedAt';
	const UPDATED_AT = 'UpdatedAt';

	protected $table = 'borrower';
	protected $primaryKey = 'Id';

	public function creator(){
		return $this->belongsTo('App\User', 'Creator_Id', 'Id');
	}
}
