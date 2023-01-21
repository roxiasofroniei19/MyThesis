<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Nexmo;
use DB;
use Carbon;

class NotifyClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:clients';

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
        $nextDaySchedules = DB::table('schedule')
        ->join('clients', 'schedule.clients_ClientId', '=', 'clients.ClientId')
        ->where('schedule.ScheduleDate', Carbon\Carbon::now()->addDay()->format('Y-m-d'))
        ->orderBy('ScheduleDate', 'asc')->get();

        
        foreach($nextDaySchedules as $schedule) {
            if($schedule->ClientPhone != "-" && $schedule->ClientPhone != null) {

                $text = "Reminder: Programare DeratDezin Vest: \n".$schedule->ClientName
                        ."\nData: ".$schedule->ScheduleDate
                        ."\nOra: ".$schedule->ScheduleTime
                        ."\nOperatiune: ".$schedule->ScheduleType;

                try {
                    Nexmo::message()->send([
                        'to'   => '+4'.$schedule->ClientPhone,
                        'from' => 'Vonage SMS API',
                        'text' => $text
                
                    ]);
                } catch (\Throwable $th) {
                    error_log("Eroare la trimitere SMS pentru ".$schedule.ClientPhone);
                }
                
            }
            
        }

        
    }
}
