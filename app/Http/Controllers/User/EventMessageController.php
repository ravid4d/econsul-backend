<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;


class EventMessageController extends Controller
{
    public function getEventMessage()
    {
        // Get the current year and next year
        $currentYear = Carbon::now()->year;
        $nextYear = $currentYear + 1;

        // Define the event dates
        $startDate = Carbon::create($currentYear, 10, 4);  // Start date (October 4th of current year)
        $endDate = Carbon::create($currentYear, 11, 7);    // End date (November 7th of current year)
        $resultDateCurrentYear = Carbon::create($currentYear, 5, 3); // Result date for current year (May 3rd of the current year)
        $resultDateNextYear = Carbon::create($nextYear, 5, 3);  // Result date for next year (May 3rd of next year)

        $currentDate = Carbon::now();  
        if ($currentDate->lt($resultDateCurrentYear)) {
            
            $remainingDays = $currentDate->diffInDays($resultDateCurrentYear, false);

            return [
                'message' => "DV-$currentYear Lottery Results Date:",
                'remaining_days' => round($remainingDays), // Whole days only
                'event_date' => $resultDateCurrentYear->toFormattedDateString(),
            ];
        } elseif ($currentDate->lt($startDate)) {
            $remainingDays = $currentDate->diffInDays($startDate, false);

            return [
                'message' => "DV-$nextYear Start date:",
                'remaining_days' => round($remainingDays), // Whole days only
                'event_date' => $startDate->toFormattedDateString(),
            ];
        } elseif ($currentDate->lt($endDate)) {
            // If the visit date is between start date and end date, show the deadline
            $remainingDays = $currentDate->diffInDays($endDate, false);

            return [
                'message' => "DV-$nextYear Deadline:",
                'remaining_days' => round($remainingDays), // Whole days only
                'event_date' => $endDate->toFormattedDateString(),
            ];
        } else {
            $remainingDays = $currentDate->diffInDays($resultDateNextYear, false);

            return [
                'message' => "DV-$nextYear Lottery Results Date:",
                'remaining_days' => round($remainingDays), // Whole days only
                'event_date' => $resultDateNextYear->toFormattedDateString(),
            ];
        }
    }

    // Method to get the next event message based on the current date
    private function getNextEventMessage(array $events)
    {
        $today = Carbon::now();
        return $events[0]['date'];
        // Loop through each event and return the first one that hasn't passed
        foreach ($events as $event) {
            if ($today->lt($event['date'])) {
                // Calculate remaining days and include fraction of the day
                $remainingDays = $today->diffInDays($event['date'], false);

                return [
                    'message' => $event['message'],
                    'remaining_days' => round($remainingDays), // Whole days only
                    'date' => $event['date'],
                ];
            }
        }

        // if($events[0]->date)

        // If all events are passed, return the last event (results)
        $lastEvent = end($events);
        return [
            'message' => "0 days left, last event was " . $lastEvent['message'],
            'remaining_days' => 0,
            'date' => $lastEvent['date'],
        ];
    }

}
