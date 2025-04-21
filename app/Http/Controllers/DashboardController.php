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

        // Data saldo saat ini, total pengeluaran dan total pemasukan
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
            DB::raw("DATE(created_at) as date"), //Ambil hanya tanggalnya dari created_at (tanpa jam)
            DB::raw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income"), // Hitung total amount hanya jika type-nya 'income', jika tidak, 0.
            DB::raw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense")
        )
            ->groupBy(DB::raw("DATE(created_at)")) //Kelompokkan data berdasarkan tanggal
            ->orderBy(DB::raw("DATE(created_at)")) //Urutkan dari tanggal paling lama ke terbaru
            ->get(); // ambil data

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
