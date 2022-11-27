<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Http\Traits\GlobalTrait;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use GlobalTrait;
    public function index()
    {
        return view('booking');
    }

    public function availableSlots($date){
        $appointments = Appointment::where('date', $date)->pluck('time')->toArray();
        return array_diff(GlobalTrait::getHours(),$appointments);
    }

    public function getEvents(Request $request)
    {
        // dd($request->all());
        $end = $request->end;
        $start = Carbon::now()->toDateString() < $request->start ? $request->start : Carbon::now()->toDateString();
        $period = new CarbonPeriod($start, $end);
        $events = [];
        foreach($period as $date) {
            if($date->isWeekday() && $this->availableSlots($date->toDateString())){
                $events[] = [
                    'title' => 'Available',
                    'start' => $date,
                    'color' => '#00ff00',
                    'textColor' => '#000000',
                    'className' => 'text-center',
                ];
            }
        }
        return response()->json($events);
    }

    public function getHours(Request $request)
    {
        $date = $request->date;
        $hours = $this->availableSlots($date);
        return response()->json($hours);
    }

    public function bookAppointment(Request $request)
    {
        $appointment = Appointment::create([
            'user_id' => auth()->user()->id,
            'date' => $request->date,
            'time' => $request->time,
        ]);

        return response()->json($appointment);
    }
}
