<?php

namespace Database\Seeders;

use DB;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    
        $data = [
            [
                'id' => 1,
                'name' => 'Projekt Nr. 1',
                'planning_status' => 1,
                'status' => 1
            ],[
                'id' => 2,
                'name' => 'Projekt Nr. 2',
                'planning_status' => 0,
                'status' => 0
            ],[
                'id' => 3,
                'name' => 'Projektverwaltung',
                'planning_status' => 0,
                'status' => 0
            ],[
                'id' => 4,
                'name' => 'PLZT',
                'planning_status' => 0,
                'status' => 0
            ]
        ];  
        
        DB::table('projects')->truncate();
        DB::table('projects')->insert($data);          
        
        $data = [
            [
                'id' => 1,
                'project_id' => 1,
                'milestone' => 'Außenbeleuchtung',
                'priority' => 1
                
            ],[
                'id' => 2,
                'project_id' => 1,
                'milestone' => 'Büro-Allgemeinbeleuchtung',
                'priority' => 1
            ],[
                'id' => 3,
                'project_id' => 1,
                'milestone' => 'Industriebeleuchtung',
                'priority' => 3
            ],[
                'id' => 4,
                'project_id' => 1,
                'milestone' => 'Lichtbandsysteme',
                'priority' => 1
            ],[
                'id' => 5,
                'project_id' => 1,
                'milestone' => 'Retail-Shopbeleuchtung',
                'priority' => 2
            ],[
                'id' => 6,
                'project_id' => 1,
                'milestone' => 'Röhren',
                'priority' => 1
            ],[
                'id' => 7,
                'project_id' => 3,
                'milestone' => 'Kundenverwaltung',
                'priority' => 1
            ],[
                'id' => 8,
                'project_id' => 3,
                'milestone' => 'Ticketsystem',
                'priority' => 2
            ],[
                'id' => 9,
                'project_id' => 3,
                'milestone' => 'Reporting / Statistiken',
                'priority' => 3
            ],[
                'id' => 10,
                'project_id' => 4,
                'milestone' => 'Kundenebene',
                'priority' => 3
            ]
        ];

        DB::table('milestones')->truncate();
        DB::table('milestones')->insert($data);
        
        $data = [
            [
                'id' => 1,
                'project_id' => 1,
                'milestone_id' => 1,
                'todo' => 'anfahrt',
                'discussed' => 1,
                'estimated_time' => 1,
                'status' => 1,
                'employee' => "SaKo" 
            ],[
                'id' => 2,
                'project_id' => 1,
                'milestone_id' => 1,
                'todo' => 'ausladen',
                'discussed' => 1,
                'estimated_time' => 1,
                'status' => 1,
                'employee' => "SaKo" 
            ],[
                'id' => '3',
                'project_id' => 1,
                'milestone_id' => 1,
                'todo' => 'auspacken',
                'discussed' => 1,
                'estimated_time' => 1,
                'status' => 1,
                'employee' => "SaKo" 
            ],[
                'id' => '4',
                'project_id' => 1,
                'milestone_id' => 1,
                'todo' => 'aufbauen',
                'discussed' => 1,
                'estimated_time' => 1,
                'status' => 1,
                'employee' => "SaKo" 
            ],[
                'id' => '5',
                'project_id' => 1,
                'milestone_id' => 1,
                'todo' => 'anbringen',
                'discussed' => 1,
                'estimated_time' => 1,
                'status' => 1,
                'employee' => "SaKo" 
            ],[
                'id' => 6,
                'project_id' => 3,
                'milestone_id' => 7,
                'todo' => 'Planung der Datenbank',
                'discussed' => 1,
                'estimated_time' => 1,
                'status' => 0,
                'employee' => "SaKo" 
            ],[
                'id' => 7,
                'project_id' => 3,
                'milestone_id' => 8,
                'todo' => 'Planung der Datenbank',
                'discussed' => 0,
                'estimated_time' => 1,
                'status' => 0,
                'employee' => "SaKo" 
            ],[
                'id' => 8,
                'project_id' => 3,
                'milestone_id' => 9,
                'todo' => 'Erstellung von Model, Migration u. Controler',
                'discussed' => 0,
                'estimated_time' => 2,
                'status' => 0,
                'employee' => "SaKo" 
            ],[
                'id' => 9,
                'project_id' => 4,
                'milestone_id' => 10,
                'todo' => 'Benutzerverwaltung',
                'discussed' => 0,
                'estimated_time' => 2,
                'status' => 1,
                'employee' => "SaKo" 
            ]
        ];

        DB::table('todos')->truncate();
        DB::table('todos')->insert($data);
                
    }
}
