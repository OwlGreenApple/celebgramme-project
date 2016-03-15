<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

use Celebgramme\Helpers\GeneralHelper;
use Carbon\Carbon;

class Client extends Model {

	protected $table = 'clients';
	public $timestamps = false;
  
	protected $fillable = ['used',];
													 
	protected function getClientId($name)
	{
		$client = Client::where('name','=',$name)
							// ->where("used","<",500)
							->first();
		if (!is_null($client)) {
			$client->used += 1;
			$client->save();
			return $client->client_id;
		} else {
			return "0";
		}
	}
}
