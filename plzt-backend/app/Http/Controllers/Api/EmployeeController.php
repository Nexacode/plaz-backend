<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\EmployeeResource;

use Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        $url = env('KEYCLOAK_URL') . 'admin/realms/' .env('KEYCLOAK_REALM') . '/groups/' .env('KEYCLOAK_EMPLOYEE_GROUP') . '/members';
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . Auth::decodededtoken()
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $output = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $error = json_decode($err);
        $response = json_decode($output);

        if ($err) {
            return response()->json($error, 201);
        }
        if ($response) {
            //return response()->json($response, 201);
            return EmployeeResource::collection($response);
        }
    }
}
