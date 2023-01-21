<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon;
use PDF;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Nexmo;


class SchedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($limit)
    {
        $schedules = DB::table('schedule')
            ->join('clients', 'schedule.clients_ClientId', '=', 'clients.ClientId')
            ->orderBy('ScheduleDate', 'desc')
            ->orderBy('ScheduleTime', 'asc')
            ->take($limit)->get();
        // $schedules = Schedule::orderBy('ScheduleDate', 'desc')->take(10)->get();
        $payload = ['schedules' => $schedules];
        return response()->json($payload);
    }

    public function getTodaysSchedules() 
    {
        $schedules = DB::table('schedule')
            ->join('clients', 'schedule.clients_ClientId', '=', 'clients.ClientId')
            ->where('schedule.ScheduleDate', date("Y-m-d"))
            ->orderBy('ScheduleDate', 'desc')
            ->orderBy('ScheduleTime', 'asc')
            ->get();
        // $schedules = Schedule::orderBy('ScheduleDate', 'desc')->take(10)->get();
        $payload = ['schedules' => $schedules];
        return response()->json($payload);
    }

    public function getFutureSchedules() 
    {
        $schedules = DB::table('schedule')
            ->join('clients', 'schedule.clients_ClientId', '=', 'clients.ClientId')
            ->where('schedule.ScheduleDate', ">" ,date("Y-m-d"))
            ->orderBy('ScheduleDate', 'asc')
            ->orderBy('ScheduleTime', 'asc')
            ->get();
        // $schedules = Schedule::orderBy('ScheduleDate', 'desc')->take(10)->get();
        $payload = ['schedules' => $schedules];
        return response()->json($payload);
    }

    public function getSchedulesFromStartingDate($date) 
    {
        $schedules = DB::table('schedule')
            ->join('clients', 'schedule.clients_ClientId', '=', 'clients.ClientId')
            ->where('schedule.ScheduleDate', ">=" , $date)
            ->orderBy('ScheduleDate', 'desc')
            ->orderBy('ScheduleTime', 'desc')
            ->get();
        // $schedules = Schedule::orderBy('ScheduleDate', 'desc')->take(10)->get();
        $payload = ['schedules' => $schedules];
        return response()->json($payload);
    }

    public function getSchedulesBeforeEndDate($date) 
    {
        $schedules = DB::table('schedule')
            ->join('clients', 'schedule.clients_ClientId', '=', 'clients.ClientId')
            ->where('schedule.ScheduleDate', "<=" , $date)
            ->orderBy('ScheduleDate', 'desc')
            ->orderBy('ScheduleTime', 'desc')
            ->get();
        // $schedules = Schedule::orderBy('ScheduleDate', 'desc')->take(10)->get();
        $payload = ['schedules' => $schedules];
        return response()->json($payload);
    }

    public function getSchedulesFromDateToDate($startDate, $endDate) 
    {
        $schedules = DB::table('schedule')
            ->join('clients', 'schedule.clients_ClientId', '=', 'clients.ClientId')
            ->where('schedule.ScheduleDate', ">=" , $startDate)
            ->where('schedule.ScheduleDate', "<=" , $endDate)
            ->orderBy('ScheduleDate', 'desc')
            ->orderBy('ScheduleTime', 'asc')
            ->get();
        // $schedules = Schedule::orderBy('ScheduleDate', 'desc')->take(10)->get();
        $payload = ['schedules' => $schedules];
        return response()->json($payload);
    }

    public function getSchedulesForDate($date) {
        $schedules = DB::table('schedule')
            ->join('clients', 'schedule.clients_ClientId', '=', 'clients.ClientId')
            ->where('schedule.ScheduleDate', $date)
            ->orderBy('ScheduleDate', 'desc')
            ->orderBy('ScheduleTime', 'asc')
            ->get();
        // $schedules = Schedule::orderBy('ScheduleDate', 'desc')->take(10)->get();
        $payload = ['schedules' => $schedules];
        return response()->json($payload);
    }

    public function pdfSchedulesView() {
        $data = DB::table('schedule')
            ->join('clients', 'schedule.clients_ClientId', '=', 'clients.ClientId')
            ->where('schedule.ScheduleDate', date("Y-m-d"))
            ->orderBy('ScheduleTime', 'asc')->get();

        return view('pdfSchedulesView', ['schedules' => $data]);
      }

    public function createPDF() {
        $data = DB::table('schedule')
            ->join('clients', 'schedule.clients_ClientId', '=', 'clients.ClientId')
            ->where('schedule.ScheduleDate', date("Y-m-d"))
            ->orderBy('ScheduleTime', 'asc')->get();

        $now = Carbon\Carbon::now()->format('d-m-Y');

        $pdf = PDF::loadView('pdfSchedulesView', ['schedules' => $data, 'date' => $now]);

        return $pdf->download('programari_'. $now .'.pdf');
    }

    public function createTomorrowPDF() {
        $data = DB::table('schedule')
            ->join('clients', 'schedule.clients_ClientId', '=', 'clients.ClientId')
            ->where('schedule.ScheduleDate', Carbon\Carbon::now()->addDay()->format('Y-m-d'))
            ->orderBy('ScheduleTime', 'asc')->get();

        $tomorrow = Carbon\Carbon::now()->addDay()->format('d-m-Y');

        $pdf = PDF::loadView('pdfSchedulesView', ['schedules' => $data, 'date' => $tomorrow]);
  
        return $pdf->download('programari_'. $tomorrow .'.pdf');
    }

    public function getTodaysSchedulesCount() {
        $schedules = DB::table('schedule')
            ->join('clients', 'schedule.clients_ClientId', '=', 'clients.ClientId')
            ->where('schedule.ScheduleDate', date("Y-m-d"))
            ->orderBy('ScheduleDate', 'desc')
            ->orderBy('ScheduleTime', 'desc')
            ->get();
        // $schedules = Schedule::orderBy('ScheduleDate', 'desc')->take(10)->get();

        return $schedules->count();
    }

    public function getTomorrowsSchedulesCount() {
        $schedules = DB::table('schedule')
            ->join('clients', 'schedule.clients_ClientId', '=', 'clients.ClientId')
            ->where('schedule.ScheduleDate', Carbon\Carbon::now()->addDay()->format('Y-m-d'))
            ->orderBy('ScheduleDate', 'desc')
            ->orderBy('ScheduleTime', 'desc')
            ->get();
        // $schedules = Schedule::orderBy('ScheduleDate', 'desc')->take(10)->get();

        return $schedules->count();
    }

    public function schedulesHistory($id) {
        return Inertia::render('SchedulesHistory', ['clientHistoryId' => $id]);
    }

    public function getAllScheduleHistory($id) {
        $schedules = DB::table('schedule')
            ->join('clients', 'schedule.clients_ClientId', '=', 'clients.ClientId')
            ->where('clients.ClientId', $id)
            ->orderBy('ScheduleDate', 'desc')
            ->orderBy('ScheduleTime', 'asc')
            ->get();
        // $schedules = Schedule::orderBy('ScheduleDate', 'desc')->take(10)->get();
        $payload = ['schedules' => $schedules];
        return response()->json($payload);
    }

    public function sendNextDayScheduleSMS($phone) {
        Nexmo::message()->send([
            'to'   => $phone,
            'from' => 'Vonage SMS API',
            'text' => 'Welcome to the Codezen Application!'
    
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dateTime = new DateTime($request->scheduleDateTime);
        $date = $dateTime->format('Y/m/d');
        $time = $dateTime->format('H:i');

        $schedule = new Schedule;

        $schedule->ScheduleName = $request->scheduleName;
        $schedule->ScheduleDateTime = $request->scheduleDateTime;
        $schedule->ScheduleDate = $date;
        $schedule->ScheduleTime = $time;
        $schedule->ScheduleAddress = $request->scheduleAddress;
        $schedule->ScheduleDescription = $request->scheduleDescription;
        $schedule->ScheduleType = $request->scheduleType;
        $schedule->accounts_AccountId = $request->userId;
        $schedule->clients_ClientId = $request->customerId;

        $schedule->save();
    }

    public function deleteSchedule(Request $request) {
        $schedule = Schedule::find($request->scheduleIdToDelete);

        if (Auth::id() == $request->userId) {
            $schedule->delete();
        }
    }

    public function editScheduleView($id) {
        $schedule = DB::table('schedule')
        ->join('clients', 'schedule.clients_ClientId', '=', 'clients.ClientId')
        ->where('schedule.ScheduleId', $id)
        ->get();

        return Inertia::render('EditSchedule', ['schedule' => $schedule]);
    }

    public function editSchedule(Request $request) {
            $dateTime = new DateTime($request->scheduleDateTime);
            $date = $dateTime->format('Y/m/d');
            $time = $dateTime->format('H:i');

            $schedule = Schedule::find($request->scheduleId);

            $schedule->ScheduleName = $request->scheduleName;
            $schedule->ScheduleDateTime = $request->scheduleDateTime;
            $schedule->ScheduleDate = $date;
            $schedule->ScheduleTime = $time;
            $schedule->ScheduleAddress = $request->scheduleAddress;
            $schedule->ScheduleDescription = $request->scheduleDescription;
            $schedule->ScheduleType = $request->scheduleType;
            $schedule->accounts_AccountId = $request->userId;
            $schedule->clients_ClientId = $request->scheduleClientId;

            $schedule->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        //
    }
}
