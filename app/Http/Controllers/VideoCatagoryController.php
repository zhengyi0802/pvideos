<?php

namespace App\Http\Controllers;

use App\Models\VideoCatagory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class VideoCatagoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pn = 20;
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        $catagories = DB::table('video_catagories as a')
                         ->leftJoin('users', 'user_id', 'users.id')
                         ->leftJoin('video_catagories as b', 'a.parent_id', 'b.id')
                         ->select('a.*', 'b.name as parent', 'users.name as user')
                         ->paginate($pn);

        return view('catagories.index', compact('catagories'))
            ->with(compact('user'))
            ->with('i', (request()->input('page', 1) - 1) * $pn);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $catagories = VideoCatagory::where('status', true)->get();

        return view('catagories.create',compact('catagories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = auth()->user()->id;
        $request->merge(['user_id' => $user_id]);
        VideoCatagory::create($request->all());

       return redirect()->route('catagories.index')
                        ->with('success', 'Catagory stored successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VideoCatagory  $videoCatagory
     * @return \Illuminate\Http\Response
     */
    public function show(VideoCatagory $catagory)
    {
        $user_id = auth()->user()->id;
        //$user = User::find($user_id);
        $id = $catagory->id;
        $catagory = DB::table('video_catagories as a')
                      ->leftJoin('users', 'user_id', 'users.id')
                      ->leftJoin('video_catagories as b', 'a.parent_id', 'b.id')
                      ->select('a.*', 'users.name as user', 'b.name as parent')
                      ->where('a.user_id', $user_id)
                      ->where('a.id', $id)->first();

        return view('catagories.show', compact('catagory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VideoCatagory  $videoCatagory
     * @return \Illuminate\Http\Response
     */
    public function edit(VideoCatagory $catagory)
    {
        $parents = VideoCatagory::where('status', true)
                                ->where('id', '<>', $catagory->id)
                                ->get();

        return view('catagories.edit', compact('catagory'))
               ->with(compact('parents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VideoCatagory  $videoCatagory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VideoCatagory $catagory)
    {
        //
       return redirect()->route('catagories.index')
                        ->with('success', 'Catagory updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VideoCatagory  $videoCatagory
     * @return \Illuminate\Http\Response
     */
    public function destroy(VideoCatagory $catagory)
    {
        $catagory->delete();

        return redirect()->route('catagories.index')
                        ->with('success', 'Catagory deleted successfully');
    }
}
