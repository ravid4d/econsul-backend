<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;


class EventMessageController extends Controller
{
    public function getEventMessage()
    {
        // Get the current year dynamically
        $currentYear = Carbon::now()->year;
        $nextYear = $currentYear + 1;
        // Define event messages with dynamic years
        $eventMessages = [
            [
                'message' => "DV-$nextYear Start date:",
                'date' => Carbon::create($currentYear, 10, 4),
            ],
            [
                'message' => "DV-$nextYear Deadline:",
                'date' => Carbon::create($currentYear, 11, 7),
            ],
            [
                'message' => "DV-$nextYear Results:",
                'date' => Carbon::create($currentYear + 1, 5, 4),
            ],
        ];
    
        // Get the next event message and return it
        $nextEventMessage = $this->getNextEventMessage($eventMessages);
    
        // Return as JSON response with properly formatted remaining days
        return response()->json([
            'message' => $nextEventMessage['message'],
            'remaining_days' => $nextEventMessage['remaining_days'], // Whole days
            'event_date' => $nextEventMessage['date']->toDateString(),
        ]);
    }
    
    // Method to get the next event message based on the current date
    private function getNextEventMessage(array $events)
    {
        $today = Carbon::now();
    
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
    
        // If all events are passed, return the last event (results)
        $lastEvent = end($events);
        return [
            'message' => "0 days left, last event was " . $lastEvent['message'],
            'remaining_days' => 0,
            'date' => $lastEvent['date'],
        ];
    }
    
}
