<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $r)
    {
        $products = Product::when($r->search, function ($q) use ($r) {
            $q->where(function($query) use ($r) {
                $query->where('name', 'like', "%{$r->search}%")
                    ->orWhere('sku', 'like', "%{$r->search}%");
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        if ($r->ajax()) {
            return view('admin.products.index', compact('products'))->render();
        }

        return view('admin.products.index', compact('products'));
    }



    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'=>'required|string|max:255',
            'type' => ['required', Rule::in([
                'public_sector',
                'aluminum_plastic_angles_sheet_door',
                'aluminum_iron_angles_sheet_door',
                'aluminum_iron_angles_wood_door',
                'full_wood',
            ])],

            'color_primary'   => 'nullable|string|max:100',
            'color_secondary' => 'nullable|string|max:100',

            'is_taxable' => 'required|boolean',


            'model'=>'nullable|string|max:255',
            'sku' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-z0-9\-]+$/',
                'unique:products,sku'
            ],

            'purchase_price'=>'required|numeric|min:0',
            'selling_price'=>'required|numeric|min:0',
            'min_allowed_price'=>'nullable|numeric|min:0',
            'warranty_type'=>'nullable|string|max:255',
            'warranty_period_days'=>'nullable|integer|min:0',
            'condition'=>['required',Rule::in(['new','used','imported'])],
            'images.*'=>'nullable|image|max:2048',
            'stock'=>'required|integer|min:0',
            'reorder_level'=>'nullable|integer|min:0',
            'is_service'=>'boolean',
            'notes'=>'nullable|string',
        ]);

        

        if($r->hasFile('images')){
            $images = [];
            foreach($r->file('images') as $file){
                $images[] = $file->store('products','public');
            }
            $data['image'] = $images;
        }

        Product::create($data);
        return redirect()->route('admin.products.index')->with('success','تم إضافة الصنف');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $r, Product $product)
    {
        $data = $r->validate([
            'name'=>'required|string|max:255',
            'type' => ['required', Rule::in([
                'public_sector',
                'aluminum_plastic_angles_sheet_door',
                'aluminum_iron_angles_sheet_door',
                'aluminum_iron_angles_wood_door',
                'full_wood',
            ])],

            'color_primary'   => 'nullable|string|max:100',
            'color_secondary' => 'nullable|string|max:100',

            'is_taxable' => 'required|boolean',


            'model'=>'nullable|string|max:255',
            'sku' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-z0-9\-]+$/',
                Rule::unique('products','sku')->ignore($product->id),
            ],

            'purchase_price'=>'required|numeric|min:0',
            'selling_price'=>'required|numeric|min:0',
            'min_allowed_price'=>'nullable|numeric|min:0',
            'warranty_type'=>'nullable|string|max:255',
            'warranty_period_days'=>'nullable|integer|min:0',
            'condition'=>['required',Rule::in(['new','used','imported'])],
            'images.*'=>'nullable|image|max:2048',
            'stock'=>'required|integer|min:0',
            'reorder_level'=>'nullable|integer|min:0',
            'is_service'=>'boolean',
            'notes'=>'nullable|string',
        ]);

        

        if($r->hasFile('images')){
            $images = [];
            foreach($r->file('images') as $file){
                $images[] = $file->store('products','public');
            }
            $data['image'] = $images;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success','تم تعديل الصنف بنجاح');
    }



    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success','تم حذف الصنف');
    }

    public function getTypeLabelAttribute()
    {
        return [
            'public_sector' => 'قطاع عام',
            'aluminum_plastic_angles_sheet_door' => 'المونيوم قطاع خاص زوايا بلاستيك باب صاج',
            'aluminum_iron_angles_sheet_door' => 'المونيوم قطاع خاص زوايا حديد باب صاج',
            'aluminum_iron_angles_wood_door' => 'المونيوم قطاع خاص زوايا حديد باب خشب',
            'full_wood' => 'خشب كامل',
        ][$this->type] ?? $this->type;
    }

}