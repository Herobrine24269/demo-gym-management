<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($action = $request->get('action')) {
            $query->where('action', $action);
        }
        if ($userId = $request->get('user_id')) {
            $query->where('user_id', $userId);
        }

        $logs    = $query->paginate(30)->withQueryString();
        $actions = AuditLog::distinct()->pluck('action');

        return view('admin.audit-logs.index', compact('logs', 'actions'));
    }
}
