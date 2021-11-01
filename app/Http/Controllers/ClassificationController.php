<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\Video;
use Illuminate\Http\Request;

class ClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pn = 20;
        $classifications = Classification::latest()->paginate($pn);

        return view('classifications.index', compact('classifications'))
            ->with('i', (request()->input('page', 1) - 1) * $pn);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('classifications.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
               'name' => 'required',
        ]);

        Classification::create($request->all());

        return redirect()->route('classifications.index')
                        ->with('success', 'Classification stored successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Classification  $classification
     * @return \Illuminate\Http\Response
     */
    public function show(Classification $classification)
    {
        $videodatas = Video::get();
        $videos = array();
        foreach ($videodatas as $videodata) {
           $arr = json_decode($videodata->classifications, true);
           if (gettype($arr) == 'array') {
               foreach ($arr as $id) {
                   if ($id == $classification->id) {
                       array_push($videos, $videodata);
                   }
               }
           } else if ((gettype($arr) == 'string') && ($arr == $classification->id)) {
               array_push($videos, $videodata);
           }
        }
        return view('classifications.show', compact('classification'))
               ->with(compact('videos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Classification  $classification
     * @return \Illuminate\Http\Response
     */
    public function edit(Classification $classification)
    {
        return view('classifications.edit', compact('classification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Classification  $classification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Classification $classification)
    {
        $request->validate([
               'name' => 'required',
        ]);

        $classification->update($request->all());

        return redirect()->route('classifications.index')
                        ->with('success', 'Classification updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Classification  $classification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classification $classification)
    {
        $classification->delete();

        return redirect()->route('classifications.index')
                        ->with('success', 'Classification deleted successfully');
    }
}
