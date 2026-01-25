<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\PurchaseInvoiceController;
use App\Http\Controllers\Admin\SalesInvoiceController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ProfitReportsController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\CashboxController;
use App\Http\Controllers\Admin\DebtsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\StockExcelController;
use App\Http\Controllers\Admin\SalesReturnController;
use App\Http\Controllers\Admin\ContractController;
use App\Http\Controllers\Admin\TermConditionController;



use App\Livewire\Actions\Logout;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'permission:view_dashboard'])
    ->name('dashboard');


Route::view('profile', 'profile')
    ->middleware('auth')
    ->name('profile');

Route::post('/logout', Logout::class)->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    /* =========================
    | المنتجات
    ========================= */

    /* عرض المنتجات */
    Route::middleware('permission:view_products')->group(function () {
        Route::get('products', [ProductController::class, 'index'])
            ->name('products.index');

        // show آخر حاجة عشان create ما يجيبش Not Found
        Route::get('products/{product}', [ProductController::class, 'show'])
            ->whereNumber('product')
            ->name('products.show');
    });

    /* إضافة منتج */
    Route::middleware('permission:create_product')->group(function () {
        Route::get('products/create', [ProductController::class, 'create'])
            ->name('products.create');

        Route::post('products', [ProductController::class, 'store'])
            ->name('products.store');
    });

    /* تعديل منتج */
    Route::middleware('permission:edit_product')->group(function () {
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])
            ->name('products.edit');

        Route::put('products/{product}', [ProductController::class, 'update'])
            ->name('products.update');
    });

    /* حذف منتج */
    Route::middleware('permission:delete_product')->group(function () {
        Route::delete('products/{product}', [ProductController::class, 'destroy'])
            ->name('products.destroy');
    });


    /* =========================
    | العملاء
    ========================= */

    Route::middleware('permission:view_customers')->group(function () {

        Route::get(
                'customers/{customer}/quotations',
                [ContractController::class, 'getCustomerQuotations']
            )->middleware('auth')
            ->name('customers.quotations');

        // create لازم قبل {customer}
        Route::get('customers/create', [CustomerController::class, 'create'])
            ->middleware('permission:create_customer')
            ->name('customers.create');

        Route::post('customers', [CustomerController::class, 'store'])
            ->middleware('permission:create_customer')
            ->name('customers.store');

        Route::get('customers', [CustomerController::class, 'index'])
            ->name('customers.index');

        Route::get('customers/{customer}', [CustomerController::class, 'show'])
            ->middleware('permission:show_customer_details')
            ->name('customers.show');
    });

    Route::middleware('permission:edit_customer')->group(function () {
        Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])
            ->name('customers.edit');

        Route::put('customers/{customer}', [CustomerController::class, 'update'])
            ->name('customers.update');
    });

    Route::middleware('permission:delete_customer')->group(function () {
        Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])
            ->name('customers.destroy');
    });



    /* =========================
     | الموردين
     ========================= */
    Route::middleware('permission:view_suppliers')->group(function () {

        // create لازم قبل {supplier}
        Route::get('suppliers/create', [SupplierController::class, 'create'])
            ->middleware('permission:create_supplier')
            ->name('suppliers.create');

        Route::post('suppliers', [SupplierController::class, 'store'])
            ->middleware('permission:create_supplier')
            ->name('suppliers.store');

        Route::get('suppliers', [SupplierController::class, 'index'])
            ->name('suppliers.index');

        Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])
            ->middleware('permission:show_supplier_details')
            ->name('suppliers.show');
    });

    Route::middleware('permission:edit_supplier')->group(function () {
        Route::get('suppliers/{supplier}/edit', [SupplierController::class, 'edit'])
            ->name('suppliers.edit');

        Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])
            ->name('suppliers.update');
    });

    Route::middleware('permission:delete_supplier')->group(function () {
        Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])
            ->name('suppliers.destroy');
    });
    
    /* =========================
    | فواتير الشراء
    ========================= */

    Route::middleware('permission:view_purchase_invoice')->group(function () {

        // إنشاء فاتورة (لازم قبل {invoice})
        Route::get('purchase-invoices/create', [PurchaseInvoiceController::class, 'create'])
            ->middleware('permission:create_purchase_invoice')
            ->name('purchase_invoices.create');

        Route::post('purchase-invoices', [PurchaseInvoiceController::class, 'store'])
            ->middleware('permission:create_purchase_invoice')
            ->name('purchase_invoices.store');

        // عرض القائمة
        Route::get('purchase-invoices', [PurchaseInvoiceController::class, 'index'])
            ->name('purchase_invoices.index');

        // عرض فاتورة
        Route::get('purchase-invoices/{invoice}', [PurchaseInvoiceController::class, 'show'])
            ->middleware('permission:show_purchase_invoice_details')
            ->name('purchase_invoices.show');
    });

    // تعديل
    Route::middleware('permission:edit_purchase_invoice')->group(function () {
        Route::get('purchase-invoices/{invoice}/edit', [PurchaseInvoiceController::class, 'edit'])
            ->name('purchase_invoices.edit');

        Route::put('purchase-invoices/{invoice}', [PurchaseInvoiceController::class, 'update'])
            ->name('purchase_invoices.update');
    });

    // حذف
    Route::middleware('permission:delete_purchase_invoice')->group(function () {
        Route::delete('purchase-invoices/{invoice}', [PurchaseInvoiceController::class, 'destroy'])
            ->name('purchase_invoices.destroy');
    });



    /* =========================
    | فواتير البيع
    ========================= */

    // عرض القائمة
    Route::middleware('permission:view_sales_invoice')->group(function () {

        // إنشاء (قبل {invoice})
        Route::get('sales-invoices/create', [SalesInvoiceController::class, 'create'])
            ->middleware('permission:create_sales_invoice')
            ->name('sales-invoices.create');

        Route::post('sales-invoices', [SalesInvoiceController::class, 'store'])
            ->middleware('permission:create_sales_invoice')
            ->name('sales-invoices.store');

        Route::get('sales-invoices', [SalesInvoiceController::class, 'index'])
            ->name('sales-invoices.index');

        // عرض فاتورة
        Route::get('sales-invoices/{invoice}', [SalesInvoiceController::class, 'show'])
            ->middleware('permission:show_sales_invoice_details')
            ->name('sales-invoices.show');

        // طباعة
        Route::post('sales-invoices/{invoice}/print', [SalesInvoiceController::class, 'print'])
            ->middleware('permission:print_sales_invoice')
            ->name('sales-invoices.print');
    });

    // تعديل
    Route::middleware('permission:edit_sales_invoice')->group(function () {
        Route::get('sales-invoices/{invoice}/edit', [SalesInvoiceController::class, 'edit'])
            ->name('sales-invoices.edit');

        Route::put('sales-invoices/{invoice}', [SalesInvoiceController::class, 'update'])
            ->name('sales-invoices.update');
    });

    // حذف
    Route::middleware('permission:delete_sales_invoice')->group(function () {
        Route::delete('sales-invoices/{invoice}', [SalesInvoiceController::class, 'destroy'])
            ->name('sales-invoices.destroy');
    });


    /* =========================
    | عروض الأسعار
    ========================= */

    // عرض القائمة + عرض عرض سعر
    Route::middleware('permission:view_quotations')->group(function () {

        // عرض كل عروض الأسعار
        Route::get('quotations', [App\Http\Controllers\Admin\QuotationController::class, 'index'])
            ->name('quotations.index');

        // عرض عرض سعر واحد
        Route::get('quotations/{quotation}', [App\Http\Controllers\Admin\QuotationController::class, 'show'])
            ->middleware('permission:show_quotation_details')
            ->whereNumber('quotation')
            ->name('quotations.show');
    });


    // إنشاء عرض سعر
    Route::middleware('permission:create_quotation')->group(function () {

        // لازم قبل {quotation}
        Route::get('quotations/create', [App\Http\Controllers\Admin\QuotationController::class, 'create'])
            ->name('quotations.create');

        Route::post('quotations', [App\Http\Controllers\Admin\QuotationController::class, 'store'])
            ->name('quotations.store');
    });


    // تعديل عرض سعر
    Route::middleware('permission:edit_quotation')->group(function () {

        Route::get('quotations/{quotation}/edit', [App\Http\Controllers\Admin\QuotationController::class, 'edit'])
            ->whereNumber('quotation')
            ->name('quotations.edit');

        Route::put('quotations/{quotation}', [App\Http\Controllers\Admin\QuotationController::class, 'update'])
            ->whereNumber('quotation')
            ->name('quotations.update');
    });

    Route::middleware('permission:convert_quotation')->group(function () {

        Route::post(
            'quotations/{quotation}/convert',
            [App\Http\Controllers\Admin\QuotationController::class, 'convertToInvoice']
        )
        ->whereNumber('quotation')
        ->name('quotations.convert');
    });

    Route::middleware('permission:print_quotation')->group(function () {
        Route::get('quotations/{quotation}/print',
            [App\Http\Controllers\Admin\QuotationController::class, 'print'])
            ->name('quotations.print');
    });



    // حذف عرض سعر
    Route::middleware('permission:delete_quotation')->group(function () {

        Route::delete('quotations/{quotation}', [App\Http\Controllers\Admin\QuotationController::class, 'destroy'])
            ->whereNumber('quotation')
            ->name('quotations.destroy');
    });

    /* =========================
    | عقود الاتفاق
    ========================= */

    Route::middleware('permission:create_contract')->group(function () {

        Route::get('contracts/create', [ContractController::class, 'create'])
            ->name('contracts.create');

        Route::post('contracts', [ContractController::class, 'store'])
            ->name('contracts.store');
    });

    Route::middleware('permission:view_contracts')->group(function () {

        Route::get('contracts', [ContractController::class, 'index'])
            ->name('contracts.index');

        Route::get('contracts/{contract}', [ContractController::class, 'show'])
            ->whereNumber('contract')
            ->name('contracts.show');

        Route::get('contracts/{contract}/print', [ContractController::class, 'print'])
        ->whereNumber('contract')
        ->name('contracts.print');
    });

    
    /* =========================
    | الشروط والأحكام
    ========================= */

    Route::middleware('permission:view_terms')->group(function () {

        Route::get('terms', [TermConditionController::class, 'index'])
            ->name('terms.index');

        Route::post('terms', [TermConditionController::class, 'store'])
            ->middleware('permission:create_term')
            ->name('terms.store');

        Route::put('terms/{term}', [TermConditionController::class, 'update'])
            ->middleware('permission:edit_term')
            ->name('terms.update');

        Route::delete('terms/{term}', [TermConditionController::class, 'destroy'])
            ->middleware('permission:delete_term')
            ->name('terms.destroy');
    });



    /* ==========================
     | مرتجعات المبيعات
     ========================= */
        Route::get('sales_returns', [SalesReturnController::class, 'index'])
            ->name('sales_returns.index');

        Route::get('sales_returns/create', [SalesReturnController::class, 'create'])
            ->name('sales_returns.create');

        Route::post('sales_returns', [SalesReturnController::class, 'store'])
            ->name('sales_returns.store');

        Route::get('sales_returns/{id}', [SalesReturnController::class, 'show'])
            ->name('sales_returns.show');
  


    /* =========================
     | المخزون
     ========================= */
    Route::middleware('permission:view_inventory')->group(function () {
        Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('inventory/card/{product}', [InventoryController::class, 'card'])->name('inventory.card');
        Route::get('inventory/adjust/{product}', [InventoryController::class, 'showAdjustForm'])->name('inventory.adjust_form');
        Route::post('inventory/adjust/{product}', [InventoryController::class, 'adjust'])->name('inventory.adjust');
        Route::get('inventory/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low_stock');
        Route::get('inventory/adjusted', [InventoryController::class, 'adjustedProducts'])->name('inventory.adjusted');
        Route::get('inventory/report', [InventoryController::class, 'report'])->name('inventory.report');

        // استيراد جرد من إكسل
        Route::get('upload',[StockExcelController::class,'create'])->name('inventory.upload');
        Route::post('upload',[StockExcelController::class,'store'])->name('inventory.upload.store');
        Route::get('pending',[StockExcelController::class,'pending'])->name('inventory.excel.pending');
        Route::post('inventory/{batch}/approve', [StockExcelController::class, 'approve'])->name('inventory.approve');
        Route::get('inventory/excel/{batch}', [StockExcelController::class,'show'])->name('inventory.excel_show');
        Route::post('inventory/excel/{batch}/reject',[StockExcelController::class,'reject'])->name('inventory.reject');
        Route::get('inventory/export/excel',[InventoryController::class, 'exportInventoryExcel'])->name('inventory.export.excel');

    });

    /* =========================
     | تقارير الأرباح (حساسة)
     ========================= */
    Route::middleware('permission:view_profits')->group(function () {
        Route::get('profit-reports', [ProfitReportsController::class, 'index'])
            ->name('profit-reports.index');

        Route::get('profit-reports/expenses', [ProfitReportsController::class, 'expenses'])
            ->name('profit-reports.expenses');

        Route::get('profit-reports/sales', [ProfitReportsController::class, 'sales'])
            ->name('profit-reports.sales');
    });

    /* =========================
     | المصروفات
     ========================= */
    Route::middleware('permission:view_expenses')->group(function () {
        Route::resource('expenses', ExpenseController::class);
    });

    /* =========================
     | الخزائن
     ========================= */
    Route::middleware('permission:view_cashbox')->group(function () {
        
        Route::get('cashboxes/transactions', [CashboxController::class, 'transactions'])
        ->name('cashboxes.transactions');
        
        Route::get('cashboxes/transfer', [CashboxController::class, 'showTransferForm'])
        ->name('cashboxes.transfer.form');

        Route::post('cashboxes/receive-from-customer',[CashboxController::class, 'receiveFromCustomer'])
        ->name('cashboxes.receive_from_customer');
        
        Route::post('cashboxes/transfer', [CashboxController::class, 'transfer'])
        ->name('cashboxes.transfer');
        
        Route::resource('cashboxes', CashboxController::class);
    });

    /* =========================
     | المديونيات
     ========================= */
    Route::middleware('permission:view_debts')->group(function () {
        Route::get('debts', [DebtsController::class, 'index'])->name('debts.index');
        Route::post('debts/pay-supplier', [DebtsController::class, 'paySupplierDebt'])
            ->name('debts.pay_supplier');
        Route::post('debts/receive-customer', [DebtsController::class, 'receiveCustomerPayment'])
            ->name('debts.receive_customer');
    });

    /* =========================
     | الصيانة
     ========================= */
     Route::middleware('permission:create_maintenance')->group(function () {
         Route::get('maintenances/create', [App\Http\Controllers\Admin\MaintenanceController::class, 'create'])
             ->name('maintenances.create');
 
         Route::post('maintenances', [App\Http\Controllers\Admin\MaintenanceController::class, 'store'])
             ->name('maintenances.store');
     });
     
    Route::middleware('permission:view_maintenances')->group(function () {
        Route::get('maintenances', [App\Http\Controllers\Admin\MaintenanceController::class, 'index'])
            ->name('maintenances.index');

        Route::get('maintenances/{maintenance}', [App\Http\Controllers\Admin\MaintenanceController::class, 'show'])
            ->middleware('permission:show_maintenance_details')
            ->name('maintenances.show');

        Route::post('maintenances/{maintenance}/print', [App\Http\Controllers\Admin\MaintenanceController::class, 'print'])
            ->middleware('permission:show_maintenance_details')
            ->name('maintenances.print');
    });


    Route::middleware('permission:edit_maintenance')->group(function () {
        Route::get('maintenances/{maintenance}/edit', [App\Http\Controllers\Admin\MaintenanceController::class, 'edit'])
            ->name('maintenances.edit');

        Route::put('maintenances/{maintenance}', [App\Http\Controllers\Admin\MaintenanceController::class, 'update'])
            ->name('maintenances.update');
    });

    Route::middleware('permission:delete_maintenance')->group(function () {
        Route::delete('maintenances/{maintenance}', [App\Http\Controllers\Admin\MaintenanceController::class, 'destroy'])
            ->name('maintenances.destroy');
    });

    Route::middleware('permission:collect_maintenance')->group(function () {
        Route::post('maintenances/{maintenance}/collect', [App\Http\Controllers\Admin\MaintenanceController::class, 'collect'])
            ->name('maintenances.collect');
    });

    /* =========================
     | المستخدمين والأدوار (إدارة عليا)
     ========================= */
    Route::middleware('permission:view_users')->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::middleware('permission:view_roles')->group(function () {
        Route::resource('roles', RoleController::class);
    });

    Route::middleware('permission:view_permission')->group(function () {
        Route::resource('permissions', PermissionController::class);
    });

});

require __DIR__ . '/auth.php';
