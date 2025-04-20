<?php

namespace App\Http\Controllers;

use App\Models\expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $expenses = expense::select('id', 'title', 'amount', 'category', 'type', 'description');

            return DataTables::of($expenses)
                ->addIndexColumn()
                ->addColumn('aksi', function ($expense) {
                    $btn  = '<button onclick="modalAction(\'' . url('/' . $expense->id) . '\')" class="btn btn-info btn-sm size-8"><i class="fa-solid fa-info"></i></button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/' . $expense->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm size-8"><i class="fa-regular fa-pen-to-square"></i></button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/expenses/' . $expense->id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm size-8"><i class="fa-solid fa-eraser"></i></button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        $page = (object)[
            'title' => 'List of expense and income'
        ];

        $activeMenu = 'expenses';

        $totalExpense = expense::where('type', 'expense')->sum('amount');
        $totalIncome = expense::where('type', 'income')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return view('index', [
            'page' => $page,
            'activeMenu' => $activeMenu,
            'balance' => $balance,
            'totalExpense' => $totalExpense,
            'totalIncome' => $totalIncome,
        ]);
    }

    public function create_ajax()
    {
        // mengrimkan view saja
        return view('create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'title' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'category' => 'required|in:food,transportation,bills,entertainment,health,education,others',
                'type' => 'required|in:income,expense',
                'description' => 'nullable|string',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed Validation',
                    'msgField' => $validator->errors(),
                ]);
            }


            $totalExpense = expense::where('type', 'expense')->sum('amount');
            $totalIncome = expense::where('type', 'income')->sum('amount');
            $balance = $totalIncome - $totalExpense;

            if ($request->type == 'expense' && $request->amount > $balance) {
                return response()->json([
                    'status' => false,
                    'message' => 'Expense amount exceeds balance'
                ]);
            }

            $data = $request->only(['title', 'amount', 'category', 'type', 'description']);
            Expense::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Data has been saved!',
            ]);
        }

        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $expense = Expense::find($id);
        return view('edit_ajax', compact('expense'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'title' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'category' => 'required|in:food,transportation,bills,entertainment,health,education,others',
                'type' => 'required|in:income,expense',
                'description' => 'nullable|string',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed Validation',
                    'msgField' => $validator->errors(),
                ]);
            }

            $totalExpense = expense::where('type', 'expense')->sum('amount');
            $totalIncome = expense::where('type', 'income')->sum('amount');
            $balance = $totalIncome - $totalExpense;

            $expense = Expense::find($id);

            if ($request->type == 'expense' && $request->amount > $balance + $expense->amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Expense amount exceeds balance'
                ]);
            }

            if ($expense) {
                $expense->update($request->only(['title', 'amount', 'category', 'type', 'description']));
                return response()->json([
                    'status' => true,
                    'message' => 'Data has been updated!'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data not found'
            ]);
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $expense = Expense::find($id);
        return view('confirm_ajax', compact('expense'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $expense = Expense::find($id);
            if ($expense) {
                $expense->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data has been deleted!'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data not found'
                ]);
            }
        }

        return redirect('/');
    }

    // Menampilkan detail expense
    public function show(string $id)
    {
        // Ambil data expense berdasarkan id
        $expense = Expense::find($id);

        // Kalau tidak ditemukan, redirect atau abort
        if (!$expense) {
            return redirect()->back()->with('error', 'Data not found');
        }

        // Informasi halaman
        $page = (object)[
            'title' => 'Expense detail'
        ];

        // Menu aktif untuk highlight di sidebar/nav
        $activeMenu = 'expenses';

        // Tampilkan ke view
        return view('show', [
            'page' => $page,
            'expense' => $expense,
            'activeMenu' => $activeMenu
        ]);
    }
}
