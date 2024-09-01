<?php
namespace App\Console\Commands;

use App\Models\Work;

use Illuminate\Console\Command;

use Mail;
use Carbon\Carbon;

class WorkReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:reminder';

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
    	
    	$dailybusiness = [];
    	
    	$dailybusiness['yesterday'] = Work::where('date', Carbon::yesterday())->where('project_id',76)->sum('time');
    	$dailybusiness['month'] = Work::whereMonth('date', Carbon::now()->format('m'))->where('project_id',76)->sum('time');
    	$dailybusiness['allday'] = Work::whereMonth('date', Carbon::now()->format('m'))->where('project_id',76)->selectRaw('sum(time) as sum,date')->groupBy('date')->get();
    	$dailybusiness['client'] = Work::whereMonth('date', Carbon::now()->format('m'))->where('project_id',76)->selectRaw('sum(time) as sum,client')->orderBy('sum')->groupBy('client')->get();
    	
   		$dailybusinessuser = [];
   		
   		$i = 0;
   		foreach($dailybusiness['allday'] as $day){
   		
   			//$dailybusinessuser[] = [ $day->date => [] ];
   			
   			$times = Work::where('date','=', $day->date)->where('project_id',76)->selectRaw('sum(time) as sum,user_name')->groupBy('user_name')->get();
   			$z = 0;
   			foreach($times as $time){
   				//$dailybusinessuser[$i] = $time->user_name;
   				$dailybusinessuser["$day->date"][$z] = ["user" => $time->user_name, "time" => $time->sum];
   				$z++;
   			}
   			$i++;
   		}
   		
   	
    	$works = Work::where('date', Carbon::yesterday())->get();
    	
    	$aktiv = "";
      	$data = array('works'=>$works,'aktiv'=>$aktiv,'dailybusiness'=>$dailybusiness,'dailybusinessuser'=>$dailybusinessuser,'times'=>$times);
   	
      	Mail::send(['text'=>"email"], $data, function($message) {
         	$message->to('sascha.koziellek@power4-its.de', 'Tutorials Point')->subject
            	('Tägliche - Statistiken');
         	$message->from('info@power4-its.com','PLZT TOOL');
      	});
      
      return true;
    }
}
