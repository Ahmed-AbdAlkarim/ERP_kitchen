<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TermCondition;
use Illuminate\Http\Request;

class TermConditionController extends Controller
{
    public function index()
    {
        $terms = TermCondition::orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.terms.index', compact('terms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'term' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        TermCondition::create([
            'term'       => $request->term,
            'active'     => $request->has('active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'تم إضافة الشرط بنجاح');
    }

    public function update(Request $request, TermCondition $term)
    {
        $request->validate([
            'term' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $term->update([
            'term'       => $request->term,
            'active'     => $request->has('active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'تم تعديل الشرط بنجاح');
    }

    public function destroy(TermCondition $term)
    {
        $term->delete();

        return back()->with('success', 'تم حذف الشرط');
    }
}
