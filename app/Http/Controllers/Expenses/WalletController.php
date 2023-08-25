<?php

namespace App\Http\Controllers\Expenses;

use App\Actions\Expenses\Wallet\CreateWallet;
use App\Actions\Expenses\Wallet\DestroyWallet;
use App\Actions\Expenses\Wallet\EditWallet;
use App\Actions\Expenses\Wallet\FetchWallets;
use App\Actions\Expenses\Wallet\ReorderWallet;
use App\Actions\Expenses\Wallet\StoreWallet;
use App\Actions\Expenses\Wallet\UpdateWallet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Services\Expenses\ExpensePermissionService;

class WalletController extends Controller
{
    private $createWallet;
    private $storeWallet;
    private $fetchWallets;
    private $editWallet;
    private $updateWallet;
    private $destroyWallet;
    private $reorderWallet;


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
     * @param CreateWallet $createWallet 
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */

    public function __construct(
        CreateWallet $createWallet,
        StoreWallet $storeWallet,
        ExpensePermissionService $expensePermissionService,
        FetchWallets $fetchWallets,
        EditWallet $editWallet,
        UpdateWallet $updateWallet,
        DestroyWallet $destroyWallet,
        ReorderWallet $reorderWallet
    ) {
        $this->createWallet = $createWallet;
        $this->storeWallet = $storeWallet;
        $this->expensePermissionService = $expensePermissionService;
        $this->fetchWallets = $fetchWallets;
        $this->editWallet = $editWallet;
        $this->updateWallet = $updateWallet;
        $this->destroyWallet = $destroyWallet;
        $this->reorderWallet = $reorderWallet;
    }

    public function index()
    {
        $teamId = auth()->user()->currentTeam->id;

        try {
            $data = $this->fetchWallets->fetch($teamId);
        } catch (\Exception $e) {
            return redirect()->route('wallet.index')->with('failure', $e->getMessage());
        }

        return view('expenses.wallet.index', $data);
    }

    public function create()
    {
        try {
            $view = $this->createWallet->create();
        } catch (\Exception $e) {
            return redirect()->route('wallet.index')->with('failure', $e->getMessage());
        }
        return $view;
    }

    public function store(Request $request)
    {
        try {
            $this->storeWallet->store($request);
        } catch (\Exception $e) {
            return redirect()->route('wallet.index')->with('failure', $e->getMessage());
        }
        return redirect()->route('wallet.index')->with('success', 'Wallet successfully created.');
    }

    public function edit(Wallet $wallet)
    {
        try {
            $this->editWallet->edit();
            return view('expenses.wallet.edit', compact('wallet'));
        } catch (\Exception $e) {
            return redirect()->route('wallet.index')->with('failure', $e->getMessage());
        }
    }

    public function update(Request $request, Wallet $wallet)
    {
        try {
            $this->updateWallet->update($request, $wallet);
        } catch (\Exception $e) {
            return redirect()->route('wallet.index')->with('failure', $e->getMessage());
        }
        return redirect()->route('wallet.index')->with('success', 'Wallet successfully updated.');
    }

    public function destroy(Wallet $wallet)
    {
        try {
            $this->destroyWallet->destroy($wallet);
        } catch (\Exception $e) {
            return redirect()->route('wallet.index')->with('failure', $e->getMessage());
        }
        return redirect()->route('wallet.index')->with('success', 'Wallet successfully deleted.');
    }

    public function reorder(Request $request)
    {
        try {
            $this->reorderWallet->reorder($request);
            return response()->json(['message' => 'Order updated successfully']);
        } catch (\Exception $e) {
            return redirect()->route('payment-method.index')->with('failure', $e->getMessage());
        }
    }
}
