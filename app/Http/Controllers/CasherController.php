<?php

namespace App\Http\Controllers;

use App\Models\cashers;
use Illuminate\Http\Request;

class CasherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cash['casher'] = $request->casher;
        $cash['branch_id'] = $request->branch;

        cashers::create($cash);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show( $casher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $casher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $casher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $casher)
    {
        //
    }
}
