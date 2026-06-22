@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-0">Applications</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb
                    

@endsection

@push('styles')
<style>
    .empty-state {
        padding: 40px 0;
    }
    .empty-state-icon {
        font-size: 60px;
        width: 120px;
        height: 120px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f8f9fa;
        margin-bottom: 20px;
    }
    .table td {
        vertical-align: middle;
    }
    .card-statistic-1 {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }
    .card-statistic-1:hover {
        transform: translateY(-5px);
    }
    .card-statistic-1 .card-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        font-size: 24px;
    }
    .card-statistic-1 .card-wrap {
        padding-left: 10px;
    }
    .card-statistic-1 .card-header {
        padding: 0;
        background: none;
        border: none;
    }
    .card-statistic-1 .card-header h4 {
        font-size: 13px;
        font-weight: 600;
        color: #6c757d;
        margin: 0;
    }
    .card-statistic-1 .card-body {
        font-size: 20px;
        font-weight: 700;
        padding: 5px 0;
        color: #2d2d2d;
    }
    .badge-lg {
        font-size: 13px;
        padding: 6px 12px;
    }
    .pagination {
        margin-bottom: 0;
    }
    .dropdown-menu {
        max-height: 400px;
        overflow-y: auto;
    }
</style>
@endpush

                           