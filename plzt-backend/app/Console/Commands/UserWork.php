<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;

use App\Models\User;
use App\Models\Work;
use App\Models\SetSendEmail;

use Carbon\Carbon;

class UserWork extends Command
{

    protected $signature = 'cron:userwork';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

	

    public function handle()
    {

		$i = 0;

        $alphabet = range('A', 'Z');
        $filename = 'userresults.xlsx';
        $spreadsheet = new Spreadsheet();
        
        $tableStyle = new TableStyle();
        $tableStyle->setTheme(TableStyle::TABLE_STYLE_LIGHT21);
        $tableStyle->setShowRowStripes(true);

        if($i==0) {
            $worksheet = $spreadsheet->getActiveSheet();
        } else {
            $worksheet = $spreadsheet->createSheet();
        }
            
        $worksheet->setCellValue('A1', 'Datum');
        $worksheet->setCellValue('B1', 'Mitarbeiter');
        $worksheet->setCellValue('C1', 'Zeit');
        //$worksheet->setCellValue('D1', 'Projekt');
        
        //$days = getDaysOfCurrentMonthUntilToday();
        
        $currentYear = Carbon::now()->year;
        $works = Work::whereYear('created_at', $currentYear)->get();
        
        $z = 2;

        foreach ($works as $work) {
        	$worksheet->setCellValue('A'.$z, $work->created_at);
            $worksheet->setCellValue('B'.$z, $work->user_name);
            $worksheet->setCellValue('C'.$z, $work->time);
            $z++;
        }
        
        $users = User::all();
        foreach($users as $user){
        
        	$columns_count = 3;
              
        	$worksheet = $spreadsheet->createSheet();
        	
        	$worksheet->setTitle("$user->name");
        	
        	$worksheet->setCellValue('A1', 'Datum');
        	$worksheet->setCellValue('B1', 'Mitarbeiter');
        	$worksheet->setCellValue('C1', 'Zeit');
        	
        	$works = Work::whereYear('created_at', $currentYear)->get();
        	$works = Work::selectRaw('DATE(date) as date, COUNT(*) as total, SUM(time) as total_time')
    			->whereYear('created_at', $currentYear)
    			->where('user_id','=',$user->keycloak_id)
    			->groupBy('date')
    			->get();

        $z = 2;
        
        	if(count($works)>0){
        	$row_count = count($works) + 1;
        	$range = 'A1:'.$alphabet[$columns_count - 1].$row_count;
        	$tablename = "Mitarbeiter" . "$user->id";
        	$table = new Table($range, $tablename);
        	$table->setStyle($tableStyle);
        	

        foreach ($works as $work) {
        	

        	
        	$worksheet->setCellValue('A'.$z, $work->date);
            //$worksheet->setCellValue('B'.$z, $work->user_name);
            $worksheet->setCellValue('C'.$z, $work->total_time);
            $z++;
        }        	
        	$z = $z+1;
        	$worksheet->addTable($table);
        }
        
        }
        
        
        

        $spreadsheet->setActiveSheetIndex(0);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();
        Storage::disk('local')->put('userwork/'.$filename, $content);
        
        $this->sendEmail($filename);

    }
    
    private function getDaysOfCurrentMonthUntilToday() {
    	$today = Carbon::today();
    	$startOfMonth = $today->copy()->startOfMonth();
    	$daysArray = [];

    	for ($date = $startOfMonth; $date->lte($today); $date->addDay()) {
        	$daysArray[] = $date->copy()->toDateString();
    	}

    	return $daysArray;
	}
	
    private function sendEmail($filename){
        $data = [];
        $sendEmails=SetSendEmail::where('prefix', 'user_works')->get('email')->toarray();
        $emailRecipients = [];
        foreach($sendEmails as $sendEmail){
            $emailRecipients[] = $sendEmail['email'];
        }
        $htmlContent = 'Eingetragene Arbeiten';
        var_dump($emailRecipients);
        Mail::send([], ['htmlContent' => $htmlContent], function($message) use ($filename, $emailRecipients, $htmlContent) {
            $message->to($emailRecipients)->subject('User - Works');
            $message->from('info@power4-its.de', 'power4-its.de');
            $message->attach(storage_path("app/userworks/" . $filename));

            // Set email body as HTML
            $message->setBody($htmlContent, 'text/html');
        });
        
        
    }
}
