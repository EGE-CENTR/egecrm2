<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Client\Client, Request as ClientRequest, Phone};
use App\Http\Resources\Request\{RequestResource, RequestCollection};
use App\Http\Resources\AlgoliaResult;
use App\Http\Requests\Request\StoreOrUpdateRequest;

class RequestsController extends Controller
{
    protected $filters = [
        'multiple' => ['id', 'grade_id', 'responsible_admin_id', 'created_email_id'],
        'timestamp' => ['created_at_timestamp'],
    ];

    public function index(Request $request)
    {
        if (isset($request->client_id)) {
            return RequestCollection::collection(
                $this->showAll(Client::find($request->client_id)->requests())
            );
        }

        $filters = [];
        foreach(['status'] as $field) {
            if ($request->input($field)) {
                $values = explode(',', $request->input($field));
                $filters[] = implode(' OR ', array_map(function ($value) use ($field) {
                    return $field . ':' . $value;
                }, $values));
            }
        }

        $query = ClientRequest::search()->with([
            'facets' => ['*'],
            'filters' => implode(' AND ', $filters)
        ]);

        $this->filter($request, $query);

        $result = new AlgoliaResult(
            $query->paginateRaw($request->paginate ?: SHOW_ALL)
        );
        $result = jsonRedecode($result);
        $ids = collect($result->data)->pluck('id');

        $items = ClientRequest::query()
            ->with(['responsibleAdmin', 'createdEmail'])
            ->whereIn('id', $ids)
            ->orderBy('id', 'desc')
            ->get();
        $result->data = RequestCollection::collection($items);
        if (is_array($result->facets) && empty($result->facets)) {
            $result->facets = (object) [];
        }
        return response()->json($result);
    }

    public function store(StoreOrUpdateRequest $request)
    {
        $new_model = ClientRequest::create($request->input());
        $new_model->phones()->createMany($request->phones);
        $new_model->get_back_at = $this->getBackAt($request);

        // если заявка падает с сайта
        // и среди на текущий момент "новых" заявок есть телефон только что упавшей заявки,
        // то только что упавшая заявка должна иметь статус "выполненные"
        if (isset($request->google_id) && isset($request->phones)) {
            $newRequestIds = ClientRequest::where('status', 'new')->where('id', '<>', $new_model->id)->pluck('id')->all();
            if (
                Phone::query()
                    ->where('entity_type', ClientRequest::class)
                    ->whereIn('entity_id', $newRequestIds)
                    ->where('phone', $new_model->phones->first()->phone_clean)
                    ->exists()
            ) {
                $new_model->status = 'finished';
                $new_model->save();
            }
        }

        return response(new RequestCollection($new_model), 201);
    }

    public function show($id, Request $request)
    {
        // потому что происходит небольшой разлом логики:
        // на странице просмотра заявки отображается модель как в списке
        if (isset($request->resource)) {
            $item = ClientRequest::find($id);
            return RequestCollection::collection(
                ClientRequest::query()
                    ->whereIn('id', array_merge([$id], $item->getRelativeIds()))
                    ->orderBy('created_at', 'desc')
                    ->get()
            );
        } else {
            return new RequestResource(
                ClientRequest::find($id)
            );
        }
    }

    public function update(StoreOrUpdateRequest $request, $id)
    {
        $model = ClientRequest::find($id);
        $request->merge([
            'get_back_at' => $this->getBackAt($request)
        ]);
        $model->update($request->all());

        $model->phones()->delete();
        $model->phones()->createMany($request->phones);

        return new RequestCollection($model->fresh());
    }

    public function destroy($id)
    {
        // TODO: может как-нибудь поумнее можно вынести?
        $item = ClientRequest::find($id);
        $item->phones->each(function ($phone) {
            $phone->delete();
        });
        $item->delete();
        return new RequestCollection($item);
    }

    private function getBackAt(Request $request)
    {
        if (isset($request->get_back_date) && isset($request->get_back_time)) {
            return $request->get_back_date . ' ' . $request->get_back_time;
        }
        return null;
    }
}
