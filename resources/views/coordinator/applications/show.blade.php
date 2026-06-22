@extends('layouts.app')

@section('content')

@endsection

@push('styles')
<style>
    .badge-lg {
        font-size: 14px;
        padding: 8px 16px;
    }
    .timeline {
        position: relative;
        padding: 20px 0;
    }
    .timeline-item {
        position: relative;
        padding-left: 60px;
        margin-bottom: 30px;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-marker {
        position: absolute;
        left: 0;
        top: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .timeline-marker.bg-success {
        background: #28a745;
    }
    .timeline-marker.bg-primary {
        background: #007bff;
    }
    .timeline-marker.bg-warning {
        background: #ffc107;
        color: #212529;
    }
    .timeline-marker.bg-secondary {
        background: #6c757d;
    }
    .timeline-marker.bg-danger {
        background: #dc3545;
    }
    .timeline-marker.bg-info {
        background: #17a2b8;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 19px;
        top: 40px;
        width: 2px;
        height: calc(100% + 10px);
        background: #e9ecef;
    }
    .timeline-item:last-child::before {
        display: none;
    }
    .timeline-content {
        padding: 15px 20px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 3px solid #007bff;
    }
    .timeline-title {
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 16px;
    }
    .timeline-text {
        margin-bottom: 0;
        color: #6c757d;
        font-size: 14px;
    }
    .nav-tabs .nav-link {
        font-weight: 600;
        color: #495057;
    }
    .nav-tabs .nav-link.active {
        color: #007bff;
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
    @media print {
        .section-header-breadcrumb,
        .card-header-action,
        .btn,
        .no-print {
            display: none !important;
        }
        .card {
            border: 1px solid #ddd !important;
            break-inside: avoid;
        }
        .timeline-item::before {
            display: none !important;
        }
    }
</style>
@endpush
