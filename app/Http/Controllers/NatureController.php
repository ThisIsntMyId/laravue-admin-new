<?php

namespace App\Http\Controllers;

use App\Laravue\Models\Nature;
use Illuminate\Http\Request;

class NatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->query('ids')) {
            return Nature::whereIn('id', json_decode('['. $request->query('ids') .']'))->get();
        }
        
        // limiting
        $limit = $request->query('limit');
        if ($limit && $limit == -1) {
            return Nature::all();
        }
        // paginating and returning final results
        return Nature::paginate($limit ?? 10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Laravue\Models\Nature  $nature
     * @return \Illuminate\Http\Response
     */
    public function show(Nature $nature)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Laravue\Models\Nature  $nature
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nature $nature)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Laravue\Models\Nature  $nature
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nature $nature)
    {
        //
    }
}
