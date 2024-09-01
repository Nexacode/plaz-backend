<?php

namespace App\Console\Commands;

use App\Models\Todo;
use App\Models\Milestone;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

use Mail;
use Carbon\Carbon;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProjectForecastInternal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
	protected $signature = 'cron:internalforecast {project}';

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
    
    	$filename = 'forecast.xlsx';
 
		$styleMilestone = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => 'f4a478')
			],
			'font' => [
				'italic'  => false,
				'color' => array('rgb' => '8c3200'),
			]
		]; 
		$styleUnderMilestone = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => 'f0cdb9')
			],
			'font' => [
				'italic'  => false,
				'color' => array('rgb' => '8c3200'),
			]
		];
		$styleStatus1 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => 'ffffff')
			],
			'font' => [
				'italic'  => false,
				'color' => array('rgb' => '000000'),
			],
           'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'c5c5c5'),
                ]
            ],
		];		
		$styleStatus2 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => '88cffc')
			],
			'font' => [
				'italic'  => false,
				'color' => array('rgb' => '12486b'),
			],
          'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'c5c5c5'),
                ]
            ],
		];
		$styleStatus3 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => 'e6a9fc')
			],
			'font' => [
				'italic'  => false,
				'color' => array('rgb' => '510f69'),
			],
          'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'c5c5c5'),
                ]
            ],
		];
		$styleStatus4 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => 'f0cdb9')
			],
			'font' => [
				'italic'  => false,
				'color' => array('rgb' => '8c3200'),
			],
          'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'c5c5c5'),
                ]
            ],
		];
		$styleStatus5 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => 'e97171')
			],
			'font' => [
				'italic'  => false,
				'color' => array('rgb' => '8c3200'),
			],
          'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'c5c5c5'),
                ]
            ],
		];
		$styleStatus6 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => 'f0cdb9')
			],
			'font' => [
				'italic'  => false,
				'color' => array('rgb' => '8c3200'),
			],
          'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'c5c5c5'),
                ]
            ],
		];
		$styleStatus7 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => '51ffe8')
			],
			'font' => [
				'italic'  => false,
				'color' => array('rgb' => '014940'),
			],          
			'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'c5c5c5'),
                ]
            ],
		];
		$styleStatus8 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => 'daff89')
			],
			'font' => [
				'italic'  => false,
				'color' => array('rgb' => '3f5315'),
			],          
			'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'c5c5c5'),
                ]
            ],
		];
		$styleStatus9 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => 'c8d6a1')
			],
			'font' => [
				'italic'  => false,
				'color' => array('rgb' => '004903'),
			],          
			'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'c5c5c5'),
                ]
            ],
		];
		$styleStatus10 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => '71e977')
			],
			'font' => [
				'italic'  => false,
				'color' => array('rgb' => '004903'),
			],          
			'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'c5c5c5'),
                ]
            ],
		];
		$styleStatus11 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => 'B0FC38')
			],
			'font' => [
				'italic'  => false,
				'color' => array('rgb' => '004903'),
			],          
			'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'c5c5c5'),
                ]
            ],
		];	
		$styleTemporary = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => 'f3f1b4')
			],
			'font' => [
				'italic'  => true,
				'color' => array('rgb' => '747245'),
			],          
			'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'c5c5c5'),
                ]
            ],
		];	
		$styleCenter = [
    		'alignment' => [
        	'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        	'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        	'wrapText' => true,
    		]
		];
    	
		$styleArrayDuplicate = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => 'EAEAEA')
			],
			'font' => [
				'italic'  => true,
				'color' => array('rgb' => '818181'),
			],
          'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'c5c5c5'),
                ]
            ],
		];
    
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		
		$milestones = Milestone::where('project_id',$this->argument('project'))->with('todo','todo.statusname','todo.lastAssessment','inCategory')->orderBy('priority')->get();
		
		$z = 1;
		
		$sheet->setCellValue('I1', 'Legende');
		$sheet->getStyle($sheet->getCellByColumnAndRow(9, 2)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(9, 2)->getCoordinate())->applyFromArray($styleStatus1);
		$sheet->setCellValue('I2', 'Besprochen');
		$sheet->getStyle($sheet->getCellByColumnAndRow(9, 3)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(9, 3)->getCoordinate())->applyFromArray($styleStatus2);
		$sheet->setCellValue('I3', 'Eingeschaetzt');
		$sheet->getStyle($sheet->getCellByColumnAndRow(9, 4)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(9, 4)->getCoordinate())->applyFromArray($styleStatus3);
		$sheet->setCellValue('I4', 'Freigabe');
		$sheet->getStyle($sheet->getCellByColumnAndRow(9, 5)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(9, 5)->getCoordinate())->applyFromArray($styleStatus4);
		$sheet->setCellValue('I5', 'In Planung');
		$sheet->getStyle($sheet->getCellByColumnAndRow(9, 6)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(9, 6)->getCoordinate())->applyFromArray($styleStatus5);
		$sheet->setCellValue('I6', 'Verzoegert');
		$sheet->getStyle($sheet->getCellByColumnAndRow(9, 7)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(9, 7)->getCoordinate())->applyFromArray($styleStatus6);
		$sheet->setCellValue('I7', 'Im Plan');
		$sheet->getStyle($sheet->getCellByColumnAndRow(9, 8)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(9, 8)->getCoordinate())->applyFromArray($styleStatus7);
		$sheet->setCellValue('I8', 'Programmiert / im Test');
		$sheet->getStyle($sheet->getCellByColumnAndRow(9, 9)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(9, 9)->getCoordinate())->applyFromArray($styleStatus8);
		$sheet->setCellValue('I9', 'Abnahmebereit Testumgebung');
		$sheet->getStyle($sheet->getCellByColumnAndRow(9, 10)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(9, 10)->getCoordinate())->applyFromArray($styleStatus9);
		$sheet->setCellValue('I10', 'Abgenommen Testumgebung');
		$sheet->getStyle($sheet->getCellByColumnAndRow(9, 11)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(9, 11)->getCoordinate())->applyFromArray($styleStatus10);
		$sheet->setCellValue('I11', 'Abnahmebereit');
		$sheet->getStyle($sheet->getCellByColumnAndRow(9, 12)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(9, 12)->getCoordinate())->applyFromArray($styleStatus11);
		$sheet->setCellValue('I12', 'Live / online');
		$sheet->getStyle($sheet->getCellByColumnAndRow(9, 14)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(9, 14)->getCoordinate())->applyFromArray($styleTemporary);
		$sheet->setCellValue('I14', 'TemporŠr geplant/ nicht freigegeben. Datum kann sich nach Freigabe aendern');

		
		$time = 0;
		
		foreach($milestones as $milestone){
			
			$cellStart = $sheet->getCellByColumnAndRow(1, $z)->getCoordinate();
			$cellEnd = $sheet->getCellByColumnAndRow(6, $z)->getCoordinate();
			
			if(empty($milestone->inCategory->in_category_id)){
				$sheet->getStyle($cellStart .':'. $cellEnd)->applyFromArray($styleMilestone);
			} else {
				$sheet->getStyle($cellStart .':'. $cellEnd)->applyFromArray($styleUnderMilestone);
			}
			
			foreach ($sheet->getColumnIterator() as $column) {
						$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
			}
			
			$sheet->setCellValue('A' . $z, $milestone->milestone);
			//$sheet->setCellValue('B' . $z, $milestone->inCategory->in_category_id);
			//$sheet->setCellValue('C' . $z, $milestone->category_id);
			
			if(count($milestone->todo) >= 1){
				$z = $z+1;
				$cellStart = $sheet->getCellByColumnAndRow(1, $z)->getCoordinate();
				$cellEnd = $sheet->getCellByColumnAndRow(6, $z)->getCoordinate();
				$sheet->getStyle($cellStart .':'. $cellEnd)->applyFromArray($styleArrayDuplicate);
				$sheet->setCellValue('A' . $z, 'Todo');
				$sheet->setCellValue('B' . $z, 'Status');
				$sheet->setCellValue('C' . $z, "geschaetzt (Std.)");
				$sheet->setCellValue('D' . $z, "benoetigt (Std.)");
				$sheet->setCellValue('E' . $z, 'geplant am:');
				$sheet->setCellValue('F' . $z, 'Programmierer');
			}
			
			
			
			foreach($milestone->todo as $todo){
				$z++;
				
				$sheet->setCellValue('A' . $z, $todo->todo);
				//$sheet->setCellValue('M' . $z, $todo->todo_status_id);
				
				if($todo->todo_status_id == 1){
					$sheet->getStyle($sheet->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus1);
					$sheet->setCellValue('B' . $z, $todo->statusname->name);
				} 
				
				if ($todo->todo_status_id == 2){
					$sheet->getStyle($sheet->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus2);
					$sheet->setCellValue('B' . $z, $todo->statusname->name);
				} 
				
				if ($todo->todo_status_id == 3){
					$sheet->getStyle($sheet->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus3);
					$sheet->setCellValue('B' . $z, $todo->statusname->name);
				} 
				
				if ($todo->todo_status_id == 4){
					$sheet->getStyle($sheet->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus4);
					$sheet->setCellValue('B' . $z, $todo->statusname->name);
				} 
				
				if ($todo->todo_status_id == 5){
					$sheet->getStyle($sheet->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus5);
					$sheet->setCellValue('B' . $z, $todo->statusname->name);
				} 
				
				if ($todo->todo_status_id == 6){
					$sheet->getStyle($sheet->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus6);
					$sheet->setCellValue('B' . $z, $todo->statusname->name);
				} 
				
				if ($todo->todo_status_id == 7){
					$sheet->getStyle($sheet->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus7);
					$sheet->setCellValue('B' . $z, $todo->statusname->name);
				} 
				
				if ($todo->todo_status_id == 8){
					$sheet->getStyle($sheet->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus8);
					$sheet->setCellValue('B' . $z, $todo->statusname->name);
				} 
				
				if ($todo->todo_status_id == 9){
					$sheet->getStyle($sheet->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus9);
					$sheet->setCellValue('B' . $z, $todo->statusname->name);
				}
				
				if ($todo->todo_status_id == 10){
					$sheet->getStyle($sheet->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus10);
					$sheet->setCellValue('B' . $z, $todo->statusname->name);
				}
				
				$sheet->setCellValue('C' . $z, $todo->calculated_time);
				$sheet->getStyle($sheet->getCellByColumnAndRow(4, $z)->getCoordinate() .':'. $sheet->getCellByColumnAndRow(4, $z)->getCoordinate())->applyFromArray($styleCenter);
				$sheet->setCellValue('D' . $z, $todo->workSum()->sum('time'));
				$sheet->setCellValue('E' . $z, Carbon::parse($todo->deadline)->format('d.m.Y'));
				$sheet->setCellValue('F' . $z, $todo->user_name);
				$sheet->setCellValue('G' . $z, $todo->temporary);
				//$sheet->setCellValue('M' . $z, $todo->todo_status_id);
				
				$time = $time + $todo->calculated_time;
				$todo = "";
				
			}
			$z++;
			
		}
		$sheet->setCellValue('B' . $z+1, "Gesamt:");
		$sheet->setCellValue('C' . $z+1, $time);
				
		//$sheet->setSelectedCell("A2");
		$spreadsheet->setActiveSheetIndex(0);

		//get binaries of excel file
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		
		//$todos = Todo::with('project')->where('status', 0)->where('project_id',$this->argument('project'))->get();
		//$rows = $todos->count();

		ob_start();
		$writer->save('php://output');
		$content = ob_get_clean();

		//store excel file in storageÉ
		Storage::disk('local')->put('reports/'.$filename, $content);		
    
    	$works = Todo::where('status', 0)->count();
    	$active = Todo::where('status', 0)->count();
      	$data = array('works'=>$works,'active'=>$active);
   	
      	Mail::send(['text'=>"forecast"], $data, function($message) use ($filename) {
        	$message->to('sascha.koziellek@power4-its.de', 'Tutorials Point')->subject
            ('Projekt - Forecast');
         	$message->from('info@power4-its.com','power4-its.de');
         	$message->attach(storage_path("app/reports/" . $filename));
      	});
      
      	return true;
    }
}
