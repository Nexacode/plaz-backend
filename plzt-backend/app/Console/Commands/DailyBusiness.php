<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Work;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

use App\Models\Ticket;
use App\Models\SetSendEmail;

use Mail;
use Carbon\Carbon;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\Simple;
use Auth;
class DailyBusiness extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:daily';

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


    private $dateRange=[
        'last14Days', 'currentWeek', 'currentMonth', 'lastWeek', 'lastMonth'
    ];

    private function makeTicketList(&$spreadsheet, &$i, $tableStyle){
        $alphabet = range('A', 'Z');
        foreach ($this->dateRange as $dateRange){

            switch ($dateRange){
                case 'last14Days':
                    $startDate = Carbon::now()->subDays(14);
                    $endDate = Carbon::now();
                    $tableName = 'Ticketsletzten14Tage';
                    $tableTitle = 'Tickets der letzten 14 Tage';
                    break;
                case 'currentWeek':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    $tableName = 'TicketsaktuelleWoche';
                    $tableTitle = 'Tickets der aktuellen Woche';
                    break;
                case 'currentMonth':
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    $tableName = 'TicketsaktuellerMonat';
                    $tableTitle = 'Tickets des aktuellen Monats';
                    break;
                case 'lastWeek':
                    $startDate = Carbon::now()->startOfWeek()->subWeek();
                    $endDate = Carbon::now()->endOfWeek()->subWeek();
                    $tableName = 'TicketsletzteWoche';
                    $tableTitle = 'Tickets der letzten Woche';
                    break;
                case 'lastMonth':
                    $startDate = Carbon::now()->startOfMonth()->subMonth();
                    $endDate = Carbon::now()->endOfMonth()->subMonth();
                    $tableName = 'TicketsletzterMonat';
                    $tableTitle = 'Tickets des letzten Monats';
                    break;
            }

            if($i==0) {
                $worksheet = $spreadsheet->getActiveSheet();
            } else {
                $worksheet = $spreadsheet->createSheet();
            }

            $i++;
            $worksheet->setTitle($tableTitle);
            $tickets = Ticket::whereBetween('created_at', [$startDate, $endDate])->withSum('works','time')->with('customer')->with('project')->get();

            $columnsCount =11;  // hard coded, how many columns to display

            $rowCount= count($tickets) + 1;
            $range = 'A1:' . $alphabet[$columnsCount-1] . $rowCount;

            $table = new Table($range, $tableName);

            $table->setStyle($tableStyle);
            $worksheet->addTable($table);

            $worksheet->setCellValue('A1', 'Datum');
            $worksheet->setCellValue('B1', 'Kunde');
            $worksheet->setCellValue('C1', 'Von');
            $worksheet->setCellValue('D1', "Zeit (Std.)");
            $worksheet->setCellValue('E1', 'Betreff');
            $worksheet->setCellValue('F1', 'Status');
            $worksheet->setCellValue('G1', 'Info');
            $worksheet->setCellValue('H1', 'Lösungsbeschreibung');
            $worksheet->setCellValue('I1', 'Prio_Level');
            $worksheet->setCellValue('J1', 'Ticketerfasser');
            $worksheet->setCellValue('K1', 'Projekt');

            $z = 2;

            foreach($tickets as $ticket){
                $worksheet->setCellValue('A' . $z, $ticket->created_at->format('d.m.Y H:i'));
                if($ticket->customer==null){
                    $worksheet->setCellValue('B' . $z, "");
                } else {
                    $worksheet->setCellValue('B' . $z, $ticket->customer->company_name);
                }

                $worksheet->setCellValue('C' . $z, $ticket->from_name);
                $worksheet->setCellValue('D' . $z, $ticket->works_sum_time);
                $worksheet->setCellValue('E' . $z, $ticket->subject);
                if($ticket->status == 1)
                    $worksheet->setCellValue('F' . $z, "offen");
                if($ticket->status == 2)
                    $worksheet->setCellValue('F' . $z, "closed");
                if($ticket->status == 3)
                    $worksheet->setCellValue('F' . $z, "on Hold");
                if($ticket->status == 4)
                    $worksheet->setCellValue('F' . $z, "Projekt");

                $worksheet->setCellValue('H' . $z, $ticket->solution);
                if($ticket->priority == 1)
                    $worksheet->setCellValue('I' . $z, "kritisch");
                if($ticket->priority == 2)
                    $worksheet->setCellValue('I' . $z, "hoch");
                if($ticket->priority == 3)
                    $worksheet->setCellValue('I' . $z, "normal");
                if($ticket->priority == 4)
                    $worksheet->setCellValue('I' . $z, "niedrig");

                if($ticket->user==null) {
                    $worksheet->setCellValue('J' . $z, "");
                }
                else {
                    $worksheet->setCellValue('J' . $z, $ticket->user->name);
                }
                $worksheet->setCellValue('K' . $z, $ticket->external_project);
                $z++;
            }
            foreach (range('A', $worksheet->getHighestColumn()) as $col) {
                $worksheet->getColumnDimension($col)->setAutoSize(true);
            }


        }

        return $tickets;
    }

    private function makeFieldSummary(&$worksheet, $tickets, $z, $tableStyle, $field, $tableName, $firstCellName){

        $columnsCount =$tickets->unique($field)->where($field,  !null) ->count();
        if($columnsCount>0) {
            $alphabet = range('A', 'Z');
            $range = "A".$z.":".$alphabet[$columnsCount].($z+6);

            $table = new Table($range, $tableName);
            $table->setStyle($tableStyle);
            $worksheet->addTable($table);
            $worksheet->setCellValue('A'. ($z), $firstCellName);
            $worksheet->setCellValue('A'. ($z+1), 'Anzahl Tickets');
            $worksheet->setCellValue('A'. ($z+2), 'Anzahl Zeit');
            $worksheet->setCellValue('A'. ($z+3), 'Anzahl Prio-Level KRITISCH');
            $worksheet->setCellValue('A'. ($z+4), 'Anzahl Prio-Level HIGH');
            $worksheet->setCellValue('A'. ($z+5), 'Anzahl Prio-Level NORMAL');
            $worksheet->setCellValue('A'. ($z+6), 'Anzahl Prio-Level LOW');

            $alphabet = range('B', 'Z');
            $startColumn = 0;

            foreach ($tickets->unique($field)->where($field,  !null) as $item) {
                $Cell = $alphabet[$startColumn].$z;
                switch ($field){
                    case 'customer_id':
                        $subItem=$item->customer;
                        $subItemName=$subItem->company_name;
                        break;
                    case 'project_id':
                        $subItem=$item->project;
                        $subItemName=$subItem->name;
                        break;
                    case 'user_id':
                        $subItem=$item->user;
                        $subItemName=$subItem->name;
                        break;
                }
                $worksheet->setCellValue($Cell, $subItemName);
                $worksheet->setCellValue(
                    $alphabet[$startColumn].($z+1), $tickets->where('customer_id', $subItem->id)->count()
                );
                $worksheet->setCellValue(
                    $alphabet[$startColumn].($z+2), $tickets->where('customer_id', $subItem->id)->sum('works_sum_time')
                );
                $worksheet->setCellValue(
                    $alphabet[$startColumn].($z+3), $tickets->where('customer_id', $subItem->id)->where('priority', 1)->count()
                );
                $worksheet->setCellValue(
                    $alphabet[$startColumn].($z+4), $tickets->where('customer_id', $subItem->id)->where('priority', 2)->count()
                );
                $worksheet->setCellValue(
                    $alphabet[$startColumn].($z+5), $tickets->where('customer_id', $subItem->id)->where('priority', 3)->count()
                );
                $worksheet->setCellValue(
                    $alphabet[$startColumn].($z+6), $tickets->where('customer_id', $subItem->id)->where('priority', 4)->count()
                );
                $startColumn++;
            }

        }


    }
    private function makeSummary(&$spreadsheet, &$i, $tableStyle, $tickets){
        $worksheet = $spreadsheet->createSheet();
        $worksheet->setTitle('Zusammenfassung');
        $table = new Table('A1:B10', 'Zusammenfassung');

        $table->setStyle($tableStyle);
        $worksheet->addTable($table);

        $worksheet->setAutoFilter(null);

        $worksheet->setCellValue('A1', 'Anzahl');
        $worksheet->setCellValue('B1', 'Gesamt');
        $worksheet->setCellValue('A3', 'Anzahl Tickets');
        $worksheet->setCellValue('B3', $tickets->count());
        $worksheet->setCellValue('A4', 'Anzahl Zeit');

        $worksheet->setCellValue('B4',$tickets->sum('works_sum_time'));

        $worksheet->setCellValue('A5', 'Anzahl Betreff');

        $worksheet->setCellValue('B5', $tickets->unique('customer_id')->count());

        $worksheet->setCellValue('A6', 'Anzahl Project');

        $worksheet->setCellValue('B6', $tickets->unique('project_id')->count());
        $worksheet->setCellValue('A7', 'Anzahl Prio-Level KRITISCH');
        $worksheet->setCellValue('B7', $tickets->where('priority', 1)->count());
        $worksheet->setCellValue('A8', 'Anzahl Prio-Level HIGH');
        $worksheet->setCellValue('B8', $tickets->where('priority', 2)->count());
        $worksheet->setCellValue('A9', 'Anzahl Prio-Level NORMAL');
        $worksheet->setCellValue('B9', $tickets->where('priority', 3)->count());
        $worksheet->setCellValue('A10', 'Anzahl Prio-Level LOW');
        $worksheet->setCellValue('B10', $tickets->where('priority', 4)->count());


        return $worksheet;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $filename = 'dailybusiness.xlsx';

        $spreadsheet = new Spreadsheet();
        $tableStyle = new TableStyle();
        $tableStyle->setTheme(TableStyle::TABLE_STYLE_LIGHT21);
        $tableStyle->setShowRowStripes(true);
        $i=0;
        $tickets=$this->makeTicketList($spreadsheet, $i, $tableStyle);

// page Zusammanfassung

        $z=12;

        $worksheet=$this->makeSummary($spreadsheet, $i, $tableStyle, $tickets);
        $this->makeFieldSummary($worksheet, $tickets, $z, $tableStyle,'customer_id', 'Kundenstatistik', 'Kunde');
        $this->makeFieldSummary($worksheet, $tickets, $z+8, $tableStyle,'user_id', 'Userstatistik', 'User');
        $this->makeFieldSummary($worksheet, $tickets, $z+16, $tableStyle,'project_id', 'Projectstatistik', 'Projekt');

        foreach (range('A', $worksheet->getHighestColumn()) as $col) {
            $worksheet->getColumnDimension($col)->setAutoSize(true);
        }


        $spreadsheet->setActiveSheetIndex(0);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        //store excel file in storage…
        Storage::disk('local')->put('reports/'.$filename, $content);

        $data = [];
        $sendEmails=SetSendEmail::where('prefix', 'dailybusiness')->get('email')->toarray();
        $emailRecipients = [];
        foreach($sendEmails as $sendEmail){
            $emailRecipients[] = $sendEmail['email'];
        }
//
      	Mail::send(['text'=>"daily"], $data, function($message) use ($filename, $emailRecipients) {
//        	$message->to('david.cai@power4-its.de', 'PLZT Tool')->subject('TG - Statistik');
          $message->to($emailRecipients)->subject('TG - Statistik');

            $message->from('info@power4-its.com','power4-its.de');
         	$message->attach(storage_path("app/reports/" . $filename));
      	});
        return 0;
    }
}
