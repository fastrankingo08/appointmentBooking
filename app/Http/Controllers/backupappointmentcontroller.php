<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentLeave;
use App\Models\Appointment;
use App\Models\Configuration;
use App\Models\FullyBookedDate;
use App\Models\HolidayCalendar;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Http\Request;

class backupappointmentcontroller extends Controller
{

    public function qualityReject(Request $request, \App\Models\Appointment $appointment)
    {
        // Optionally, perform additional checks or logging before deletion

        // Delete the appointment record
        $appointment->delete();

        // Return a JSON response indicating success
        return response()->json([
            'success' => true,
            'message' => 'Appointment rejected and deleted successfully.'
        ]);
    }


    public function qualityApprove(Request $request, $appointmentId)
    {
        // 1. Retrieve the appointment record.
        $appointment = Appointment::find($appointmentId);
        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found.'
            ], 404);
        }

        // If the appointment is already finalized, nothing more to do.
        if (!$appointment->is_temporary_assigned) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment is already finalized.'
            ]);
        }

        // 2. Check for conflict: Look for other appointments on the same date and slot using the same agent.
        $conflictCount = Appointment::where('appointment_date', $appointment->appointment_date)
            ->where('slot_id', $appointment->slot_id)
            ->where('agent_id', $appointment->agent_id)
            ->where('id', '!=', $appointment->id)
            ->count();

        if ($conflictCount > 0) {
            // Conflict found. We need to reassign the appointment.
            // Load global configuration settings for half-day boundaries.
            $configurations = Configuration::pluck('value', 'key');
            $firstHalfStart = $configurations->get('first_half_start', '09:15:00');
            $firstHalfEnd = $configurations->get('first_half_end', '12:00:00');
            $secondHalfStart = $configurations->get('second_half_start', '13:00:00');
            $secondHalfEnd = $configurations->get('second_half_end', '17:30:00');

            // Retrieve the selected slot details (to know its start time).
            $slot = Slot::find($appointment->slot_id);
            if (!$slot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot not found for the appointment.'
                ], 404);
            }
            $slotStartTime = $slot->start_time;

            // Fetch all active agents.
            $allAgentIds = Agent::where('is_active', 1)->pluck('id');

            // Retrieve agent leaves for the appointment date.
            $agentLeaves = AgentLeave::where('leave_date', $appointment->appointment_date)->get();

            // Filter available agents for the slot based on its time.
            if ($slotStartTime >= $firstHalfStart && $slotStartTime < $firstHalfEnd) {
                $leavesForSlot = $agentLeaves->filter(function ($leave) {
                    return in_array($leave->leave_of, ['full_day', 'first_half']);
                });
            } elseif ($slotStartTime >= $secondHalfStart && $slotStartTime < $secondHalfEnd) {
                $leavesForSlot = $agentLeaves->filter(function ($leave) {
                    return in_array($leave->leave_of, ['full_day', 'second_half']);
                });
            } else {
                $leavesForSlot = collect();
            }

            $agentIdsOnLeave = $leavesForSlot->pluck('agent_id')->unique();

            // Exclude the conflicting agent from available agents.
            //  $availableAgentIds = $allAgentIds->diff($agentIdsOnLeave)->diff(collect([$appointment->agent_id]));

            $assignedAgentIdsForSlot = Appointment::where('appointment_date', $appointment->appointment_date)
                ->where('slot_id', $appointment->slot_id)
                ->pluck('agent_id')
                ->unique();

            $availableAgentIds = $allAgentIds
                ->diff($agentIdsOnLeave)
                ->diff($assignedAgentIdsForSlot);

            if ($availableAgentIds->isEmpty()) {
                // No alternative agent available.
                $appointment->reserved_reason = 'Conflict detected: No alternative agent available. Appointment requires rework.';
                // Optionally, you may keep the appointment temporary to indicate rework.
                // $appointment->is_temporary_assigned remains 1.

                // rework logic will come here
                return response()->json([
                    'success' => false,
                    'message' => 'No Agent Available for this Time Slot , Reassign to Lead Agent',
                    'appointment' => $appointment
                ]);
            } else {
                // Pick an alternative agent. For simplicity, choose the first available.
                $selectedAlternative = $availableAgentIds->first();
                $appointment->agent_id = $selectedAlternative;
                // Finalize the assignment.
                $appointment->is_temporary_assigned = 0;

                // Set the appointment status as "Approved" after quality control.
                $appointment->status = 'Approved';
                $appointment->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Appointment approved successfully.',
                    'appointment' => $appointment
                ]);
            }
        } else {
            // No conflict: Finalize the appointment.
            $appointment->is_temporary_assigned = 0;
            // Set the appointment status as "Approved" after quality control.
            $appointment->status = 'Approved';
            $appointment->save();

            return response()->json([
                'success' => true,
                'message' => 'Appointment approved successfully.',
                'appointment' => $appointment
            ]);
        }
    }


    public function qualitypending()
    {
        $pending_Appointments = Appointment::with('agent', 'slot')->where('status', 'pending')->get();
        return view('quality-pending', ['pending_Appointments' => $pending_Appointments]);
    }
    public function bookappointmentpage()
    {
        // Retrieve all holiday dates as an array of strings (formatted as 'Y-m-d')
        $holidays = HolidayCalendar::where('holiday_type', 'full_day')->pluck('holiday_date')->map(function ($date) {
            return date('Y-m-d', strtotime($date));
        })->toArray();

        $fullyBookedDates = FullyBookedDate::pluck('date')->map(function ($date) {
            return date('Y-m-d', strtotime($date));
        })
            ->toArray();

        $mergedDates = array_unique(array_merge($holidays, $fullyBookedDates));
        // Pass the holiday dates to the view.
        return view('book-appointment', ['holidays' => $mergedDates]);
    }

    //
    // public function store(Request $request)
    // {
    //     // 1. Validate the request data.
    //     $request->validate([
    //         'appointment_date' => 'required|date',
    //         'slot_id'          => 'required|exists:slots,id',
    //         // Add additional validation as needed.
    //     ]);

    //     $appointment_date = $request->input('appointment_date');
    //     $slot_id = $request->input('slot_id');

    //     // 2. Retrieve configuration for maximum calls per agent.
    //     $maxCall = (int) Configuration::where('key', 'max_call_per_agent')->value('value') ?? 5;

    //     // 3. Retrieve the selected slot details.
    //     $slot = Slot::find($slot_id);
    //     if (!$slot) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Selected slot not found.'
    //         ], 404);
    //     }
    //     $slotStartTime = $slot->start_time;

    //     // 4. Load global configuration settings (half-day boundaries).
    //     $configurations = Configuration::pluck('value', 'key');
    //     $firstHalfStart  = $configurations->get('first_half_start', '09:15:00');
    //     $firstHalfEnd    = $configurations->get('first_half_end', '12:00:00');
    //     $secondHalfStart = $configurations->get('second_half_start', '13:00:00');
    //     $secondHalfEnd   = $configurations->get('second_half_end', '17:30:00');

    //     // 5. Fetch all active agents.
    //     $allAgentIds = Agent::where('is_active', 1)->pluck('id');

    //     // 6. Retrieve all agent leaves for the appointment date.
    //     $agentLeaves = AgentLeave::where('leave_date', $appointment_date)->get();

    //     // 7. Filter available agents based on slot's time period.
    //     if ($slotStartTime >= $firstHalfStart && $slotStartTime < $firstHalfEnd) {
    //         // For first-half slots, exclude agents on 'full_day' or 'first_half' leave.
    //         $leavesForSlot = $agentLeaves->filter(function ($leave) {
    //             return in_array($leave->leave_of, ['full_day', 'first_half']);
    //         });
    //     } elseif ($slotStartTime >= $secondHalfStart && $slotStartTime < $secondHalfEnd) {
    //         // For second-half slots, exclude agents on 'full_day' or 'second_half' leave.
    //         $leavesForSlot = $agentLeaves->filter(function ($leave) {
    //             return in_array($leave->leave_of, ['full_day', 'second_half']);
    //         });
    //     } else {
    //         $leavesForSlot = collect();
    //     }
    //     $agentIdsOnLeave = $leavesForSlot->pluck('agent_id')->unique();
    //     $availableAgentIds = $allAgentIds->diff($agentIdsOnLeave);
    //     if ($availableAgentIds->isEmpty()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No available agents for the selected slot on this date.'
    //         ], 422);
    //     }

    //     // 8. Select the agent with the fewest appointments who has not reached the max call limit.
    //     $selectedAgentId = null;
    //     $minAppointments = null;
    //     foreach ($availableAgentIds as $agent_id) {
    //         $appointmentCount = Appointment::where('appointment_date', $appointment_date)
    //             ->where('agent_id', $agent_id)
    //             ->count();

    //         // Skip this agent if they've reached or exceeded the maximum allowed appointments.
    //         if ($appointmentCount >= $maxCall) {
    //             continue;
    //         }

    //         if (is_null($minAppointments) || $appointmentCount < $minAppointments) {
    //             $minAppointments = $appointmentCount;
    //             $selectedAgentId = $agent_id;
    //         }
    //     }

    //     if (!$selectedAgentId) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'All available agents have reached their maximum appointment limit for the selected date.'
    //         ], 422);
    //     }

    //     // 9. Create the appointment record.
    //     $appointment = new Appointment();
    //     $appointment->slot_id = $slot_id;
    //     $appointment->appointment_date = $appointment_date;
    //     // If lead details are provided, you can assign them; otherwise, leave as null.
    //     $appointment->lead_id = $request->input('lead_id');
    //     $appointment->agent_id = $selectedAgentId;
    //     $appointment->status = 'Booked';
    //     $appointment->save();

    //     // 10. Return success response.
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Appointment booked successfully.',
    //         'appointment' => $appointment
    //     ]);
    // }

    //**********************************************main code******************************************************* */
    // public function store(Request $request)
    // {
    //     // 1. Validate the request data.
    //     $request->validate([
    //         'appointment_date' => 'required|date',
    //         'slot_id' => 'required|exists:slots,id',
    //     ]);

    //     $appointment_date = $request->input('appointment_date');
    //     $slot_id = $request->input('slot_id');



    //     // 2. Retrieve configuration for maximum calls per agent.
    //     $maxCall = (int) Configuration::where('key', 'max_call_per_agent')->value('value') ?? 5;

    //     // 3. Retrieve the selected slot details.
    //     $slot = Slot::find($slot_id);
    //     if (!$slot) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Selected slot not found.'
    //         ], 404);
    //     }
    //     $slotStartTime = $slot->start_time;

    //     // 4. Load global configuration settings (half-day boundaries).
    //     $configurations = Configuration::pluck('value', 'key');
    //     $firstHalfStart = $configurations->get('first_half_start', '09:15:00');
    //     $firstHalfEnd = $configurations->get('first_half_end', '12:00:00');
    //     $secondHalfStart = $configurations->get('second_half_start', '13:00:00');
    //     $secondHalfEnd = $configurations->get('second_half_end', '17:30:00');

    //     // 5. Fetch all active agents.
    //     $allAgentIds = Agent::where('is_active', 1)->pluck('id');

    //     // 6. Retrieve all agent leaves for the appointment date.
    //     $agentLeaves = AgentLeave::where('leave_date', $appointment_date)->get();

    //     // 7. Filter available agents based on the slot's time period.
    //     if ($slotStartTime >= $firstHalfStart && $slotStartTime < $firstHalfEnd) {
    //         $leavesForSlot = $agentLeaves->filter(function ($leave) {
    //             return in_array($leave->leave_of, ['full_day', 'first_half']);
    //         });
    //     } elseif ($slotStartTime >= $secondHalfStart && $slotStartTime < $secondHalfEnd) {
    //         $leavesForSlot = $agentLeaves->filter(function ($leave) {
    //             return in_array($leave->leave_of, ['full_day', 'second_half']);
    //         });
    //     } else {
    //         $leavesForSlot = collect();
    //     }
    //     $agentIdsOnLeave = $leavesForSlot->pluck('agent_id')->unique();
    //     $availableAgentIds = $allAgentIds->diff($agentIdsOnLeave);

    //     // 8. Fetch agents who are already assigned to the same slot on the appointment date.
    //     $assignedAgentIdsForSlot = Appointment::where('appointment_date', $appointment_date)
    //         ->where('slot_id', $slot_id)
    //         ->pluck('agent_id');

    //     // 9. Exclude agents who are already assigned to this slot.
    //     $availableAgentIds = $availableAgentIds->diff($assignedAgentIdsForSlot);

    //     if ($availableAgentIds->isEmpty()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No available agents for the selected slot on this date.'
    //         ], 422);
    //     }

    //     // 10. Precompute appointment counts for each agent (to avoid per-agent queries).
    //     $appointmentsByAgent = Appointment::where('appointment_date', $appointment_date)
    //         ->selectRaw('agent_id, COUNT(*) as appointment_count')
    //         ->groupBy('agent_id')
    //         ->get()
    //         ->keyBy('agent_id');

    //     // 11. Select the agent with the fewest appointments who has not reached the max call limit.
    //     $selectedAgentId = null;
    //     $minAppointments = null;
    //     foreach ($availableAgentIds as $agent_id) {
    //         // Retrieve appointment count from the precomputed collection (default 0 if not present)
    //         $appointmentCount = isset($appointmentsByAgent[$agent_id])
    //             ? $appointmentsByAgent[$agent_id]->appointment_count
    //             : 0;

    //         // Skip agent if they've reached the max call limit.
    //         if ($appointmentCount >= $maxCall) {
    //             continue;
    //         }

    //         if (is_null($minAppointments) || $appointmentCount < $minAppointments) {
    //             $minAppointments = $appointmentCount;
    //             $selectedAgentId = $agent_id;
    //         }
    //     }

    //     if (!$selectedAgentId) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'All available agents have reached their maximum appointment limit for the selected date.'
    //         ], 422);
    //     }

    //     // 12. Create the appointment record.
    //     $appointment = new Appointment();
    //     $appointment->slot_id = $slot_id;
    //     $appointment->appointment_date = $appointment_date;
    //     $appointment->lead_id = $request->input('lead_id'); // if provided
    //     $appointment->agent_id = $selectedAgentId;
    //     $appointment->status = 'pending';
    //     $appointment->save();

    //     // 13. Update Fully Booked Dates:
    //     // Precompute appointment counts for all active slots on the date.
    //     $appointmentsBySlot = Appointment::where('appointment_date', $appointment_date)
    //         ->selectRaw('slot_id, COUNT(*) as booked_count')
    //         ->groupBy('slot_id')
    //         ->get()
    //         ->keyBy('slot_id');

    //     // Fetch all active slots.
    //     $activeSlots = Slot::where('is_active', 1)->get();

    //     // Define a helper function for slot capacity.
    //     $getTotalCapacityForSlot = function ($slotStartTime) use ($allAgentIds, $agentLeaves, $firstHalfStart, $firstHalfEnd, $secondHalfStart, $secondHalfEnd, ) {
    //         if ($slotStartTime >= $firstHalfStart && $slotStartTime < $firstHalfEnd) {
    //             $leavesForSlot = $agentLeaves->filter(function ($leave) {
    //                 return in_array($leave->leave_of, ['full_day', 'first_half']);
    //             });
    //         } elseif ($slotStartTime >= $secondHalfStart && $slotStartTime < $secondHalfEnd) {
    //             $leavesForSlot = $agentLeaves->filter(function ($leave) {
    //                 return in_array($leave->leave_of, ['full_day', 'second_half']);
    //             });
    //         } else {
    //             $leavesForSlot = collect();
    //         }
    //         $agentIdsOnLeave = $leavesForSlot->pluck('agent_id')->unique();
    //         return $allAgentIds->diff($agentIdsOnLeave)->count();
    //     };

    //     $isDateFullyBooked = true;
    //     foreach ($activeSlots as $activeSlot) {
    //         $totalCapacity = $getTotalCapacityForSlot($activeSlot->start_time);
    //         $bookedCount = $appointmentsBySlot->has($activeSlot->id)
    //             ? $appointmentsBySlot->get($activeSlot->id)->booked_count
    //             : 0;
    //         if (($totalCapacity - $bookedCount) > 0) {
    //             $isDateFullyBooked = false;
    //             break; // As soon as one slot has availability, we stop checking further.
    //         }
    //     }

    //     if ($isDateFullyBooked) {
    //         FullyBookedDate::updateOrCreate(
    //             ['date' => $appointment_date],
    //             ['updated_at' => now()]
    //         );
    //     }

    //     // 14. Return success response.
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Appointment booked successfully.',
    //         'appointment' => $appointment
    //     ]);
    // }
    //***********************************************end main code****************************************************** */
    public function store(Request $request)
    {
        // 1. Validate the request data.
        $request->validate([
            'appointment_date' => 'required|date',
            'slot_id' => 'required|exists:slots,id',
        ]);

        $appointment_date = $request->input('appointment_date');
        $slot_id = $request->input('slot_id');

        // 2. Retrieve configuration for maximum calls per agent.
        $maxCall = (int) Configuration::where('key', 'max_call_per_agent')->value('value') ?? 5;

        // 3. Retrieve the selected slot details.
        $slot = Slot::find($slot_id);
        if (!$slot) {
            return response()->json([
                'success' => false,
                'message' => 'Selected slot not found.'
            ], 404);
        }
        $slotStartTime = $slot->start_time;

        // 4. Load global configuration settings (half-day boundaries).
        $configurations = Configuration::pluck('value', 'key');
        $firstHalfStart = $configurations->get('first_half_start', '09:15:00');
        $firstHalfEnd = $configurations->get('first_half_end', '12:00:00');
        $secondHalfStart = $configurations->get('second_half_start', '13:00:00');
        $secondHalfEnd = $configurations->get('second_half_end', '17:30:00');

        // 5. Fetch all active agents.
        $allAgentIds = Agent::where('is_active', 1)->pluck('id');

        // 6. Retrieve all agent leaves for the appointment date.
        $agentLeaves = AgentLeave::where('leave_date', $appointment_date)->get();

        // 7. Filter available agents based on the slot's time period.
        if ($slotStartTime >= $firstHalfStart && $slotStartTime < $firstHalfEnd) {
            $leavesForSlot = $agentLeaves->filter(function ($leave) {
                return in_array($leave->leave_of, ['full_day', 'first_half']);
            });
        } elseif ($slotStartTime >= $secondHalfStart && $slotStartTime < $secondHalfEnd) {
            $leavesForSlot = $agentLeaves->filter(function ($leave) {
                return in_array($leave->leave_of, ['full_day', 'second_half']);
            });
        } else {
            $leavesForSlot = collect();
        }
        $agentIdsOnLeave = $leavesForSlot->pluck('agent_id')->unique();
        $availableAgentIds = $allAgentIds->diff($agentIdsOnLeave);

        // 8. Fetch agents who are already assigned to the same slot on the appointment date.
        $assignedAgentIdsForSlot = Appointment::where('appointment_date', $appointment_date)
            ->where('slot_id', $slot_id)
            ->pluck('agent_id');

        // 9. Exclude agents who are already assigned to this slot.
        $availableAgentIds = $availableAgentIds->diff($assignedAgentIdsForSlot);

        if ($availableAgentIds->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No available agents for the selected slot on this date.'
            ], 422);
        }

        // 10. Precompute appointment counts for each agent (to avoid per-agent queries).
        $appointmentsByAgent = Appointment::where('appointment_date', $appointment_date)
            ->selectRaw('agent_id, COUNT(*) as appointment_count')
            ->groupBy('agent_id')
            ->get()
            ->keyBy('agent_id');

        // 11. Select the agent with the fewest appointments who has not reached the max call limit.
        $selectedAgentId = null;
        $minAppointments = null;
        foreach ($availableAgentIds as $agent_id) {
            $appointmentCount = isset($appointmentsByAgent[$agent_id])
                ? $appointmentsByAgent[$agent_id]->appointment_count
                : 0;
            if ($appointmentCount >= $maxCall) {
                continue;
            }
            if (is_null($minAppointments) || $appointmentCount < $minAppointments) {
                $minAppointments = $appointmentCount;
                $selectedAgentId = $agent_id;
            }
        }
        if (!$selectedAgentId) {
            return response()->json([
                'success' => false,
                'message' => 'All available agents have reached their maximum appointment limit for the selected date.'
            ], 422);
        }

        // 12. Create the appointment record.
        $appointment = new Appointment();
        $appointment->slot_id = $slot_id;
        $appointment->appointment_date = $appointment_date;
        $appointment->lead_id = $request->input('lead_id'); // if provided
        $appointment->agent_id = $selectedAgentId;
        $appointment->status = 'pending';
        $appointment->save();

        // 13. Update Fully Booked Dates:
        // Precompute appointment counts for all active slots on the date.
        $appointmentsBySlot = Appointment::where('appointment_date', $appointment_date)
            ->selectRaw('slot_id, COUNT(*) as booked_count')
            ->groupBy('slot_id')
            ->get()
            ->keyBy('slot_id');

        // Fetch all active slots.
        $activeSlots = Slot::where('is_active', 1)->get();

        // NEW: Retrieve holiday info for the appointment date.
        $holiday = HolidayCalendar::where('holiday_date', $appointment_date)->first();
        $holidayType = $holiday ? $holiday->holiday_type : null;

        // Updated helper function for slot capacity, now accounting for holiday blocks.
        $getTotalCapacityForSlot = function ($slotStartTime) use ($allAgentIds, $agentLeaves, $firstHalfStart, $firstHalfEnd, $secondHalfStart, $secondHalfEnd, $holidayType) {
            // If a half-day holiday blocks this slot, capacity is zero.
            if ($holidayType === 'first_half' && $slotStartTime >= $firstHalfStart && $slotStartTime < $firstHalfEnd) {
                return 0;
            }
            if ($holidayType === 'second_half' && $slotStartTime >= $secondHalfStart && $slotStartTime < $secondHalfEnd) {
                return 0;
            }

            // Otherwise, calculate capacity based on agent leaves.
            if ($slotStartTime >= $firstHalfStart && $slotStartTime < $firstHalfEnd) {
                $leavesForSlot = $agentLeaves->filter(function ($leave) {
                    return in_array($leave->leave_of, ['full_day', 'first_half']);
                });
            } elseif ($slotStartTime >= $secondHalfStart && $slotStartTime < $secondHalfEnd) {
                $leavesForSlot = $agentLeaves->filter(function ($leave) {
                    return in_array($leave->leave_of, ['full_day', 'second_half']);
                });
            } else {
                $leavesForSlot = collect();
            }
            $agentIdsOnLeave = $leavesForSlot->pluck('agent_id')->unique();
            return $allAgentIds->diff($agentIdsOnLeave)->count();
        };

        $isDateFullyBooked = true;
        foreach ($activeSlots as $activeSlot) {
            $totalCapacity = $getTotalCapacityForSlot($activeSlot->start_time);
            $bookedCount = $appointmentsBySlot->has($activeSlot->id)
                ? $appointmentsBySlot->get($activeSlot->id)->booked_count
                : 0;
            if (($totalCapacity - $bookedCount) > 0) {
                $isDateFullyBooked = false;
                break;
            }
        }

        if ($isDateFullyBooked) {
            FullyBookedDate::updateOrCreate(
                ['date' => $appointment_date],
                ['updated_at' => now()]
            );
        }

        // 14. Return success response.
        return response()->json([
            'success' => true,
            'message' => 'Appointment booked successfully.',
            'appointment' => $appointment
        ]);
    }


    // public function getAvailableSlots(Request $request)
    // {
    //     // 1. Input Validation: Retrieve appointment_date from request.
    //     $appointment_date = $request->input('appointment_date');
    //     if (!$appointment_date) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Appointment date is required.'
    //         ], 400);
    //     }

    //     // 2. Holiday Check: Return empty slots if the date is a holiday.

    //     if (HolidayCalendar::where('holiday_date', $appointment_date)->exists()) {
    //         return response()->json([
    //             'success' => true,
    //             'appointment_date' => $appointment_date,
    //             'availableSlots' => [],
    //             'message' => 'No appointments can be booked on holidays.'
    //         ]);
    //     }

    //     // 3. Load Global Configurations as key-value pairs.
    //     $configurations = Configuration::pluck('value', 'key');
    //     $firstHalfStart  = $configurations->get('first_half_start', '09:15:00');
    //     $firstHalfEnd    = $configurations->get('first_half_end', '12:00:00');
    //     $secondHalfStart = $configurations->get('second_half_start', '13:00:00');
    //     $secondHalfEnd   = $configurations->get('second_half_end', '17:30:00');

    //     // 4. Fetch Active Agents: Get all agent IDs where is_active = true.
    //     $allAgentIds = Agent::where('is_active', true)->pluck('id');

    //     // 5. Fetch Agent Leaves for the Date.
    //     $agentLeaves = AgentLeave::where('leave_date', $appointment_date)->get();

    //     // 6. Fetch Active Slot Templates.
    //     $slots = Slot::where('is_active', true)->get();

    //     // 7. Retrieve Appointment Counts by Slot.
    //     $appointmentsBySlot = Appointment::where('appointment_date', $appointment_date)
    //         ->selectRaw('slot_id, COUNT(*) as booked_count')
    //         ->groupBy('slot_id')
    //         ->get()
    //         ->keyBy('slot_id');

    //     // 8. Define a helper function to calculate total capacity for a slot.
    //     $getTotalCapacityForSlot = function ($slotStartTime) use ($allAgentIds, $agentLeaves, $firstHalfStart, $firstHalfEnd, $secondHalfStart, $secondHalfEnd) {
    //         if ($slotStartTime >= $firstHalfStart && $slotStartTime < $firstHalfEnd) {
    //             // For first-half slots, filter leaves with leave_of 'full_day' or 'first_half'.
    //             $leavesForSlot = $agentLeaves->filter(function ($leave) {
    //                 return in_array($leave->leave_of, ['full_day', 'first_half']);
    //             });
    //         } elseif ($slotStartTime >= $secondHalfStart && $slotStartTime < $secondHalfEnd) {
    //             // For second-half slots, filter leaves with leave_of 'full_day' or 'second_half'.
    //             $leavesForSlot = $agentLeaves->filter(function ($leave) {
    //                 return in_array($leave->leave_of, ['full_day', 'second_half']);
    //             });
    //         } else {
    //             $leavesForSlot = collect();
    //         }
    //         $agentIdsOnLeave = $leavesForSlot->pluck('agent_id')->unique();
    //         return $allAgentIds->diff($agentIdsOnLeave)->count();
    //     };

    //     // 9. Process each active slot and calculate available capacity.
    //     $availableSlots = collect();
    //     foreach ($slots as $slot) {
    //         $slotStartTime = $slot->start_time;
    //         $totalCapacity = $getTotalCapacityForSlot($slotStartTime);
    //         $bookedCount = $appointmentsBySlot->has($slot->id)
    //             ? $appointmentsBySlot->get($slot->id)->booked_count
    //             : 0;
    //         $availableCapacity = $totalCapacity - $bookedCount;

    //         if ($availableCapacity > 0) {
    //             $slot->available_capacity = $availableCapacity;
    //             $availableSlots->push($slot);
    //         }
    //     }

    //     // 10. Return JSON Response with appointment date and available slots.
    //     return response()->json([
    //         'success' => true,
    //         'appointment_date' => $appointment_date,
    //         'availableSlots' => $availableSlots
    //     ]);
    // }

    // after introducing first_half , second_half , full_day leave in holday
    public function getAvailableSlots(Request $request)
    {
        // 1. Input Validation: Retrieve appointment_date from request.
        $appointment_date = $request->input('appointment_date');
        if (!$appointment_date) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment date is required.'
            ], 400);
        }

        // 2. Holiday Check: Retrieve holiday record (if any) for the appointment_date.
        $holiday = HolidayCalendar::where('holiday_date', $appointment_date)->first();
        $holidayType = null;
        if ($holiday) {
            // If the holiday is a full day, no slots are available.
            if ($holiday->holiday_type === 'full_day') {
                return response()->json([
                    'success' => true,
                    'appointment_date' => $appointment_date,
                    'availableSlots' => [],
                    'message' => 'No appointments can be booked on full-day holidays.'
                ]);
            } else {
                // For half-day holidays, store the type ("first_half" or "second_half")
                $holidayType = $holiday->holiday_type;
            }
        }

        // 3. Load Global Configurations as key-value pairs.
        $configurations = Configuration::pluck('value', 'key');
        $firstHalfStart = $configurations->get('first_half_start', '09:15:00');
        $firstHalfEnd = $configurations->get('first_half_end', '12:00:00');
        $secondHalfStart = $configurations->get('second_half_start', '13:00:00');
        $secondHalfEnd = $configurations->get('second_half_end', '17:30:00');

        // 4. Fetch Active Agents: Get all agent IDs where is_active = true.
        $allAgentIds = Agent::where('is_active', true)->pluck('id');

        // 5. Fetch Agent Leaves for the Date.
        $agentLeaves = AgentLeave::where('leave_date', $appointment_date)->get();

        // 6. Fetch Active Slot Templates.
        $slots = Slot::where('is_active', true)->get();

        // 7. Retrieve Appointment Counts by Slot.
        $appointmentsBySlot = Appointment::where('appointment_date', $appointment_date)
            ->selectRaw('slot_id, COUNT(*) as booked_count')
            ->groupBy('slot_id')
            ->get()
            ->keyBy('slot_id');

        // 8. Define a helper function to calculate total capacity for a slot.
        $getTotalCapacityForSlot = function ($slotStartTime) use ($allAgentIds, $agentLeaves, $firstHalfStart, $firstHalfEnd, $secondHalfStart, $secondHalfEnd, $holidayType) {
            // If there is a half-day holiday, block the corresponding half.
            if ($holidayType === 'first_half' && $slotStartTime >= $firstHalfStart && $slotStartTime < $firstHalfEnd) {
                return 0;
            }
            if ($holidayType === 'second_half' && $slotStartTime >= $secondHalfStart && $slotStartTime < $secondHalfEnd) {
                return 0;
            }

            // Otherwise, calculate capacity based on agent leaves.
            if ($slotStartTime >= $firstHalfStart && $slotStartTime < $firstHalfEnd) {
                // For first-half slots, consider leaves with leave_of 'full_day' or 'first_half'.
                $leavesForSlot = $agentLeaves->filter(function ($leave) {
                    return in_array($leave->leave_of, ['full_day', 'first_half']);
                });
            } elseif ($slotStartTime >= $secondHalfStart && $slotStartTime < $secondHalfEnd) {
                // For second-half slots, consider leaves with leave_of 'full_day' or 'second_half'.
                $leavesForSlot = $agentLeaves->filter(function ($leave) {
                    return in_array($leave->leave_of, ['full_day', 'second_half']);
                });
            } else {
                $leavesForSlot = collect();
            }
            $agentIdsOnLeave = $leavesForSlot->pluck('agent_id')->unique();
            return $allAgentIds->diff($agentIdsOnLeave)->count();
        };

        // 9. Process each active slot and calculate available capacity.
        $availableSlots = collect();
        foreach ($slots as $slot) {
            $slotStartTime = $slot->start_time;
            $totalCapacity = $getTotalCapacityForSlot($slotStartTime);
            $bookedCount = $appointmentsBySlot->has($slot->id)
                ? $appointmentsBySlot->get($slot->id)->booked_count
                : 0;
            $availableCapacity = $totalCapacity - $bookedCount;

            if ($availableCapacity > 0) {
                $slot->available_capacity = $availableCapacity;
                $availableSlots->push($slot);
            }
        }

        // 10. Return JSON Response with appointment date and available slots.
        return response()->json([
            'success' => true,
            'appointment_date' => $appointment_date,
            'availableSlots' => $availableSlots
        ]);
    }
}
