<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonsterRequest;
use App\Laravue\Models\Monster;
use Illuminate\Http\Request;

class MonsterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // limiting
        $limit = $request->query('limit');
        if ($limit && $limit == -1) {
            return Monster::all();
        }
        // paginiting and returning final results
        return Monster::paginate($limit ?? 10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MonsterRequest $request)
    {
        $data = $request->validated();
        return Monster::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Laravue\Models\Monster  $monster
     * @return \Illuminate\Http\Response
     */
    public function show(Monster $monster)
    {
        return $monster;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Laravue\Models\Monster  $monster
     * @return \Illuminate\Http\Response
     */
    public function update(MonsterRequest $request, Monster $monster)
    {
        $data = $request->validated();
        $status = $monster->update($data);
        if ($status) {
            return $monster;
        } else {
            return 'Error';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Laravue\Models\Monster  $monster
     * @return \Illuminate\Http\Response
     */
    public function destroy(Monster $monster)
    {
        try {
            $monster->delete();
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
