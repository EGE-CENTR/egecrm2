<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client\ClientTest;
use App\Http\Resources\Test\ClientTest as ClientTestResource;
use User;

class ClientTestsController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientTest::query();

        if (isset($request->client_id)) {
            $query->where('client_id', $request->client_id);
        }

        if (isset($request->test_id)) {
            $query->where('test_id', $request->test_id);
        }

        if (isset($request->started)) {
            $query->whereNotNull('started_at');
        }

        if (isset($request->includeTest)) {
            $query->with(['test']);
        }

        // return $query->get();
        return ClientTestResource::collection($query->get());
    }

    public function store(Request $request)
    {
        return ClientTest::create($request->all());
    }

    public function update($id, Request $request)
    {
        $model = ClientTest::where([
            ['client_id', User::id()],
            ['test_id', $id]
        ])->first();
        $model->started_at = $request->started_at;
        $model->save();
        return $model;
    }

    // $id – test id
    public function show($id, Request $request)
    {
        $query = ClientTest::where('test_id', $id);

        if (isset($request->client_id)) {
            $query->where('client_id', $request->client_id);
        }

        if (isset($request->started)) {
            $query->whereNotNull('started_at');
        }

        return $query->first();
    }

    public function destroy($id)
    {
        $client_test = ClientTest::find($id);
        $client_test->answers()->delete();
        $client_test->delete();
    }
}
