<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\InsuranceProvider;
use App\Models\Tariff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TariffController extends Controller
{
    public function index(Request $request): View
    {
        $query = Tariff::with('insuranceProvider');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('insurance_provider_id')) {
            $query->where('insurance_provider_id', $request->insurance_provider_id);
        }

        if ($request->boolean('active_only')) {
            $query->active()->effectiveNow();
        }

        $tariffs = $query->latest()->paginate(15)->withQueryString();

        $categories = Tariff::distinct()->whereNotNull('category')->pluck('category');
        $providers = InsuranceProvider::where('is_active', true)->orderBy('name')->get();

        return view('hms.insurance.tariffs.index', compact('tariffs', 'categories', 'providers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|unique:tariffs,code',
            'name' => 'required|string|max:255',
            'insurance_provider_id' => 'nullable|exists:insurance_providers,id',
            'category' => 'nullable|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        Tariff::create($data);

        return redirect()->route('hms.insurance.tariffs.index')
            ->with('status', 'Tariff created successfully');
    }

    public function update(Request $request, Tariff $tariff): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|unique:tariffs,code,' . $tariff->id,
            'name' => 'required|string|max:255',
            'insurance_provider_id' => 'nullable|exists:insurance_providers,id',
            'category' => 'nullable|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $tariff->update($data);

        return redirect()->route('hms.insurance.tariffs.index')
            ->with('status', 'Tariff updated successfully');
    }

    public function destroy(Tariff $tariff): RedirectResponse
    {
        $tariff->delete();

        return redirect()->route('hms.insurance.tariffs.index')
            ->with('status', 'Tariff deleted successfully');
    }
}
