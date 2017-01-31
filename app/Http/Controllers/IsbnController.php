<?php
namespace App\Http\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Client;
use App\Viewmodels\AjaxViewmodel;

class IsbnController extends Controller{

    const ISBNDBKEY = '650ATEOS';

    public function searchIsbn(Request $request){
        $success = true;
        $error = '';
        $view=null;

        try{
            $isbn = $request->input('isbn');
            $client = new Client([
                'base_uri' => 'http://isbndb.com/api/v2/json/'. self::ISBNDBKEY .'/book/',
            ]);

            $response = $client->request('GET',$isbn);
            $responseBody = json_decode($response->getBody(), true);

            if($response->getStatusCode() == 200){
                if(!isset($responseBody['error'])){
                    $view = $responseBody['data'][0];
                }
                else{
                    $view = $responseBody;
                }
            }
            else{
                throw new Exception($responseBody);
            }
        }
        catch(Exception $e){
            $success = false;
            $error = $e->getMessage();
        }

        return response()->json(new AjaxViewmodel($success, $error, $view));
    }

    public function searchIsbnWorldCat(Request $request){

    }

    public function searchTitle(Request $request){
      $title = $request->input('title');
      $title = str_replace(' ', '_', $title);

      $client = new Client([
          'base_uri' => 'http://isbndb.com/api/v2/json/'. self::ISBNDBKEY .'/books?q='.$title,
      ]);

      $response = $client->request('GET');
      return $response;
    }
}
