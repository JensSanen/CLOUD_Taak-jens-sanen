<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LaravelWorkers;
use App\Models\LaravelWorkedHours;
use Illuminate\Support\Facades\Log;

class LaravelWorkedHoursController extends Controller
{
    // Functie om alle gewerkte uren op te halen voor een specifiek project
    public function getProjectWorkedHours($projectId)
    {
        try {
            // Haal alle werknemers op
            $workers = LaravelWorkers::all()->toArray();

            // Haal alle gewerkte uren voor dit project op
            $workedHours = LaravelWorkedHours::where('projectId', $projectId)->get()->toArray();

            // Maak een array waarin we het aantal gewerkte uren per werknemer bijhouden
            foreach ($workers as &$worker) {
                $worker['workedHours'] = 0; // Default waarde
            }

            // Voeg de gewerkte uren toe aan de juiste werknemer
            foreach ($workedHours as $worked_hour) {
                foreach ($workers as &$worker) {
                    if ($worker['workerId'] == $worked_hour['workerId']) {
                        $worker['workedHours'] += $worked_hour['hours'];
                    }
                }
            }

            // Filter de werknemers die effectief gewerkt hebben
            $filteredWorkers = array_filter($workers, function ($worker) {
                return $worker['workedHours'] > 0;
            });

            // Zorg ervoor dat de array indices opnieuw worden geïndexeerd
            $filteredWorkers = array_values($filteredWorkers);

            return response()->json($filteredWorkers, 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    // Functie om alle gewerkte uren van een werknemer op te halen voor een specifiek project
    public function getProjectWorkerWorkedHours($projectId, $workerId)
    {
        try {
            // Query met een JOIN tussen worked_hours en workers
            $workedHours = LaravelWorkedHours::join('workers', 'worked_hours.workerId', '=', 'workers.workerId')
                ->where('worked_hours.projectId', $projectId)
                ->where('worked_hours.workerId', $workerId)
                ->select('worked_hours.*', 'workers.name', 'workers.surname')
                ->get();

            return response()->json($workedHours, 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    // Functie om gewerkte uren te verwijderen
    public function deleteWorkedHours(Request $request, $whId)
    {
        // Controleer of het wachtwoord correct is
        $pwd = $request->query('pwd');
        if ($pwd !== 'admin') {
            return response()->json(["error" => "You are not authorized to delete"], 401);
        }

        try {
            // Zoek het record in de database
            $workedHours = LaravelWorkedHours::find($whId);

            if (!$workedHours) {
                return response()->json(["message" => "Project not found"], 404);
            }

            // Verwijder het record
            $workedHours->delete();
            return response()->json(["message" => "Project deleted successfully"], 200);

        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    // Functie om gewerkte uren toe te voegen aan een project
    public function createWorkedHours(Request $request, $projectId)
    {
        Log::info("Creating worked hours for project with id: $projectId");
        Log::info($request->all());
        // Valideer de invoer
        $request->validate([
            'fullName' => 'required|string',
            'hours' => 'required|numeric',
            'date' => 'required|date',
        ]);

        // Haal de gegevens uit het verzoek
        $fullName = $request->input('fullName');
        $hours = $request->input('hours');
        $comment = $request->input('comment', null); // Optioneel
        $date = $request->input('date');

        // Splits de volledige naam in voornaam en achternaam
        $nameParts = explode(' ', $fullName, 2);

        // Controleer of de volledige naam zowel een voornaam als een achternaam bevat
        if (count($nameParts) < 2) {
            return response()->json(["error" => "Full name must include first and last name"], 400);
        }

        [$firstName, $lastName] = $nameParts;
        Log::info("First name: $firstName, Last name: $lastName");

        try {
            // Zoek de werknemer op basis van voornaam en achternaam
            $worker = LaravelWorkers::where('name', $firstName)
                ->where('surname', $lastName)
                ->first();

            // Als de werknemer niet bestaat, retourneer een foutmelding
            if (!$worker) {
                return response()->json(["error" => "Worker not found"], 404);
            }

            Log::info("Worker found: " . $worker->workerId);
            Log::info("Hours: $hours, Comment: $comment, Date: $date");

            // Maak een nieuwe worked_hours entry aan
            LaravelWorkedHours::create([
                'projectId' => $projectId,
                'workerId' => $worker->workerId,
                'hours' => $hours,
                'comment' => $comment,
                'date' => $date,
            ]);

            Log::info("Hours booked successfully");

            // Retourneer een succesbericht
            return response()->json(["message" => "Hours booked successfully"], 201);
        } catch (\Exception $e) {
            // Retourneer een foutmelding als er een uitzondering optreedt
            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
