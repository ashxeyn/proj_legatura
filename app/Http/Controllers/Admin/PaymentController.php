<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\MilestonePayment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int)$request->input('per_page',15);
        $q = MilestonePayment::query();
        if ($status = $request->input('status')) {
            $q->where('payment_status',$status);
        }
        return response()->json($q->orderBy('payment_id','desc')->paginate($perPage));
    }

    public function show($id)
    {
        $p = MilestonePayment::find($id);
        if (!$p) return response()->json(['error'=>'Not found'],404);
        return response()->json($p);
    }

    public function store(StorePaymentRequest $request)
    {
        $p = MilestonePayment::create($request->validated());
        return response()->json($p,201);
    }

    public function update(Request $request, $id)
    {
        $p = MilestonePayment::find($id);
        if (!$p) return response()->json(['error'=>'Not found'],404);
        $p->update($request->only(['payment_status','amount','transaction_date']));
        return response()->json($p);
    }

    public function destroy($id)
    {
        $p = MilestonePayment::find($id);
        if (!$p) return response()->json(['error'=>'Not found'],404);
        $p->delete();
        return response()->json(['deleted'=>true]);
    }
}
