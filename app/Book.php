<?php
namespace App;
use App;

class Book extends OdeALireModel{
	const CREATED_AT = 'CreatedAt';
	const UPDATED_AT = 'UpdatedAt';

	protected $table = 'book';
	protected $primaryKey = 'Id';

	public function serie(){
		return $this->belongsTo('App\Serie', 'Serie_Id', 'Id');
	}

	public function editor(){
		return $this->belongsTo('App\Editor', 'Editor_Id', 'Id');
	}

	public function authors(){
		return $this->belongsToMany('App\Author', 'book_author', 'Book_Id', 'Author_Id');
	}

	public function creator(){
		return $this->belongsTo('App\User', 'Creator_Id', 'Id');
	}

	public function loans(){
		return $this->hasMany('App\Loan', 'Book_Id', 'Id');
	}
}
