<?php
// routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\DealController;

Route::middleware('auth:sanctum')->group(function () {
    // Customer API
    Route::apiResource('customers', CustomerController::class);
    Route::patch('customers/{customer}/archive', [CustomerApiController::class, 'archive']);

    // Lead API
    Route::apiResource('leads', LeadController::class);
    Route::patch('leads/{lead}/convert', [LeadController::class, 'convert']);

    // Deal API
    Route::apiResource('deals', DealController::class);
    Route::patch('deals/{deal}/stage', [DealController::class, 'updateStage']);

    // Search
    Route::get('search', function (Request $request) {
        $query = $request->input('q');
        $results = [];

        // Search customers
        $customers = \App\Models\Customer::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        foreach ($customers as $customer) {
            $results[] = [
                'title' => $customer->full_name,
                'type' => 'Customer',
                'url' => route('filament.resources.customers.edit', $customer)
            ];
        }

        // Search leads
        $leads = \App\Models\Lead::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('company', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        foreach ($leads as $lead) {
            $results[] = [
                'title' => $lead->full_name,
                'type' => 'Lead',
                'url' => route('filament.resources.leads.edit', $lead)
            ];
        }

        return response()->json($results);
    });
});