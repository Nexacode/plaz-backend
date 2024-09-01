<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SetSendEmail;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jumbojett\OpenIDConnectClient;
use GuzzleHttp\Client;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

use App\Models\Milestone;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\Work;
use Mail;
use Auth;
use App\Http\Resources\TicketResource;

class TicketController extends Controller
{
    public function __construct()
    {
    	/*
        $guzzle = new Client();
        $url = 'https://login.microsoftonline.com/common/oauth2/token?api-version=1.0';
        $token = json_decode($guzzle->post($url, [
            'form_params' => [
                'client_id' => 'e859dc45-9bc9-41cd-8351-eb9bde5305a2', ///$clientId,
                'client_secret' => 'yQLx0xA~R.bQr2i2lpOZBcrv.BMK.4UKLu', // $clientSecret,
                'resource' => 'https://graph.microsoft.com/',
                'grant_type' => 'password',
                'username' => 'Sascha.Koziellek@power4-tec.de',
                'password' => 'P0wer4T3ch!#'
            ],
        ])->getBody()->getContents());
        $this->accessToken = $token->access_token;
        */
    }

    public function index()
    {
        $tickets = Ticket::where('status','!=',2)->with('workSum')->paginate(50);
        return TicketResource::collection($tickets);
    }

    private function handleFileUpload(Request $request) {
        $filenames = $request->input('file_names');
        $base64_files = $request->input('base64_files');
        if($filenames!==null) {
            for ($i = 0; $i < count($filenames); $i++) {
                $filename = $filenames[$i];
                if ($filename != "") {
                    $base64_file = $base64_files[$i];
                    try {
                        $base64Data = explode(',', $base64_file);
                        $base64String = end($base64Data);
                    } catch (\Exception $e) {
                        throw new \Exception('Invalid base64 format');
                    }
                    $data = base64_decode($base64String);
                    $filePath = "uploads/$filename";
                    Storage::disk('local')->put($filePath, $data);
                }
            }
        }

        return $filenames;
    }
    public function store(Request $request)
    {
        try{
            $filenames=$this->handleFileUpload($request);
        } catch (\Exception $e){
            return response()->json(['error' => 'Es gibt ein Problem beim Hochladen der Datei. Bitte überprüfen Sie die Datei und versuchen Sie es erneut!'], 500);
        }

        $ticket = Ticket::create($request->all());
        $ticket->history()->create(["ticket_status_id" => 1]);

        $User = Auth::user();
        if($User){
            $User = json_decode($User, true);
            $UserId = $User['token']['sub'];
            $UserName = $User['token']['name'];
        } else {
            $UserName = '';
        }

        $data = [
            'body' => $ticket->text,
            'user' => $UserName,
        ];
        $sendEmails=SetSendEmail::where('prefix', 'ticket')->get('email')->toarray();
        $emailRecipients = [];
        foreach($sendEmails as $sendEmail){
            $emailRecipients[] = $sendEmail['email'];
        }
        $ticketId= $ticket->id;
        $emailSubject= $ticket->subject;

        Mail::send('ticket', $data, function($message) use ($filenames, $emailRecipients, $ticketId, $emailSubject, $request) {
            $message->to($emailRecipients)->subject('#'.$ticketId.'#: '.$emailSubject);
            $message->from($request->from_email, $request->from_name);
            if($filenames!==null){
                foreach($filenames as $filename){
                    if($filename!="") {
                        $message->attach(storage_path("app/uploads/" . $filename));
                    }
                }
            }
        });

        return response()->json($ticket, 201);
    }

    public function ticketStatus()
    {
    	return response()->json(TicketStatus::all(),201);
    }

    public function changeStatus(Request $request)
    {
    	$ticket = Ticket::find($request->ticket_id);
    	$ticket->update(['status' => $request->status]);
    	$ticket->history()->create(["ticket_status_id" => $request->status]);
    	return response()->json(true,201);
    }

    public function workList($ticket)
    {
        $works = Work::where('ticket_id',$ticket)->get();
        return response()->json($works,201);
    }

    public function ticket($ticket)
    {
        $ticket = Ticket::find($ticket);
        return response()->json($ticket,201);
    }
}
