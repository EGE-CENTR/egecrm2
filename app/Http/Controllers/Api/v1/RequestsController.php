<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Client\Client, Request as ClientRequest};
use App\Http\Resources\Request\{RequestResource, RequestCollection};

class RequestsController extends Controller
{
    protected $filters = [
        'multiple' => ['status', 'grade_id', 'responsible_admin_id', 'created_email_id'],
        'interval' => ['created_at'],
    ];

    public function index(Request $request)
    {
        if (isset($request->client_id)) {
            return RequestCollection::collection(
                $this->showAll(Client::find($request->client_id)->requests())
            );
        }
        $query = ClientRequest::with(['responsibleAdmin', 'createdEmail'])->orderBy('id', 'desc');
        $this->filter($request, $query);
        return RequestCollection::collection($this->showBy($request, $query));
    }

    public function store(Request $request)
    {
        $new_model = ClientRequest::create($request->input());
        // dd($request->phones);
        $new_model->phones()->createMany($request->phones);
        return response(new RequestResource($new_model), 201);
    }

    public function show($id)
    {
        return new RequestResource(ClientRequest::find($id));
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
        ClientRequest::find($id)->delete();
    }
}
