<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Video;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pn = 20;
        $vendors = Vendor::latest()->paginate($pn);

        return view('vendors.index', compact('vendors'))
            ->with('i', (request()->input('page', 1) - 1) * $pn);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vendors.create');
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

        Vendor::create($request->all());

        return redirect()->route('vendors.index')
                        ->with('success', 'Vendor stored successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        $vendor_id = $vendor->id;
        $videos = Video::where('vendor_id', '=', $vendor_id)
                       ->get();

        return view('vendors.show', compact('vendor'))
               ->with(compact('videos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
               'name' => 'required',
        ]);

        $vendor->update($request->all());

        return redirect()->route('vendors.index')
                        ->with('success', 'Vendor updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()->route('vendors.index')
                        ->with('success', 'Vendor deleted successfully');
    }
}
