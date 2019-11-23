<?php

namespace App\Http\Controllers;

use App\Laravue\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private function validateData(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'description' => 'nullable',
                'price' => 'required | numeric',
                'category' => 'exists:categories,id',
            ]
        );
        if ($validator->fails()) {
            return ['errors' => $validator->messages(), 'data' => ''];
        } else {
            return ['errors' => '', 'data' => $request->all()];
        }
    }

    private function getFiltersObject(Request $request)
    {
        if ($request->query('filters')) {
            $filters = $request->query('filters');
            $chunks = array_chunk(preg_split('/(::|:)/', $filters), 2);
            $result = array_combine(array_column($chunks, 0), array_column($chunks, 1));
            if(isset($result['price'])) {
                $result['price'] = explode(',', $result['price']);
            }
            if(isset($result['category'])) {
                $result['category'] = explode(',', $result['category']);
            }
            return collect($result);
        } else {
            return null;
        }
    }

    public function index(Request $request)
    {
        $cardsQuery = new Card;
        // filtering
        $filter = $request->query('filters');
        if ($filter) {
            $filterConditions = $this->getFiltersObject($request);
            $filterConditions->each(function ($item, $key) use (& $cardsQuery) {
                $methodName = 'filterBy' . ucwords($key);
                $cardsQuery = $cardsQuery->$methodName($item);
            });
        }
        // sorting
        $sort_field = $request->query('sort');
        $sort_mode = $request->query('sort-order');
        if ($sort_field) {
            $cardsQuery = $cardsQuery->orderBy($sort_field, strtolower($sort_mode ?? 'asc'));
        }
        // limiting
        $limit = $request->query('limit');
        if ($limit && $limit == -1) {
            return card::all();
        }
        // paginiting and returning final results
        return $cardsQuery->paginate($limit ?? 10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if ($data['errors']) {
            return response()->json($data, 403);
        } else {
            return Card::create($data['data']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Laravue\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        return $card;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Laravue\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {
        $data = $this->validateData($request);
        if ($data['errors']) {
            return response()->json($data, 403);
        } else {
            $status = $card->update($data['data']);
            if ($status) {
                return $card;
            } else {
                return 'Error';
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Laravue\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        try {
            $card->delete();
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    /**
     * Remove the multiple resource from storage.
     *
     * @param  \App\Laravue\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function deletemany(Request $request)
    {
        $ids = $request->input('ids');
        Card::destroy($ids);
        dd($ids);
        return response()->json(null, 204);
    }
}
