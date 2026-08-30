<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityController extends Controller
{
    public function index()
    {
        // If the logs table or user_id column doesn't exist, return an empty paginator
        if (! Schema::hasTable('logs') || ! Schema::hasColumn('logs', 'user_id')) {
            $empty = [];
            $paginator = new LengthAwarePaginator($empty, 0, 20, 1, [
                'path' => request()->url(),
            ]);

            return view('activities.index', ['activities' => $paginator]);
        }

        $activities = Log::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('activities.index', compact('activities'));
    }
}
