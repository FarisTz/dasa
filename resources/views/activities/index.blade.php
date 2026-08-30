@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Activities</h1>
    </div>
    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <ul class="list-group">
                    @foreach($activities as $act)
                        <li class="list-group-item">
                            <div>{{ $act->action ?? $act->description ?? 'Activity' }}</div>
                            <small class="text-muted">{{ $act->created_at }}</small>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-3">{{ $activities->links() }}</div>
            </div>
        </div>
    </div>
</section>
@endsection
