<?php

namespace Bitoff\Mantis\Application\Http\Controllers;

use Bitoff\Mantis\Application\Http\Requests\PaymentMethodRequest;
use Bitoff\Mantis\Application\Models\Currency;
use Bitoff\Mantis\Application\Models\PaymentMethod;
use Bitoff\Mantis\Application\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Throwable;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::with(['children', 'tags'])->whereNull('parent_id')->get();

        return view('Mantis::paymentMethods.index')
            ->with('paymentMethods', $paymentMethods)
            ->with('tags', Tag::all())
            ->with('currencies', Currency::all());
    }

    public function show(PaymentMethod $paymentMethod)
    {
        return view('Mantis::paymentMethods.show')
            ->with('paymentMethod', $paymentMethod)
            ->with('tags', Tag::all());
    }

    public function store(PaymentMethodRequest $request)
    {
        DB::beginTransaction();

        try {
            $paymentMethod = new PaymentMethod($request->only('name'));
            $paymentMethod->saveOrFail();

            DB::commit();
            return redirect()->route('mantis.payment_methods.update.show', $paymentMethod);
        } catch (Throwable $exception) {
            DB::rollBack();
            logger()->error($exception->getMessage());
            return back()->withErrors($exception->getMessage());
        }
    }

    public function showUpdate(Request $request, PaymentMethod $paymentMethod)
    {
        return view('Mantis::paymentMethods.update', compact('paymentMethod'));
    }

    public function update(PaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        DB::beginTransaction();

        try {

            if ($request->get('parent_id') !== null) {
                $this->storeChild($request, $paymentMethod);
            } else {
                $this->storeParent($request, $paymentMethod);
            }
            if ($request->hasFile('icon')) {
                $paymentMethod->addMediaFromRequest('icon')->toMediaCollection('icon');
            }

            DB::commit();
            return redirect()->route('mantis.payment_methods.index');
        } catch (Throwable $exception) {
            DB::rollBack();
            logger()->error($exception->getMessage());
            return back()->withErrors($exception->getMessage());
        }
    }

    private function storeParent(PaymentMethodRequest $request, PaymentMethod $parent)
    {
        $this->updatePaymentWithRequest($request->merge([
            'parent_id' => null
        ]), $parent);

        if ($request->get('is_apply_to_children', 'off') === 'on') {
            foreach ($parent->children as $child) {
                $child->updateOrFail($request->only(['fee', 'min_time', 'max_time', 'time']));
                $child->currencies()->sync($request->get('currencies'));
                $child->tags()->sync($request->get('tags'));
            }
        }
    }

    private function storeChild(PaymentMethodRequest $request, PaymentMethod $child)
    {
        $this->updatePaymentWithRequest($request, $child);

        $child->currencies()->sync($request->get('currencies'));
        $child->tags()->sync($request->get('tags'));
    }

    /**
     * @param PaymentMethodRequest $request
     * @param PaymentMethod $paymentMethod
     * @return void
     * @throws Throwable
     */
    private function updatePaymentWithRequest(PaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $paymentMethod->updateOrFail($request->only([
            'name',
            'parent_id',
            'fee',
            'min_time',
            'max_time',
            'time',
        ]));
    }
}
