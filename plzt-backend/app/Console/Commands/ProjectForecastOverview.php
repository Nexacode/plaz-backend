<?php

namespace App\Console\Commands;

use App\Models\Todo;
use App\Models\Milestone;
use App\Models\Project;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

use Mail;
use Carbon\Carbon;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProjectForecast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
	protected $signature = 'cron:forecastoverview {project}';

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
    
    private function query($i)
    {
    	$subprojects = [];
    
    	if($i == 1) {
    	
    		$subprojects = Project::where('project_id',$this->argument('project'))->get()->toArray();
			$subprojects = array_column($subprojects, 'id');
			$subprojects[] = $this->argument('project');    
			$milestones = Milestone::whereIn('project_id',$subprojects)->with('todo','todo.statusname','todo.lastAssessment','inCategory')->orderBy('priority')->get();	
			return $milestones;
			
    	} else if($i == 2){
    	
    		$subprojects = Project::where('project_id',$this->argument('project'))->get()->toArray();
			$subprojects = array_column($subprojects, 'id');
			$subprojects[] = $this->argument('project');    
			$milestones = Milestone::whereIn('project_id',$subprojects)
    						->with(['todo' => function ($query) {
        						$query->whereIn('todo_status_id', [1, 2, 4]);
    						}, 'todo.statusname', 'todo.lastAssessment', 'inCategory'])
    						->whereHas('todo', function ($query) {
        						$query->whereIn('todo_status_id', [1, 2, 4]);
    						})
							->orderBy('priority')->get();	
			return $milestones;
			    	
    	} else if($i == 3){
    	
    		$subprojects = Project::where('project_id',$this->argument('project'))->get()->toArray();
			$subprojects = array_column($subprojects, 'id');
			$subprojects[] = $this->argument('project');    
			$milestones = Milestone::whereIn('project_id',$subprojects)
    						->with(['todo' => function ($query) {
        						$query->where('todo_status_id', 3);
    						}, 'todo.statusname', 'todo.lastAssessment', 'inCategory'])
    						->whereHas('todo', function ($query) {
        						$query->where('todo_status_id', 3);
    						})
							->orderBy('priority')->get();	
			return $milestones;
			  	
    	}  else if($i == 4){
    	
    		$subprojects = Project::where('project_id',$this->argument('project'))->get()->toArray();
			$subprojects = array_column($subprojects, 'id');
			$subprojects[] = $this->argument('project');    
			$milestones = Milestone::whereIn('project_id',$subprojects)
    						->with(['todo' => function ($query) {
        						$query->where('todo_status_id', 12);
    						}, 'todo.statusname', 'todo.lastAssessment', 'inCategory'])
    						->whereHas('todo', function ($query) {
        						$query->where('todo_status_id', 12);
    						})
							->orderBy('priority')->get();	
			return $milestones;
			  	
    	} else if($i == 5){
    	
    		$subprojects = Project::where('project_id',$this->argument('project'))->get()->toArray();
			$subprojects = array_column($subprojects, 'id');
			$subprojects[] = $this->argument('project');    
			$milestones = Milestone::whereIn('project_id',$subprojects)
    						->with(['todo' => function ($query) {
        						$query->where('todo_status_id', 7);
    						}, 'todo.statusname', 'todo.lastAssessment', 'inCategory'])
    						->whereHas('todo', function ($query) {
        						$query->where('todo_status_id', 7);
    						})
							->orderBy('priority')->get();	
			return $milestones;
			  	
    	} else {
    		return [];
    	}
    	
    	
    	
    }
    
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
		$styleStatus12 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color'		=> array('rgb' => '00d4b7')
			],
			'font' => [
				'italic'  => false,
				'color' => array('rgb' => '1b635a'),
			],          
			'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '053d36'),
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
		
		//$sheet = $spreadsheet->getActiveSheet();
		//$sheet->setTitle('Gesamtes Projekt');
		
		for ($i = 1; $i <= 5; $i++) {
		
			$z = 1;
				
			//$nextsheet[$i] = $spreadsheet->createSheet();
			if($i == 1){
				$nextsheet[$i] = $spreadsheet->getActiveSheet();
				$nextsheet[$i]->setTitle('Gesamtes Projekt');
			}
			
			if($i ==2){
				$nextsheet[$i] = $spreadsheet->createSheet();
				$nextsheet[$i]->setTitle('offene Todo');
			}
			
			if($i ==3){
				$nextsheet[$i] = $spreadsheet->createSheet();
				$nextsheet[$i]->setTitle('Freigabe erforderlich');
			}

			if($i ==4){
				$nextsheet[$i] = $spreadsheet->createSheet();
				$nextsheet[$i]->setTitle('Vorgeplant - zu Besprechen');
			}
			
			if($i ==5){
				$nextsheet[$i] = $spreadsheet->createSheet();
				$nextsheet[$i]->setTitle('Programmiert im Test');
			}
			
			$nextsheet[$i]->setCellValue('I1', 'Legende');
			$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(9, 2)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(9, 2)->getCoordinate())->applyFromArray($styleStatus1);
			$nextsheet[$i]->setCellValue('I2', 'Besprochen');
			$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(9, 3)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(9, 3)->getCoordinate())->applyFromArray($styleStatus2);
			$nextsheet[$i]->setCellValue('I3', 'Eingeschaetzt');
			$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(9, 4)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(9, 4)->getCoordinate())->applyFromArray($styleStatus3);
			$nextsheet[$i]->setCellValue('I4', 'Freigabe');
			$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(9, 5)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(9, 5)->getCoordinate())->applyFromArray($styleStatus4);
			$nextsheet[$i]->setCellValue('I5', 'In Planung');
			$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(9, 6)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(9, 6)->getCoordinate())->applyFromArray($styleStatus5);
			$nextsheet[$i]->setCellValue('I6', 'Verzoegert');
			$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(9, 7)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(9, 7)->getCoordinate())->applyFromArray($styleStatus6);
			$nextsheet[$i]->setCellValue('I7', 'Im Plan');
			$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(9, 8)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(9, 8)->getCoordinate())->applyFromArray($styleStatus7);
			$nextsheet[$i]->setCellValue('I8', 'Programmiert / im Test');
			$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(9, 9)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(9, 9)->getCoordinate())->applyFromArray($styleStatus8);
			$nextsheet[$i]->setCellValue('I9', 'Abnahmebereit Testumgebung');
			$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(9, 10)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(9, 10)->getCoordinate())->applyFromArray($styleStatus9);
			$nextsheet[$i]->setCellValue('I10', 'Abgenommen Testumgebung');
			$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(9, 11)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(9, 11)->getCoordinate())->applyFromArray($styleStatus10);
			$nextsheet[$i]->setCellValue('I11', 'Abnahmebereit');
			$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(9, 12)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(9, 12)->getCoordinate())->applyFromArray($styleStatus11);
			$nextsheet[$i]->setCellValue('I12', 'Live / online');
			$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(9, 13)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(9, 13)->getCoordinate())->applyFromArray($styleStatus12);
			$nextsheet[$i]->setCellValue('I13', 'Vorgeplant / zu besprechen');
			$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(9, 15)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(9, 14)->getCoordinate())->applyFromArray($styleTemporary);
			$nextsheet[$i]->setCellValue('I15', 'Temporaer geplant/ nicht freigegeben. Datum kann sich nach Freigabe aendern');
			
			$milestones = $this->query($i);
			$time = 0;
			
			foreach($milestones as $milestone){
			
				$cellStart = $nextsheet[$i]->getCellByColumnAndRow(1, $z)->getCoordinate();
				$cellEnd = $nextsheet[$i]->getCellByColumnAndRow(6, $z)->getCoordinate();
			
				if(empty($milestone->inCategory->in_category_id)){
					$nextsheet[$i]->getStyle($cellStart .':'. $cellEnd)->applyFromArray($styleMilestone);
				} else {
					$nextsheet[$i]->getStyle($cellStart .':'. $cellEnd)->applyFromArray($styleUnderMilestone);
				}
			
				foreach ($nextsheet[$i]->getColumnIterator() as $column) {
					$nextsheet[$i]->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
				}
			
				$nextsheet[$i]->setCellValue('A' . $z, $milestone->milestone);
				
				if(count($milestone->todo) >= 1){
					$z = $z+1;
					$cellStart = $nextsheet[$i]->getCellByColumnAndRow(1, $z)->getCoordinate();
					$cellEnd = $nextsheet[$i]->getCellByColumnAndRow(6, $z)->getCoordinate();
					$nextsheet[$i]->getStyle($cellStart .':'. $cellEnd)->applyFromArray($styleArrayDuplicate);
					$nextsheet[$i]->setCellValue('A' . $z, 'Todo');
					$nextsheet[$i]->setCellValue('B' . $z, 'Status');
					$nextsheet[$i]->setCellValue('C' . $z, "geschaetzt (Std.)");
					$nextsheet[$i]->setCellValue('D' . $z, 'geplant am:');
					$nextsheet[$i]->setCellValue('E' . $z, 'Programmierer');
					$nextsheet[$i]->setCellValue('F' . $z, 'Info');
				}	
				
				foreach($milestone->todo as $todo){
					$z++;
				
					if($todo->temporary){
						$nextsheet[$i]->setCellValue('A' . $z, $todo->todo);
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(1, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(1, $z)->getCoordinate())->applyFromArray($styleTemporary);
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(3, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(3, $z)->getCoordinate())->applyFromArray($styleTemporary);
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(4, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(4, $z)->getCoordinate())->applyFromArray($styleTemporary);
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(5, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(5, $z)->getCoordinate())->applyFromArray($styleTemporary);
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(6, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(6, $z)->getCoordinate())->applyFromArray($styleTemporary);	
					} else {
						$nextsheet[$i]->setCellValue('A' . $z, $todo->todo);
					}
				
					if($todo->todo_status_id == 1){
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus1);
						$nextsheet[$i]->setCellValue('B' . $z, $todo->statusname->name);
					} 
				
					if ($todo->todo_status_id == 2){
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus2);
						$nextsheet[$i]->setCellValue('B' . $z, $todo->statusname->name);
					} 
				
					if ($todo->todo_status_id == 3){
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus3);
						$nextsheet[$i]->setCellValue('B' . $z, $todo->statusname->name);
					} 
				
					if ($todo->todo_status_id == 4){
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus4);
						$nextsheet[$i]->setCellValue('B' . $z, $todo->statusname->name);
					} 
				
					if ($todo->todo_status_id == 5){
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus5);
						$nextsheet[$i]->setCellValue('B' . $z, $todo->statusname->name);
					} 
				
					if ($todo->todo_status_id == 6){
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus6);
						$nextsheet[$i]->setCellValue('B' . $z, $todo->statusname->name);
					} 
				
					if ($todo->todo_status_id == 7){
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus7);
						$nextsheet[$i]->setCellValue('B' . $z, $todo->statusname->name);
					} 
				
					if ($todo->todo_status_id == 8){
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus8);
						$nextsheet[$i]->setCellValue('B' . $z, $todo->statusname->name);
					} 
				
					if ($todo->todo_status_id == 9){
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus9);
						$nextsheet[$i]->setCellValue('B' . $z, $todo->statusname->name);
					}
				
					if ($todo->todo_status_id == 10){
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus10);
						$nextsheet[$i]->setCellValue('B' . $z, $todo->statusname->name);
					}
				
					if ($todo->todo_status_id == 11){
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus11);
						$nextsheet[$i]->setCellValue('B' . $z, $todo->statusname->name);
					}	
					if ($todo->todo_status_id == 12){
						$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate() .':'. $nextsheet[$i]->getCellByColumnAndRow(2, $z)->getCoordinate())->applyFromArray($styleStatus12);
						$nextsheet[$i]->setCellValue('B' . $z, $todo->statusname->name);
					}			
				
					$nextsheet[$i]->setCellValue('C' . $z, $todo->calculated_time);
					$nextsheet[$i]->getStyle($nextsheet[$i]->getCellByColumnAndRow(4, $z)->getCoordinate() .':'.$nextsheet[$i]->getCellByColumnAndRow(4, $z)->getCoordinate())->applyFromArray($styleCenter);
					if($todo->todo_status_id >= 2 && !empty($todo->deadline)){
						$nextsheet[$i]->setCellValue('D' . $z, Carbon::parse($todo->deadline)->format('d.m.Y'));
					} else {
						$nextsheet[$i]->setCellValue('D' . $z, "");
					}
				
					$nextsheet[$i]->setCellValue('E' . $z, $todo->user_name);
					$nextsheet[$i]->setCellValue('F' . $z, $todo->external_information);
					if($todo->temporary){
						$nextsheet[$i]->setCellValue('G' . $z, "Temporaer geplant / nicht freigegeben");
					}
				
				//$sheet->setCellValue('M' . $z, $todo->todo_status_id);
				
					$time = $time + $todo->calculated_time;
					$todo = "";
				
				}
				$z++;		
			
			}
			
			$nextsheet[$i]->setCellValue('B' . $z+1, "Gesamt:");
			$nextsheet[$i]->setCellValue('C' . $z+1, $time);
		}
		
		$subprojects = Project::where('project_id',$this->argument('project'))->get()->toArray();
		$subprojects = array_column($subprojects, 'id');
		$subprojects[] = $this->argument('project');
		
		$this->info(print_r($subprojects));

		$milestones = Milestone::whereIn('project_id',$subprojects)->with('todo','todo.statusname','todo.lastAssessment','inCategory')->orderBy('priority')->get();
		
		$z = 1;
				
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
