<?php

namespace App\Http\Controllers;

use App\Models\inventories;
use App\Models\items;
use App\Models\Branch;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = items::all();
        $branches = Branch::all();
        $inventory = inventories::all();
        return view('inventory', compact('items', 'branches', 'inventory'));
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
        $inventory = new inventories();
        $inventory->item_id = $request->item_id;
        $inventory->branch_id = $request->branch_id;
        $inventory->month = $request->month;
        $inventory->year = $request->year;
        $inventory->first_inventory = $request->first_inventory;
        $inventory->last_inventory = $request->last_inventory;
        $inventory->inventory_out = $request->inventory_out;
        $inventory->save();
        return redirect()->back()->with('success', 'Inventory created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $inventory = inventories::findOrFail($request->id);
        $inventory->item_id = $request->item_id;
        $inventory->branch_id = $request->branch_id;
        $inventory->month = $request->month;
        $inventory->year = $request->year;
        $inventory->first_inventory = $request->first_inventory;
        $inventory->last_inventory = $request->last_inventory;
        $inventory->inventory_out = $request->inventory_out;
        $inventory->save();
        return redirect()->back()->with('success', 'Inventory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $inventory = inventories::findOrFail($id);
        $inventory->delete();
        return redirect()->back()->with('success', 'Inventory deleted successfully.');
    }
}
