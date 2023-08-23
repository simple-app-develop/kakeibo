<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;

class WalletController extends Controller
{
    public function create()
    {
        return view('expenses.wallet.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|integer|min:0',
        ]);

        $wallet = new Wallet;
        $wallet->team_id = $request->user()->currentTeam->id;
        $wallet->name = $request->name;
        $wallet->balance = $request->balance;
        $wallet->save();

        return redirect()->route('wallet.index')->with('success', 'Wallet successfully created.');
    }

    public function index()
    {
        $wallets = Wallet::where('team_id', auth()->user()->currentTeam->id)->get();
        return view('expenses.wallet.index', compact('wallets'));
    }
}
