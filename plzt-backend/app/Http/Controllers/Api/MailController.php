<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Webklex\IMAP\Facades\Client;

class MailController extends Controller
{
    public function index(){
    
    	$client = Client::account('gmail');
		$client->connect();
    	
    	return true;
    }
}
