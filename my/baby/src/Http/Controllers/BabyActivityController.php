<?php

namespace My\Baby\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use My\Baby\Enums\BabyActivityTypeEnum;
use My\Baby\Http\Requests\UpdateBabyActivityRequest;
use My\Baby\Models\BabyActivity;

class BabyActivityController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = Carbon::parse($request->query('date'));

        $activities = BabyActivity::whereDate('date', $date->format('Y-m-d'))
            ->orderByDesc('date')
            ->get();

        return Inertia::render('BabyActivities/Index', [
            'activities' => $activities,
            'selectedDate' => $date->format('Y-m-d'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('BabyActivities/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'date' => 'required',
        ]);

        $type = $request->type;
        $data = null;

        if ($type === BabyActivityTypeEnum::FEEDING->value) {
            $data = [
                'type' => $request->data_type,
            ];

            if ($request->data_type === 'breast') {
                $data['side'] = $request->data_side;
            } elseif ($request->data_type === 'breast') {
                $data['amount'] = $request->data_amount;
            }
        } elseif ($type === BabyActivityTypeEnum::DIAPER_CHANGE->value) {
            $data = [
                'type' => $request->data_type,
            ];
        }

        BabyActivity::create([
            'type' => $type,
            'date' => $request->date,
            'data' => $data,
        ]);

        return redirect()->route('baby_activities.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(BabyActivity $model)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BabyActivity $model)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBabyActivityRequest $request, BabyActivity $model)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BabyActivity $model): RedirectResponse
    {
        $model->delete();

        return redirect()->route('baby_activities.index');
    }
}
