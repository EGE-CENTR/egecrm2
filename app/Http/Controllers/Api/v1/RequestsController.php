<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Request as ClientRequest;
use App\Http\Resources\Request\{Resource, Collection};

class RequestsController extends Controller
{
    public function index()
    {
        $requests = ClientRequest::with('responsibleAdmin')->orderBy('id', 'desc')->paginate(30);
        return resourceCollection($requests, Collection::class);
    }

    public function store(Request $request)
    {
        $new_model = ClientRequest::create($request->input());
        $new_model->phones()->createMany($request->phones);
        return response($new_model, 201);
    }

    public function show($id)
    {
        return new Resource(ClientRequest::find($id));
    }

    public function update(Request $request, $id)
    {
        $model = ClientRequest::find($id);
        $model->update($request->input());

        $model->phones()->delete();
        $model->phones()->createMany($request->phones);

        return response()->json(null, 204);
    }

    public function destroy($id)
    {
        //
    }
}
