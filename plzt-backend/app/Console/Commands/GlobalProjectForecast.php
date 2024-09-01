<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;

use Mail;
use Carbon\Carbon;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Models\Project;
use App\Models\Todo;

class GlobalProjectForecast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:globalforecast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
    
    	$filename = 'global-forecast.xlsx';
    	
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
    
		//$sheet->setSelectedCell("A2");
		$spreadsheet->setActiveSheetIndex(0);

		//get binaries of excel file
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		
		//$todos = Todo::with('project')->where('status', 0)->where('project_id',$this->argument('project'))->get();
		//$rows = $todos->count();
		$projects = Project::where('status',1)->where('sub_project',0)->get();
		
		$z = 1;
		
		$sheet->setCellValue('A1', "Projektname");
		$sheet->setCellValue('B1', "offen");
		
		$sheet->setCellValue('D1', "Programmiert im Test");
		
		foreach($projects as $project){
			$sheet->setCellValue('A' . $z+1, $project->name);
			$sheet->setCellValue('B' . $z+1, Todo::where('project_id',$project->id)->sum('calculated_time'));
			$sheet->setCellValue('D' . $z+1, Todo::where('project_id',$project->id)->where('todo_status_id',7)->sum('calculated_time'));
			$z++;
		}

		ob_start();
		$writer->save('php://output');
		$content = ob_get_clean();
		
		$data = array('works'=>"",'active'=>"");

		//store excel file in storage…
		Storage::disk('local')->put('reports/'.$filename, $content);    
    
      	Mail::send(['text'=>"forecast"], $data, function($message) use ($filename) {
        	$message->to('sascha.koziellek@power4-its.de', 'Tutorials Point')->subject
            ('Globaler Projekt - Forecast');
         	$message->from('info@power4-its.com','power4-its.de');
         	$message->attach(storage_path("app/reports/" . $filename));
      	});
    }
}
