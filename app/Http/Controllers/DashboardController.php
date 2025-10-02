<?php

namespace App\Http\Controllers;

use App\Models\LogbookEntry;
use App\Models\StockItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    
     public function dashboard(): View
    {
        // Ambil data untuk kartu metrik
        $logbookTotal = LogbookEntry::count();
        $logbookThisWeek = LogbookEntry::where('created_at', '>=', Carbon::now()->startOfWeek())->count();
        $pendingReviews = LogbookEntry::where('status', 'Pending')->count();
        $completedToday = LogbookEntry::where('status', 'Completed')->whereDate('updated_at', Carbon::today())->count();
        
        $stockTotal = StockItem::count();
        $discrepancies = StockItem::whereRaw('physical_count != system_count')->count();
        $userCount = User::count();

        // Ambil aktivitas terbaru
        $recentActivities = LogbookEntry::with('user')->latest()->take(3)->get();

        return view('pages.dashboard', compact(
            'logbookTotal', 'logbookThisWeek', 'pendingReviews', 'completedToday',
            'stockTotal', 'discrepancies', 'userCount',
            'recentActivities'
        ));
    }

    public function logbook(): View
    {
        return view('pages.logbook');
    }

    public function stock(): View
    {
        return view('pages.stock');
    }

    public function reports(): View
    {
        return view('pages.reports');
    }

    public function settings(): View
    {
        return view('pages.settings');
    }
}