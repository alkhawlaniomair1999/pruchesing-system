<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use Illuminate\Http\Request;

class AccountsController extends Controller
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
        $data = $request->account_name;
        $d_branch = $request->branch;
        accounts::create([
            'account' => $data,
            'debt'=>0,
            'credit'=>0,
            'balance'=>0,
            'branch_id'=>$d_branch,

        ]);
        return redirect()->back();
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(accounts $accounts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(accounts $accounts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $b1['account']=$request->newName;
        accounts::where('id',$request->id)->update($b1);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        accounts::where('id', $id)->delete();
        return redirect()->back();
    }
}
