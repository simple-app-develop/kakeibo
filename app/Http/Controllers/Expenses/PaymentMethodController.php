<?php

namespace App\Http\Controllers\Expenses;

use App\Actions\Expenses\PaymentMethod\CreatePaymentMethod;
use App\Actions\Expenses\PaymentMethod\GetPaymentMethods;
use App\Actions\Expenses\PaymentMethod\UpdatePaymentMethod;
use App\Actions\Expenses\PaymentMethod\DeletePaymentMethod;
use App\Actions\Expenses\PaymentMethod\EditPaymentMethod;
use App\Actions\Expenses\PaymentMethod\ReorderPaymentMethod;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    private $createPaymentMethod;
    private $getPaymentMethods;
    private $editPaymentMethod;
    private $updatePaymentMethod;
    private $deletePaymentMethod;
    private $reorderPaymentMethod;

    public function __construct(
        CreatePaymentMethod $createPaymentMethod,
        GetPaymentMethods $getPaymentMethods,
        EditPaymentMethod $editPaymentMethod,
        UpdatePaymentMethod $updatePaymentMethod,
        DeletePaymentMethod $deletePaymentMethod,
        ReorderPaymentMethod $reorderPaymentMethod
    ) {
        $this->createPaymentMethod = $createPaymentMethod;
        $this->getPaymentMethods = $getPaymentMethods;
        $this->editPaymentMethod = $editPaymentMethod;
        $this->updatePaymentMethod = $updatePaymentMethod;
        $this->deletePaymentMethod = $deletePaymentMethod;
        $this->reorderPaymentMethod = $reorderPaymentMethod;
    }

    public function index()
    {
        $result = $this->getPaymentMethods->getByTeam(auth()->user()->currentTeam->id);
        // ビューにデータを渡す
        return view('expenses.payment_method.index', [
            'paymentMethods' => $result['paymentMethods'],
            'permissions' => $result['permissions']
        ]);
    }

    public function create()
    {

        try {
            $view = $this->createPaymentMethod->create();
        } catch (\Exception $e) {
            return redirect()->route('payment-method.index')->with('failure', $e->getMessage());
        }
        // 品目カテゴリ作成ビューを返す
        return $view;
    }

    public function store(Request $request)
    {
        $rules = $this->getValidationRules($request);

        $validatedData = $request->validate($rules);

        $data = $request->all();
        $data['team_id'] = auth()->user()->currentTeam->id;
        try {
            $this->createPaymentMethod->store($data);
        } catch (\Exception $e) {
            return redirect()->route('payment-method.index')->with('failure', $e->getMessage());
        }
        return redirect()->route('payment-method.index')->with('success', 'Payment method registered successfully.');
    }

    public function edit($id)
    {
        try {
            $data = $this->editPaymentMethod->get($id, $this->getCurrentTeamId());
        } catch (\Exception $e) {
            return redirect()->route('payment-method.index')->with('failure', $e->getMessage());
        }
        return view('expenses.payment_method.edit', [
            'paymentMethod' => $data['paymentMethod'],
            'wallets' => $data['wallets']
        ]);
    }


    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $rules = [
            'name' => [
                'required',
                'string',
                'max:20',
                Rule::unique('payment_methods')
                    ->where(function ($query) use ($request) {
                        return $query->where('team_id', $request->user()->currentTeam->id);
                    })->ignore($method->id)
            ],
        ];

        $validatedData = $request->validate($rules);

        $data = $request->only(['name']);
        try {
            $this->updatePaymentMethod->update($id, $data);
        } catch (\Exception $e) {
            return redirect()->route('payment-method.index')->with('failure', $e->getMessage());
        }
        return redirect()->route('payment-method.index')->with('success', 'Payment method updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $this->deletePaymentMethod->delete($id);
        } catch (\Exception $e) {
            return redirect()->route('payment-method.index')->with('failure', $e->getMessage());
        }
        return redirect()->route('payment-method.index')->with('success', 'Payment method deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order');
        try {
            $this->reorderPaymentMethod->reorder($order);
        } catch (\Exception $e) {
            return redirect()->route('payment-method.index')->with('failure', $e->getMessage());
        }
        return response()->json(['message' => 'Order updated successfully']);
    }

    private function getValidationRules(Request $request)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:20',
                Rule::unique('payment_methods')->where(function ($query) use ($request) {
                    return $query->where('team_id', $request->user()->currentTeam->id);
                })
            ],
            'isCreditCard' => 'required|in:0,1',
            'wallet_id' => 'required|exists:wallets,id'
        ];

        if ($request->input('isCreditCard') == 1) {
            $rules['closing_date'] = 'required|integer|min:1|max:31';
            $rules['payment_date'] = 'required|integer|min:1|max:31';
            $rules['month_offset'] = 'required|integer|min:0|max:3';
        }

        return $rules;
    }

    /**
     * 現在のチームIDを取得
     *
     * @return int 現在のチームID
     */

    private function getCurrentTeamId()
    {
        // 認証済みのユーザーから現在のチームIDを取得して返す
        return auth()->user()->currentTeam->id;
    }
}
