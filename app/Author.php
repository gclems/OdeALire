<?php
namespace App;
use App;

class Author extends OdeALireModel{
	const CREATED_AT = 'CreatedAt';
	const UPDATED_AT = 'UpdatedAt';

	protected $table = 'author';
	protected $primaryKey = 'Id';

  public function books(){
    return $this->belongsToMany('App\Book', 'book_author', 'Author_Id', 'Book_Id');
  }

  public function creator(){
	  return $this->belongsTo('App\User', 'Creator_Id', 'Id');
  }
}
