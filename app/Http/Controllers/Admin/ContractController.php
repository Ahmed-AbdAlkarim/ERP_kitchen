<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractDetail;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\TermCondition;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::with(['customer','quotation'])
            ->latest()
            ->get();

        return view('admin.contracts.index', compact('contracts'));
    }

    public function create()
    {
        $customers = Customer::all();

        $defaultDetails = [
            'نوع المطبخ','عرض المطبخ','ارتفاع المطبخ','عمق سفلي','عمق علوي',
            'عمق الارتفاع','كود رخام','كود سادة','كود خشابي','مسكه','درفه',
            'المونيوم','فروميكا','مجلي','مفصلات','سحابات','تسكيره',
            'اضاءه','زجاج','ستاره','رف'
        ];

        return view('admin.contracts.create', compact(
            'customers',
            'defaultDetails'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'quotation_id'  => 'required|exists:quotations,id',
            'delivery_date' => 'required|date',
        ]);

        $contract = Contract::create([
            'customer_id'   => $request->customer_id,
            'quotation_id'  => $request->quotation_id,
            'delivery_date' => $request->delivery_date,
        ]);

        foreach ($request->details as $detail) {
            if (!empty($detail['title'])) {
                ContractDetail::create([
                    'contract_id' => $contract->id,
                    'title'       => $detail['title'],
                    'value'       => $detail['value'] ?? '',
                ]);
            }
        }

        return redirect()
            ->route('admin.contracts.show', $contract)
            ->with('success','تم إنشاء العقد بنجاح');
    }

    public function show(Contract $contract)
    {
        $contract->load(['customer','quotation','details']);

        $terms = TermCondition::where('active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.contracts.show', compact('contract','terms'));
    }

    /* =========================
     | Ajax: عروض أسعار عميل معين
     ========================= */
    public function getCustomerQuotations($customerId)
    {
        $quotations = Quotation::where('customer_id', $customerId)
            ->whereDoesntHave('contract') // 👈 المهم
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($q) {
                return [
                    'id'   => $q->id,
                    'code' => $q->quotation_number
                        ?? 'QT-' . $q->created_at->format('Y') . '-' . str_pad($q->id, 4, '0', STR_PAD_LEFT),
                ];
            });

        return response()->json($quotations);
    }

    public function print($contract)
    {
        $contract = Contract::with([
            'customer',
            'quotation',
            'details'
        ])->findOrFail($contract);

        $terms = TermCondition::where('active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.contracts.print', compact('contract', 'terms'));
    }
}
