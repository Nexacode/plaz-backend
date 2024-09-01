<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use GuzzleHttp\Client;

class UserController extends Controller
{
/*    public function __construct()
    {
        $guzzle = new Client();
        $token = json_decode($guzzle->post('https://key.animation-web.de:8443/auth/realms/master/protocol/openid-connect/token/', [
        	'headers' => [
        		'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'client_id' => 'power4tec', ///$clientId,
                //'client_secret' => 'yQLx0xA~R.bQr2i2lpOZBcrv.BMK.4UKLu', // $clientSecret,
                //'resource' => 'https://graph.microsoft.com/',
                'grant_type' => 'password',
                'username' => 'sascha.koziellek',
                'password' => 'sascha242'
            ],
        ])->getBody()->getContents());
        dd($token);
        //$this->accessToken = $token->access_token;
    }
*/    
    public function index()
    {
    	return "true";
    }
}
