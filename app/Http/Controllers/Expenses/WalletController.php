<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Services\Expenses\ExpensePermissionService;

class WalletController extends Controller
{
    /**
     * 品目カテゴリサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * WalletController コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */
    public function __construct(ExpensePermissionService $expensePermissionService)
    {
        $this->expensePermissionService = $expensePermissionService;
    }


    public function create()
    {
        $isPermission = $this->expensePermissionService->checkPermission('wallet', 'create');

        if (!$isPermission) {
            return redirect()->route('wallet.index')->with('failure', 'You do not have permission to edit this wallet.');
        }

        return view('expenses.wallet.create');
    }

    public function store(Request $request)
    {
        $isPermission = $this->expensePermissionService->checkPermission('wallet', 'create');

        if (!$isPermission) {
            return redirect()->route('wallet.index')->with('failure', 'You do not have permission to edit this wallet.');
        }

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
        $isPermission = $this->expensePermissionService->checkPermission('wallet', 'read');

        if (!$isPermission) {
            return redirect()->route('welcome')->with('failure', 'You do not have permission to view this wallet.');
        }

        $teamId = auth()->user()->currentTeam->id;

        $permissions = [
            'canUpdate' => $this->expensePermissionService->checkPermission('wallet', 'update'),
            'canDelete' => $this->expensePermissionService->checkPermission('wallet', 'delete'),
            'canCreate' => $this->expensePermissionService->checkPermission('wallet', 'create')
        ];

        $wallets = Wallet::where('team_id', $teamId)->get();

        return view('expenses.wallet.index', compact('wallets', 'permissions'));
    }


    public function edit(Wallet $wallet)
    {

        $isPermission = $this->expensePermissionService->checkPermission('wallet', 'update');

        if (!$isPermission) {
            return redirect()->route('wallet.index')->with('failure', 'You do not have permission to update this wallet.');
        }

        return view('expenses.wallet.edit', compact('wallet'));
    }

    public function update(Request $request, Wallet $wallet)
    {
        $isPermission = $this->expensePermissionService->checkPermission('wallet', 'update');

        if (!$isPermission) {
            return redirect()->route('wallet.index')->with('failure', 'You do not have permission to update this wallet.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);


        $wallet->name = $request->name;
        $wallet->save();

        return redirect()->route('wallet.index')->with('success', 'Wallet successfully updated.');
    }

    public function destroy(Wallet $wallet)
    {
        $isPermission = $this->expensePermissionService->checkPermission('wallet', 'delete');

        if (!$isPermission) {
            return redirect()->route('wallet.index')->with('failure', 'You do not have permission to delete this wallet.');
        }

        // 既に支払い方法に登録されている場合、または家計簿データで使用されている場合は削除不可
        if ($wallet->paymentMethods->count() > 0 || $wallet->expenses->count() > 0) {
            return redirect()->route('wallet.index')->with('failure', 'Cannot delete wallet as it is associated with payment methods or finances.');
        }

        $wallet->delete();

        return redirect()->route('wallet.index')->with('success', 'Wallet successfully deleted.');
    }
}
