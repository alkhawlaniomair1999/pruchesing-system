<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\items;
use Illuminate\Http\Request;

class ItemsController extends Controller
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
        $data = $request->category_name;
        items::create([
            'item' => $data,
        ]);
        return redirect()->back();
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(items $accounts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(items $accounts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, items $accounts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(items $accounts)
    {
        //
    }
}
