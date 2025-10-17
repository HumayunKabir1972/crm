@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="crm-dashboard">
    <aside class="crm-sidebar">
        @include('components.sidebar')
    </aside>

    <main class="crm-main">
        @include('components.header')

        <div class="crm-dashboard__content">
            <div class="crm-dashboard__title">
                <h1>Reports</h1>
                <p>View your CRM reports here.</p>
            </div>

            <div class="crm-card">
                <div class="crm-card__header">
                    <h3 class="crm-card__title">Sales Report</h3>
                </div>
                <div class="crm-card__body">
                    <p>This is a placeholder for sales reports.</p>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection