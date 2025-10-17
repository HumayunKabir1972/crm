<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index(Request $request)
    {
        $query = Deal::with(['customer', 'assignedUser']);

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('stage')) {
            $query->where('stage', $request->input('stage'));
        }

        return response()->json([
            'data' => $query->paginate($request->input('per_page', 15))
        ]);
    }

    public function show(Deal $deal)
    {
        return response()->json([
            'data' => $deal->load(['customer', 'assignedUser', 'activities'])
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'stage' => 'required|in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost',
            'status' => 'required|in:open,won,lost,abandoned',
            'probability' => 'integer|min:0|max:100',
        ]);

        $deal = Deal::create(array_merge($validated, [
            'created_by' => auth()->id()
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Deal created successfully',
            'data' => $deal
        ], 201);
    }

    public function update(Request $request, Deal $deal)
    {
        $validated = $request->validate([
            'stage' => 'in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost',
            'status' => 'in:open,won,lost,abandoned',
            'amount' => 'numeric|min:0',
            'probability' => 'integer|min:0|max:100',
        ]);

        $deal->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Deal updated successfully',
            'data' => $deal
        ]);
    }

    public function updateStage(Request $request, Deal $deal)
    {
        $request->validate([
            'stage' => 'required|in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost'
        ]);

        $deal->update(['stage' => $request->stage]);

        return response()->json([
            'success' => true,
            'message' => 'Deal stage updated successfully',
            'data' => $deal
        ]);
    }
}