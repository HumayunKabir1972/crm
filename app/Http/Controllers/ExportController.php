<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function customers()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }

    public function leads()
    {
        return Excel::download(new LeadsExport, 'leads.xlsx');
    }

    public function deals()
    {
        return Excel::download(new DealsExport, 'deals.xlsx');
    }

    public function invoices()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }
}
