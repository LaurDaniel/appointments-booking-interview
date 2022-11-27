<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(auth()->user()->hasRole('Admin')) {
            return view('admin.home');
        } else {
            return view('user.home');
        }

    }

    public function getAppointmentsAdmin(Request $request)
    {
        $end = $request->end;
        $start = $request->start;
        $appointments = Appointment::whereBetween('date', [$start, $end])->get();
        $events = [];
        foreach($appointments as $appointment) {
                $events[] = [
                    'title' => $appointment->user->name,
                    'start' => $appointment->start,
                    'end' => $appointment->end,
                    'className' => 'text-center',
                ];
        }
        return response()->json($events);

    }

    public function getAppointments(Request $request)
    {
        $end = $request->end;
        $start = $request->start;
        $appointments = Appointment::whereBetween('date', [$start, $end])->where('user_id',auth()->user()->id)->get();
        $events = [];
        foreach($appointments as $appointment) {
                $events[] = [
                    'title' => $appointment->time,
                    'start' => $appointment->start,
                    'end' => $appointment->end,
                    'className' => 'text-center',
                ];
        }
        return response()->json($events);

    }
}
