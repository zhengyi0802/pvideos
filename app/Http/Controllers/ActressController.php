<?php

namespace App\Http\Controllers;

use App\Models\Actress;
use App\Models\Video;
use Illuminate\Http\Request;

class ActressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pn = 20;
        $actresses = Actress::latest()->paginate($pn);

        return view('actresses.index', compact('actresses'))
            ->with('i', (request()->input('page', 1) - 1) * $pn);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('actresses.create');
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

        $name = $request->input('name');
        $actress = Actress::where('name', 'LIKE', $name)->first();
        if ($actress != null) {
            return $this->show($actress);
        }

        Actress::create($request->all());

        return redirect()->route('actresses.index')
                        ->with('success', 'Actress stored successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Actress  $actress
     * @return \Illuminate\Http\Response
     */
    public function show(Actress $actress)
    {
        $videosdata = Video::get();

        $videos = array();
        foreach ($videosdata as $videodata) {
            $acts = json_decode($videodata->actresses, true);
            if (gettype($acts) == 'array') {
                foreach ($acts as $act) {
                     if ($act == $actress->id) {
                         array_push($videos, $videodata);
                     }
                }
            } else if (gettype($acts) == 'string' ) {
                if ($acts == $actress->id) {
                     array_push($videos, $videodata);
                }
            }
        }
        return view('actresses.show', compact('actress'))
               ->with(compact('videos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Actress  $actress
     * @return \Illuminate\Http\Response
     */
    public function edit(Actress $actress)
    {
        return view('actresses.edit', compact('actress'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Actress  $actress
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Actress $actress)
    {
        $request->validate([
               'name' => 'required',
        ]);

        $actress->update($request->all());

        return redirect()->route('actresses.index')
                        ->with('success', 'Actress updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Actress  $actress
     * @return \Illuminate\Http\Response
     */
    public function destroy(Actress $actress)
    {
        $actress->delete();

        return redirect()->route('actresses.index')
                        ->with('success', 'Actress deleted successfully');
    }

    public function search(Request $request)
    {
        $name = $request->input('name');
        $actress = Actress::where('name', 'LIKE', $name.'%')->first();

        if ($actress == null) {
            return $this->index();
        } else {
            return $this->show($actress);
        }

    }
}
