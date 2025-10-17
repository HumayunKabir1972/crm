<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class CustomerApiController extends Controller
{
    public function archive(Request $request, Customer $customer)
    {
        try {
            $customer->status = 'archived';
            $customer->save();

            return Response::json(['message' => 'Customer archived successfully.'], 200);
        } catch (\Exception $e) {
            return Response::json(['message' => 'Failed to archive customer.', 'error' => $e->getMessage()], 500);
        }
    }
}
