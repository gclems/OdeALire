<?php
namespace App;
use App;

class Loan extends OdeALireModel{
	const CREATED_AT = 'CreatedAt';
	const UPDATED_AT = 'UpdatedAt';

	protected $table = 'loan';
	protected $primaryKey = 'Id';

  protected $dates = [
        'CreatedAt',
        'UpdatedAt',
        'LentAt',
        'ReturnPlannedAt',
        'ReturnedAt'
    ];

	public function borrower(){
		return $this->belongsTo('App\Borrower', 'Borrower_Id', 'Id');
	}

	public function book(){
		return $this->belongsTo('App\Book', 'Book_Id', 'Id');
	}

	public function creator(){
		return $this->belongsTo('App\User', 'Creator_Id', 'Id');
	}
}
