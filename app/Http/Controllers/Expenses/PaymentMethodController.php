<?php

namespace App\Http\Controllers\Expenses;

use App\Actions\Expenses\PaymentMethod\CreatePaymentMethod;
use App\Actions\Expenses\PaymentMethod\GetPaymentMethods;
use App\Actions\Expenses\PaymentMethod\UpdatePaymentMethod;
use App\Actions\Expenses\PaymentMethod\DeletePaymentMethod;
use App\Actions\Expenses\PaymentMethod\ReorderPaymentMethod;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    private $createPaymentMethod;
    private $getPaymentMethods;
    private $updatePaymentMethod;
    private $deletePaymentMethod;
    private $reorderPaymentMethod;

    public function __construct(
        CreatePaymentMethod $createPaymentMethod,
        GetPaymentMethods $getPaymentMethods,
        UpdatePaymentMethod $updatePaymentMethod,
        DeletePaymentMethod $deletePaymentMethod,
        ReorderPaymentMethod $reorderPaymentMethod
    ) {
        $this->createPaymentMethod = $createPaymentMethod;
        $this->getPaymentMethods = $getPaymentMethods;
        $this->updatePaymentMethod = $updatePaymentMethod;
        $this->deletePaymentMethod = $deletePaymentMethod;
        $this->reorderPaymentMethod = $reorderPaymentMethod;
    }

    public function index()
    {
        $paymentMethods = $this->getPaymentMethods->getByTeam(auth()->user()->currentTeam->id);
        return view('expenses.payment_method.index', [
            'paymentMethods' => $paymentMethods,
            'isPermission' => true,  // TODO: 認可ロジックを追加すること
        ]);
    }

    public function create()
    {
        return view('expenses.payment_method.create');
    }

    public function store(Request $request)
    {
        $rules = $this->getValidationRules($request);

        $validatedData = $request->validate($rules);

        $data = $request->all();
        $data['team_id'] = auth()->user()->currentTeam->id;

        $this->createPaymentMethod->create($data);

        return redirect()->route('payment-method.index')->with('success', 'Payment method registered successfully.');
    }

    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        return view('expenses.payment_method.edit', compact('paymentMethod'));
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
        $this->updatePaymentMethod->update($id, $data);

        return redirect()->route('payment-method.index')->with('success', 'Payment method updated successfully.');
    }

    public function destroy($id)
    {
        $this->deletePaymentMethod->delete($id);
        return redirect()->route('payment-method.index')->with('success', 'Payment method deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order');
        $this->reorderPaymentMethod->reorder($order);
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
            'isCreditCard' => 'required|in:0,1'
        ];

        if ($request->input('isCreditCard') == 1) {
            $rules['closing_date'] = 'required|integer|min:1|max:31';
            $rules['payment_date'] = 'required|integer|min:1|max:31';
            $rules['month_offset'] = 'required|integer|min:0|max:3';
        }

        return $rules;
    }
}
