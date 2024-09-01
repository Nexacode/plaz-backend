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

use App\Http\Resources\UserTodoResource;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\SetSendEmail;
use App\Models\Ticket;
use App\Models\Todo;
use Carbon\Carbon;

class UserResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    
    private $dateRange=[
         'currentWeek', 'currentMonth', 'currentYear',  'lastYear'
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function sendEmail($filename){
        $data = [];
        $sendEmails=SetSendEmail::where('prefix', 'user_results')->get('email')->toarray();
        $emailRecipients = [];
        foreach($sendEmails as $sendEmail){
            $emailRecipients[] = $sendEmail['email'];
        }
        $htmlContent = 'Ergebnisse der Mitarbeiter';
        var_dump($emailRecipients);
        Mail::send([], ['htmlContent' => $htmlContent], function($message) use ($filename, $emailRecipients, $htmlContent) {
            $message->to($emailRecipients)->subject('User - Todos');
            $message->from('kundenbetreuung@power4tech.de', 'power4-its.de');
            $message->attach(storage_path("app/usertodos/" . $filename));

            // Set email body as HTML
            $message->setBody($htmlContent, 'text/html');
        });
    }
    
    public function getResults()
    {
        $alphabet = range('A', 'Z');
        $filename = 'userresults.xlsx';
        $spreadsheet = new Spreadsheet();
        $tableStyle = new TableStyle();
        $tableStyle->setTheme(TableStyle::TABLE_STYLE_LIGHT21);
        $tableStyle->setShowRowStripes(true);
        $i=0;
        foreach ($this->dateRange as $dateRange){

            switch ($dateRange){
                case 'currentWeek':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    $tableName = 'Woche';
                    $tableTitle = 'Woche';
                    break;
                case 'currentMonth':
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    $tableName = 'Monat';
                    $tableTitle = 'Monat';
                    break;
                case 'currentYear':
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                    $tableName = 'Jahr';
                    $tableTitle = 'Jahr';
                    break;
                case 'lastYear':
                    $startDate = Carbon::now()->startOfYear()->subYear();
                    $endDate = Carbon::now()->endOfYear()->subYear();
                    $tableName = 'Vorjahr';
                    $tableTitle = 'Vorjahr';
                    break;
            }


            if($i==0) {
                $worksheet = $spreadsheet->getActiveSheet();
            } else {
                $worksheet = $spreadsheet->createSheet();
            }

            $i++;
            $worksheet->setTitle($tableTitle);

            $todos = Todo::where('todo_status_id', 11)
                ->select(
                    'todos.id',
                    'todos.user_name',
                    'todos.calculated_time as estimated_time',
                )
                ->leftJoin('works', 'todos.id', '=', 'works.todo_id')
                ->withSum('work', 'time')
                ->withSum('assessment', 'time')
                ->whereBetween('works.created_at', [$startDate,  $endDate])
                ->groupBy('todos.id', 'todos.user_name', 'todos.calculated_time');
            $todosSubQuery = $todos->toSql();
            $sql = "SELECT user_name, SUM(estimated_time) AS total_estimated_time,
            SUM(work_sum_time) AS total_work_sum_time,
            SUM(assessment_sum_time) AS total_assessment_time
            FROM ({$todosSubQuery}) AS t
            GROUP BY user_name";

            $userTodos = DB::select($sql, $todos->getBindings());

          if (count($userTodos) > 0) {
              $columnsCount = 5;  // hard coded, how many columns to display

              $rowCount = count($userTodos) + 1;
              $range = 'A1:'.$alphabet[$columnsCount - 1].$rowCount;

              $table = new Table($range, $tableName);

              $table->setStyle($tableStyle);
              $worksheet->addTable($table);

              $worksheet->setCellValue('A1', 'Mitarbeiter');
              $worksheet->setCellValue('B1', 'Ergebnis');
              $worksheet->setCellValue('C1', 'Ursprüngliche Schätzung');
              $worksheet->setCellValue('D1', "Schätzung Mitarbeiter");
              $worksheet->setCellValue('E1', 'Tatsächliche Zeit');

              $z = 2;

              foreach ($userTodos as $todo) {
                  $worksheet->setCellValue('A'.$z, $todo->user_name);
                  $worksheet->setCellValue('B'.$z, 'Live / online');
                  $worksheet->setCellValue('C'.$z, $todo->total_estimated_time);
                  $worksheet->setCellValue('D'.$z, $todo->total_assessment_time);
                  $worksheet->setCellValue('E'.$z, $todo->total_work_sum_time);
                  $z++;
              }
              foreach (range('A', $worksheet->getHighestColumn()) as $col) {
                  $worksheet->getColumnDimension($col)->setAutoSize(true);
              }
          }

        }
        
        $worksheet = $spreadsheet->createSheet();
        $worksheet->setTitle("Ergebnissliste");
        
        $worksheet->getStyle('A1:D1')->getFont()->setBold(true);
        //$worksheet->setAutoFilter('A1:D1');
        

        
        $worksheet->setCellValue('A1', 'Datum');
        $worksheet->setCellValue('B1', 'Mitarbeiter');
        $worksheet->setCellValue('C1', 'Todo');
        $worksheet->setCellValue('D1', 'Projekt');
        
        $currentMonth = Carbon::now()->startOfMonth();
        
        $todos = Todo::where('todo_status_id', 11)
        			->with('work')
        			->with('project')
        			->withSum('work', 'time')
        			->withSum('assessment', 'time')
        			->whereHas('work', function ($query) use ($currentMonth) {
        				$query->whereBetween('created_at', [$currentMonth, Carbon::now()]);
    				})
        			->get();
        
        $z = 2;
        
        foreach($todos as $todo){
        	
        	if ($todo->work->isNotEmpty()) {
        		$worksheet->setCellValue('A'.$z, $todo->work->first()->created_at);
    		}
    		
        	$worksheet->setCellValue('B'.$z, $todo->user_name);
        	$worksheet->setCellValue('C'.$z, $todo->todo);
        	$worksheet->setCellValue('D'.$z, $todo->project->first()->name);
        	$z++;
        }
        
        $table = new Table('A1:D'.$z, 'Overview');
        $tableStyle = new TableStyle();
        $tableStyle->setTheme(TableStyle::TABLE_STYLE_MEDIUM7);
        $tableStyle->setShowRowStripes(true);
        $table->setStyle($tableStyle);
        $worksheet->addTable($table);
        
		//auto size columns
		foreach ($worksheet->getColumnIterator() as $column) {
			$worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}

        $spreadsheet->setActiveSheetIndex(0);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();
        Storage::disk('local')->put('usertodos/'.$filename, $content);


        $this->sendEmail($filename);
    }


    public function handle()
    {
       $d = $this->getResults();
    }
}
