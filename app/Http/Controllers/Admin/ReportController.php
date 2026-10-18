<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year  = $request->get('year',  now()->year);
        $month = $request->get('month', now()->month);

        // Monthly revenue by day
        $revenueByDay = Payment::paid()
            ->whereYear('paid_at', $year)->whereMonth('paid_at', $month)
            ->select(DB::raw('DAY(paid_at) as day'), DB::raw('SUM(amount-discount) as total'))
            ->groupBy('day')->orderBy('day')
            ->pluck('total', 'day');

        // Revenue by type this month
        $revenueByType = Payment::paid()
            ->whereYear('paid_at', $year)->whereMonth('paid_at', $month)
            ->select('type', DB::raw('SUM(amount-discount) as total'))
            ->groupBy('type')->pluck('total', 'type');

        // Attendance by day last 30 days
        $attendanceByDay = collect(range(29, 0))->map(function ($d) {
            $date = today()->subDays($d);
            return ['label' => $date->format('d M'), 'count' => Attendance::whereDate('date', $date)->count()];
        });

        // New members by month (last 6)
        $newMembersByMonth = collect(range(5, 0))->map(function ($m) {
            $date = now()->subMonths($m);
            return ['label' => $date->format('M'), 'count' => Member::whereYear('joined_at', $date->year)->whereMonth('joined_at', $date->month)->count()];
        });

        $totalRevenue  = Payment::paid()->whereYear('paid_at', $year)->whereMonth('paid_at', $month)->sum(DB::raw('amount-discount'));
        $totalExpenses = Expense::whereYear('incurred_at', $year)->whereMonth('incurred_at', $month)->sum('amount');
        $netProfit     = $totalRevenue - $totalExpenses;

        return view('admin.reports.index', compact(
            'year', 'month',
            'revenueByDay', 'revenueByType', 'attendanceByDay', 'newMembersByMonth',
            'totalRevenue', 'totalExpenses', 'netProfit',
        ));
    }
}
