<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon;
use Mail;
use App\Mail\NotifiyClientsAboutSchedule;

class NotifyClientsByEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:clientsByEmail';

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
            if($schedule->ClientEmail != null && $schedule->ClientEmail != "-")
            try {
                Mail::to($schedule->ClientEmail)->send(new NotifiyClientsAboutSchedule($schedule));
            } catch (\Throwable $th) {
                error_log("Error at sending reminder mail for ".$schedule->ClientEmail);
            }
        }
    }
}
