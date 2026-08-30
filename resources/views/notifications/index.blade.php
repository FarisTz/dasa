@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Notifications</h1>
    </div>
    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('notifications.readAll') }}">
                    @csrf
                    <button class="btn btn-sm btn-secondary mb-3">Mark all as read</button>
                </form>

                <ul class="list-group">
                    @foreach($notifications as $note)
                        <li class="list-group-item {{ $note->read ? '' : 'font-weight-bold' }}">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div>{{ $note->title ?? 'Notification' }}</div>
                                    <small class="text-muted">{{ $note->message ?? '' }}</small>
                                </div>
                                <div>
                                    <form method="POST" action="{{ route('notifications.read', $note->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-link">Mark read</button>
                                    </form>
                                    <div class="text-muted small">{{ $note->created_at }}</div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-3">{{ $notifications->links() }}</div>
            </div>
        </div>
    </div>
</section>
@endsection
