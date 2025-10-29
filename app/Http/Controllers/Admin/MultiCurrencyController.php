<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class MultiCurrencyController extends Controller
{
    /**
     * Display a listing of currencies
     */
    public function index(): View
    {
        $currencies = Currency::orderBy('is_base_currency', 'desc')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.modules.multi-currency.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new currency
     */
    public function create(): View
    {
        return view('admin.modules.multi-currency.create');
    }

    /**
     * Store a newly created currency
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|size:3|unique:currencies,code',
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'country' => 'nullable|string|max:255',
            'exchange_rate' => 'required|numeric|min:0.000001',
            'is_base_currency' => 'boolean',
            'is_active' => 'boolean',
            'decimal_places' => 'required|integer|min:0|max:6',
            'position' => 'required|in:before,after',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // If this is set as base currency, set exchange rate to 1
        if ($data['is_base_currency'] ?? false) {
            $data['exchange_rate'] = 1.000000;
            // Remove base currency from all other currencies
            Currency::where('is_base_currency', true)->update(['is_base_currency' => false]);
        }

        $data['last_updated'] = now();

        Currency::create($data);

        return redirect()->route('admin.modules.multi-currency.index')
            ->with('success', 'Currency created successfully.');
    }

    /**
     * Display the specified currency
     */
    public function show(Currency $currency): View
    {
        return view('admin.modules.multi-currency.show', compact('currency'));
    }

    /**
     * Show the form for editing the specified currency
     */
    public function edit(Currency $currency): View
    {
        return view('admin.modules.multi-currency.edit', compact('currency'));
    }

    /**
     * Update the specified currency
     */
    public function update(Request $request, Currency $currency): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|size:3|unique:currencies,code,' . $currency->id,
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'country' => 'nullable|string|max:255',
            'exchange_rate' => 'required|numeric|min:0.000001',
            'is_base_currency' => 'boolean',
            'is_active' => 'boolean',
            'decimal_places' => 'required|integer|min:0|max:6',
            'position' => 'required|in:before,after',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // If this is set as base currency, set exchange rate to 1
        if ($data['is_base_currency'] ?? false) {
            $data['exchange_rate'] = 1.000000;
            // Remove base currency from all other currencies
            Currency::where('is_base_currency', true)
                ->where('id', '!=', $currency->id)
                ->update(['is_base_currency' => false]);
        }

        $data['last_updated'] = now();

        $currency->update($data);

        return redirect()->route('admin.modules.multi-currency.index')
            ->with('success', 'Currency updated successfully.');
    }

    /**
     * Remove the specified currency
     */
    public function destroy(Currency $currency): RedirectResponse
    {
        // Prevent deletion of base currency
        if ($currency->is_base_currency) {
            return redirect()->route('admin.modules.multi-currency.index')
                ->with('error', 'Cannot delete the base currency.');
        }

        // Check if currency is being used by accounts
        if ($currency->accounts()->count() > 0) {
            return redirect()->route('admin.modules.multi-currency.index')
                ->with('error', 'Cannot delete currency that is being used by accounts.');
        }

        $currency->delete();

        return redirect()->route('admin.modules.multi-currency.index')
            ->with('success', 'Currency deleted successfully.');
    }

    /**
     * Update exchange rates for all currencies
     */
    public function updateExchangeRates(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'exchange_rates' => 'required|array',
            'exchange_rates.*' => 'required|numeric|min:0.000001',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $exchangeRates = $request->input('exchange_rates');
        
        foreach ($exchangeRates as $currencyId => $rate) {
            $currency = Currency::find($currencyId);
            if ($currency && !$currency->is_base_currency) {
                $currency->updateExchangeRate($rate);
            }
        }

        return redirect()->route('admin.modules.multi-currency.index')
            ->with('success', 'Exchange rates updated successfully.');
    }

    /**
     * Set a currency as base currency
     */
    public function setBaseCurrency(Currency $currency): RedirectResponse
    {
        $currency->setAsBaseCurrency();

        return redirect()->route('admin.modules.multi-currency.index')
            ->with('success', 'Base currency updated successfully.');
    }
}