<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;

class TrainingHistoryExportController extends Controller
{
    public function __invoke(Request $request, Participant $participant)
    {
        $histories = $participant->histories()
            ->with('training')
            ->latest()
            ->get();

        return view('public.training-history-export', [
            'participant' => $participant,
            'histories' => $histories,
            'printedAt' => now(),
        ]);
    }
}