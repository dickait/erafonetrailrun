<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()->paginate(20);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:50|unique:promotions,code',
            'type' => 'required|in:earlybird,discount_code',
            'discount_type' => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'quota' => 'nullable|integer|min:0',
        ]);

        Promotion::create($request->all());

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion created successfully.');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:50|unique:promotions,code,' . $promotion->id,
            'type' => 'required|in:earlybird,discount_code',
            'discount_type' => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'quota' => 'nullable|integer|min:0',
        ]);

        $promotion->update($request->all());

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Promotion deleted successfully.');
    }
}
