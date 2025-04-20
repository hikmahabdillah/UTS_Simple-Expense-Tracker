<?php

namespace App\Http\Controllers;

use App\Models\expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $activeMenu = 'dashboard';

        $totalExpense = expense::where('type', 'expense')->sum('amount');
        $totalIncome = expense::where('type', 'income')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Komposisi kategori pengeluaran
        $categoryExpenses = expense::select('category', DB::raw('SUM(amount) as total'))
            ->where('type', 'expense')
            ->groupBy('category')
            ->get();

        // Tren harian income & expense (tanpa batas waktu)
        $dailyData = expense::select(
            DB::raw("DATE(created_at) as date"),
            DB::raw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income"),
            DB::raw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense")
        )
            ->groupBy(DB::raw("DATE(created_at)"))
            ->orderBy(DB::raw("DATE(created_at)"))
            ->get();

        return view('dashboard', [
            'title' => 'Dashboard',
            'activeMenu' => $activeMenu,
            'balance' => $balance,
            'totalExpense' => $totalExpense,
            'totalIncome' => $totalIncome,
            'categoryExpenses' => $categoryExpenses,
            'dailyData' => $dailyData,
        ]);
    }
}
