<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicinesController extends Controller
{
    public function index(): View
    {
        $medicines = Medicine::with('category')->orderBy('name')->paginate(10);
        return view('hms.pharmacy.medicines.index', compact('medicines'));
    }

    public function create(): View
    {
        $categories = MedicineCategory::orderBy('name')->pluck('name', 'id');
        return view('hms.pharmacy.medicines.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'generic_name' => 'nullable|string',
            'category_id' => 'required|exists:medicine_categories,id',
            'manufacturer' => 'nullable|string',
            'dosage_form' => 'required|string',
            'strength' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'description' => 'nullable|string',
        ]);

        Medicine::create($data);
        return redirect()->route('hms.pharmacy.medicines.index')->with('status', 'Medicine added');
    }

    public function show(Medicine $medicine): View
    {
        $medicine->load('category');
        return view('hms.pharmacy.medicines.show', compact('medicine'));
    }

    public function edit(Medicine $medicine): View
    {
        $categories = MedicineCategory::orderBy('name')->pluck('name', 'id');
        return view('hms.pharmacy.medicines.edit', compact('medicine', 'categories'));
    }

    public function update(Request $request, Medicine $medicine): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'generic_name' => 'nullable|string',
            'category_id' => 'required|exists:medicine_categories,id',
            'manufacturer' => 'nullable|string',
            'dosage_form' => 'required|string',
            'strength' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'description' => 'nullable|string',
        ]);

        $medicine->update($data);
        return redirect()->route('hms.pharmacy.medicines.index')->with('status', 'Medicine updated successfully');
    }

    public function destroy(Medicine $medicine): RedirectResponse
    {
        $medicine->delete();
        return redirect()->route('hms.pharmacy.medicines.index')->with('status', 'Medicine deleted successfully');
    }
}
