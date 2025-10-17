@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="crm-dashboard">
    <aside class="crm-sidebar">
        @include('components.sidebar')
    </aside>

    <main class="crm-main">
        @include('components.header')

        <div class="crm-dashboard__content">
            <div class="crm-dashboard__title">
                <h1>Settings</h1>
                <p>Manage your application settings here.</p>
            </div>

            <div class="crm-card">
                <div class="crm-card__header">
                    <h3 class="crm-card__title">General Settings</h3>
                </div>
                <div class="crm-card__body">
                    <p>This is a placeholder for general settings.</p>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection