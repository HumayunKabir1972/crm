<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExportController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/login', function () {
    return redirect()->route('filament.futureLinkIT.auth.login');
})->name('login');

Route::middleware(['auth'])->group(function () {

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/customers', [ReportController::class, 'customers'])->name('reports.customers');

    // Exports
    Route::get('/export/customers', [ExportController::class, 'customers'])->name('export.customers');
    Route::get('/export/leads', [ExportController::class, 'leads'])->name('export.leads');
    Route::get('/export/deals', [ExportController::class, 'deals'])->name('export.deals');
    Route::get('/export/invoices', [ExportController::class, 'invoices'])->name('export.invoices');

    // Settings
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings');
});