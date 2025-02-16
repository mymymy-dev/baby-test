<?php

namespace App\Http\Controllers;

use App\Models\Contraction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContractionController extends Controller
{
    public function index(Request $request)
    {
        // Načítanie sortOrder z query parametra (predvolený je 'desc')
        $sortOrder = $request->query('sortOrder', 'desc');

        $contractions = Contraction::orderBy('start_time', $sortOrder)->get()->map(fn ($c) => [
            'id' => $c->id,
            'date' => $c->start_time->format('d.m.Y'),
            'start_time' => $c->start_time->format('H:i'),
            'end_time' => $c->end_time?->format('H:i'),
            'duration' => $c->duration,
            'since_last' => $c->since_last,
        ]);

        $list = [];

        foreach ($contractions as $contraction) {
            $list[$contraction['date']][$contraction['start_time']] = $contraction;
        }

        krsort($list);

        foreach ($list as $contractions) {
            krsort($contractions);
        }

        return Inertia::render('Contractions/Index', [
            'contractions' => $list ?? [],
            'sortOrder' => $sortOrder,
        ])->withViewData(['debug' => $list]);
    }

    public function create()
    {
        return Inertia::render('Contractions/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        Contraction::create([
            'start_time' => Carbon::parse($validated['start_time'])->setTimezone('Europe/Bratislava'),
            'end_time' => $validated['end_time'] ? Carbon::parse($validated['end_time'])->setTimezone('Europe/Bratislava') : null,
        ]);

        return redirect()->route('contractions.index')->with('success', 'Kontrakcia bola uložená.');
    }

    // Uloženie začiatku kontrakcie
    public function start()
    {
        Contraction::create(['start_time' => now(), 'end_time' => now()->addMinute()]);
        return redirect()->route('contractions.index');
    }

    // Uloženie konca kontrakcie
    public function end()
    {
        $contraction = Contraction::latest()->first();
        if ($contraction) {
            $contraction->update(['end_time' => now()]);
        }
        return redirect()->route('contractions.index');
    }

    // Zmazanie kontrakcie
    public function destroy(Contraction $contraction)
    {
        $contraction->delete();
        return redirect()->route('contractions.index');
    }
}

