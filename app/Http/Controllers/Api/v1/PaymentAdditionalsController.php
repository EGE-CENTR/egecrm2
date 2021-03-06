<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment\PaymentAdditional;
use App\Http\Resources\PaymentAdditional\PaymentAdditionalResource;
use App\Http\Requests\PaymentAdditional\StoreOrUpdateRequest;

class PaymentAdditionalsController extends Controller
{
    protected $filters = [
        'multiple' => ['year'],
        'equals' => ['entity_id'],
        'entity' => ['entity_type'],
    ];

    public function index(Request $request)
    {
        $query = PaymentAdditional::query();
        $this->filter($request, $query);
        return $this->showAll($query);
    }

    public function update(StoreOrUpdateRequest $request, $id)
    {
        $item = PaymentAdditional::find($id);
        $item->update($request->input());
        return $item;
    }

    public function store(StoreOrUpdateRequest $request)
    {
        return PaymentAdditional::create($request->all());
    }

    public function show($id)
    {
        return new PaymentAdditionalResource(
            PaymentAdditional::find($id)
        );
    }

    public function destroy($id)
    {
        PaymentAdditional::destroy($id);
    }
}
