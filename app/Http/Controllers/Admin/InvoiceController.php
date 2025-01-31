<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Models\BillingCategory;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceTax;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

use function React\Promise\all;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('pages.finance.admin.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $highestId = Invoice::max('custom_id');
        $customId = $highestId ? $highestId + 1 : 1000;
        $billCategories = BillingCategory::all();
        $clients = Client::all();
        $invoiceTaxes = InvoiceTax::all();
        return view('pages.finance.admin.create-invoice', compact('customId', 'billCategories', 'clients', 'invoiceTaxes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // In your controller

    public function store(Request $request)
    {
        $request->merge([
            'recurring' => $request->has('recurring') ? true : false,
            'late_fees' => $request->has('late_fees') ? true : false,
            'notes' => $request->has('notes') ? true : false,
        ]);

        $validatedData = $request->validate([
            'custom_id' => 'required|string|max:255|unique:invoices',
            'date' => 'required|date',
            'due_date' => 'required|date',
            'bill_from' => 'required|exists:billing_categories,id',
            'bill_from_name' => 'required|string|max:255',
            'bill_from_email' => 'required|email|max:255',
            'bill_from_description' => 'nullable|string',
            'bill_to' => 'required|exists:clients,id',
            'bill_to_name' => 'required|string|max:255',
            'bill_to_email' => 'required|email|max:255',
            'bill_to_description' => 'nullable|string',
            'name.*' => 'required|string|max:255',
            'quantity.*' => 'required|integer|min:1',
            'price.*' => 'required|numeric|min:0',
            'tax.*' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value != 0 && !DB::table('invoice_taxes')->where('id', $value)->exists()) {
                        $fail('The selected tax is invalid.');
                    }
                },
            ],
            'tax_values.*' => 'nullable|numeric|min:0',
            'total.*' => 'required|numeric|min:0',
            'description.*' => 'nullable|string',
            'invoice_notes' => 'nullable|string',
            'recurring' => 'boolean',
            'late_fees' => 'boolean',
            'notes' => 'boolean',
            'status' => 'required|string',
            'tax_rate' => 'nullable|numeric',
            'tax_value' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'discount_value' => 'nullable|numeric',
            'discount_rate' => 'nullable|numeric',
            'attachments.*' => 'nullable|file|max:2048', // Add validation for attachments
        ], [
            'name.*.required' => 'The item name is required.',
            'name.*.string' => 'The item name must be a string.',
            'name.*.max' => 'The item name may not be greater than 255 characters.',
            'quantity.*.required' => 'The quantity is required.',
            'quantity.*.integer' => 'The quantity must be an integer.',
            'quantity.*.min' => 'The quantity must be at least 1.',
            'price.*.required' => 'The price is required.',
            'price.*.numeric' => 'The price must be a number.',
            'price.*.min' => 'The price must be at least 0.',
            'tax.*.numeric' => 'The tax must be a number.',
            'tax.*.min' => 'The tax must be at least 0.',
            'total.*.required' => 'The total is required.',
            'total.*.numeric' => 'The total must be a number.',
            'total.*.min' => 'The total must be at least 0.',
            'description.*.string' => 'The description must be a string.',
            'attachments.*.file' => 'Each attachment must be a file.',
            'attachments.*.max' => 'Each attachment may not be greater than 2MB.',
        ]);

        $files = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $fileName = date('Ymd_His') . '-' . $file->getClientOriginalName();
                $filePath = $file->storeAs('files/invoices', $fileName, 'public'); // Store in the 'invoices' folder
                $files[] = 'storage/' . $filePath;
            }
        }

        $invoice = Invoice::create(array_merge(
            $validatedData,
            ['attachments' => $files]
        ));

        foreach ($validatedData['name'] as $key => $itemName) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_name' => $itemName,
                'item_description' => $validatedData['description'][$key] ?? '',
                'quantity' => $validatedData['quantity'][$key],
                'price' => $validatedData['price'][$key],
                'tax_value' => $validatedData['tax_values'][$key] ?? 0,
                'invoice_tax_id' => isset($validatedData['tax'][$key]) && $validatedData['tax'][$key] != 0 ? $validatedData['tax'][$key] : null,
                'total' => $validatedData['total'][$key] ?? 0,
            ]);
        }

        $client = Client::find($request->input('bill_to'));
        if ($client) {
            $client->billed_category_id = $request->input('bill_from');
            $client->save();
        }

        if ($request->status == 'pending' || $request->status == 'paid') {
            $data = $request->all();
            $billForm = BillingCategory::where('id', $data['bill_from'])->first();
            $billTo = Client::where('id', $data['bill_to'])->first();

            $taxIds = $data['tax']; // Array of tax IDs

            // Filter out IDs with value 0
            $filteredTaxIds = array_filter($taxIds, function ($id) {
                return $id != "0";
            });

            if (!empty($filteredTaxIds)) {
                // Fetch taxes excluding the ones with ID 0 and order by the given IDs
                $taxes = InvoiceTax::whereIn('id', $filteredTaxIds)
                    ->orderByRaw("FIELD(id, " . implode(',', $filteredTaxIds) . ")")
                    ->get();
            } else {
                // No valid tax IDs, so return an empty collection
                $taxes = collect();
            }

            // Create a map with IDs as keys for easier lookup
            $taxMap = $taxes->keyBy('id')->toArray();

            // Organize the taxes in an array, adding '0' when necessary
            $invoiceTaxes = array_map(function ($id) use ($taxMap) {
                if ($id == "0") {
                    return [
                        'name' => null, // or whatever default you want for 'name'
                        'rate' => 0,    // or whatever default you want for 'rate'
                    ];
                }

                return [
                    'name' => $taxMap[$id]['tax_title'] ?? null,
                    'rate' => $taxMap[$id]['tax_rate'] ?? 0,
                ];
            }, $taxIds);

            Mail::to($data['bill_to_email'])->send(new InvoiceMail($data, $billForm, $billTo, $invoiceTaxes));
        }

        return redirect('/dashboard/finance/invoices')->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return view('pages.finance.admin.view-invoice', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $billCategories = BillingCategory::all();
        $clients = Client::all();
        $invoiceTaxes = InvoiceTax::all();
        $files = $invoice->attachments;
        $fileData = [];
        foreach ($files as $filePath) {
            $fileSize = filesize(public_path($filePath)); // Size in bytes

            $fileData[] = [
                'name' => basename($filePath),
                'size' => $fileSize,
                'path' => $filePath,
            ];
        }
        return view('pages.finance.admin.edit-invoice', compact('invoice', 'billCategories', 'clients', 'fileData', 'invoiceTaxes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the invoice by ID
        $invoice = Invoice::findOrFail($id);

        // Merge additional fields
        $request->merge([
            'recurring' => $request->has('recurring') ? true : false,
            'late_fees' => $request->has('late_fees') ? true : false,
            'notes' => $request->has('notes') ? true : false,
        ]);

        // Validate the request data
        $validatedData = $request->validate([
            'custom_id' => 'required|string|max:255|unique:invoices,custom_id,' . $invoice->id,
            'date' => 'required|date',
            'due_date' => 'required|date',
            'bill_from' => 'required|exists:billing_categories,id',
            'bill_from_name' => 'required|string|max:255',
            'bill_from_email' => 'required|email|max:255',
            'bill_from_description' => 'nullable|string',
            'bill_to' => 'required|exists:clients,id',
            'bill_to_name' => 'required|string|max:255',
            'bill_to_email' => 'required|email|max:255',
            'bill_to_description' => 'nullable|string',
            'name.*' => 'required|string|max:255',
            'quantity.*' => 'required|integer|min:1',
            'price.*' => 'required|numeric|min:0',
            'tax.*' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value != 0 && !DB::table('invoice_taxes')->where('id', $value)->exists()) {
                        $fail('The selected tax is invalid.');
                    }
                },
            ],
            'tax_values.*' => 'nullable|numeric|min:0',
            'total.*' => 'required|numeric|min:0',
            'description.*' => 'nullable|string',
            'invoice_notes' => 'nullable|string',
            'recurring' => 'boolean',
            'late_fees' => 'boolean',
            'notes' => 'boolean',
            'status' => 'required|string',
            'tax_rate' => 'nullable|numeric',
            'tax_value' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'discount_value' => 'nullable|numeric',
            'discount_rate' => 'nullable|numeric',
            'attachments.*' => 'nullable|file|max:2048', // Add validation for attachments
        ], [
            'name.*.required' => 'The item name is required.',
            'name.*.string' => 'The item name must be a string.',
            'name.*.max' => 'The item name may not be greater than 255 characters.',
            'quantity.*.required' => 'The quantity is required.',
            'quantity.*.integer' => 'The quantity must be an integer.',
            'quantity.*.min' => 'The quantity must be at least 1.',
            'price.*.required' => 'The price is required.',
            'price.*.numeric' => 'The price must be a number.',
            'price.*.min' => 'The price must be at least 0.',
            'tax.*.numeric' => 'The tax must be a number.',
            'tax.*.min' => 'The tax must be at least 0.',
            'total.*.required' => 'The total is required.',
            'total.*.numeric' => 'The total must be a number.',
            'total.*.min' => 'The total must be at least 0.',
            'description.*.string' => 'The description must be a string.',
            'attachments.*.file' => 'Each attachment must be a file.',
            'attachments.*.max' => 'Each attachment may not be greater than 2MB.',
        ]);

        // Handle attachments
        $files = $invoice->attachments ?? [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $fileName = date('Ymd_His') . '-' . $file->getClientOriginalName();
                $filePath = $file->storeAs('files/invoices', $fileName, 'public');
                $files[] = 'storage/' . $filePath;
            }
        }

        // Handle removal of existing images
        if ($request->has('remove_images')) {
            foreach ($request->input('remove_images') as $filePath) {
                if (($key = array_search($filePath, $files)) !== false) {
                    unset($files[$key]);
                    File::delete(public_path($filePath));
                }
            }
        }

        // Update invoice data
        $invoice->update(array_merge(
            $validatedData,
            ['attachments' => array_values($files)] // Ensure indexes are reset
        ));

        // Update invoice items
        $invoice->items()->delete(); // Clear existing items

        foreach ($validatedData['name'] as $key => $itemName) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_name' => $itemName,
                'item_description' => $validatedData['description'][$key] ?? '',
                'quantity' => $validatedData['quantity'][$key],
                'price' => $validatedData['price'][$key],
                'tax_value' => $validatedData['tax_values'][$key] ?? 0,
                'invoice_tax_id' => isset($validatedData['tax'][$key]) && $validatedData['tax'][$key] != 0 ? $validatedData['tax'][$key] : null,
                'total' => $validatedData['total'][$key] ?? 0,
            ]);
        }

        // Update client information
        $client = Client::find($request->input('bill_to'));
        if ($client) {
            $client->billed_category_id = $request->input('bill_from');
            $client->save();
        }

        if ($request->status == 'pending' || $request->status == 'paid') {
            $data = $request->all();
            $billForm = BillingCategory::where('id', $data['bill_from'])->first();
            $billTo = Client::where('id', $data['bill_to'])->first();

            $taxIds = $data['tax']; // Array of tax IDs

            // Filter out IDs with value 0
            $filteredTaxIds = array_filter($taxIds, function ($id) {
                return $id != "0";
            });

            if (!empty($filteredTaxIds)) {
                // Fetch taxes excluding the ones with ID 0 and order by the given IDs
                $taxes = InvoiceTax::whereIn('id', $filteredTaxIds)
                    ->orderByRaw("FIELD(id, " . implode(',', $filteredTaxIds) . ")")
                    ->get();
            } else {
                // No valid tax IDs, so return an empty collection
                $taxes = collect();
            }

            // Create a map with IDs as keys for easier lookup
            $taxMap = $taxes->keyBy('id')->toArray();

            // Organize the taxes in an array, adding '0' when necessary
            $invoiceTaxes = array_map(function ($id) use ($taxMap) {
                if ($id == "0") {
                    return [
                        'name' => null, // or whatever default you want for 'name'
                        'rate' => 0,    // or whatever default you want for 'rate'
                    ];
                }

                return [
                    'name' => $taxMap[$id]['tax_title'] ?? null,
                    'rate' => $taxMap[$id]['tax_rate'] ?? 0,
                ];
            }, $taxIds);

            Mail::to($data['bill_to_email'])->send(new InvoiceMail($data, $billForm, $billTo, $invoiceTaxes));
        }

        return redirect('/dashboard/finance/invoices')->with('success', 'Invoice updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->items()->delete();

        if ($invoice->attachments) {
            foreach ($invoice->attachments as $filePath) {
                File::delete(public_path($filePath));
            }
        }

        $invoice->delete();

        return redirect('/dashboard/finance/invoices')->with('success', 'Invoice deleted successfully.');
    }

    public function preview(Request $request)
    {
        $request->merge([
            'recurring' => $request->has('recurring') ? true : false,
            'late_fees' => $request->has('late_fees') ? true : false,
            'notes' => $request->has('notes') ? true : false,
        ]);

        $validatedData = $request->validate([
            'custom_id' => 'required|string|max:255|unique:invoices',
            'date' => 'required|date',
            'due_date' => 'required|date',
            'bill_from' => 'required|exists:billing_categories,id',
            'bill_from_name' => 'required|string|max:255',
            'bill_from_email' => 'required|email|max:255',
            'bill_from_description' => 'nullable|string',
            'bill_to' => 'required|exists:clients,id',
            'bill_to_name' => 'required|string|max:255',
            'bill_to_email' => 'required|email|max:255',
            'bill_to_description' => 'nullable|string',
            'name.*' => 'required|string|max:255',
            'quantity.*' => 'required|integer|min:1',
            'price.*' => 'required|numeric|min:0',
            'tax.*' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value != 0 && !DB::table('invoice_taxes')->where('id', $value)->exists()) {
                        $fail('The selected tax is invalid.');
                    }
                },
            ],
            'tax_values.*' => 'nullable|numeric|min:0',
            'total.*' => 'required|numeric|min:0',
            'description.*' => 'nullable|string',
            'invoice_notes' => 'nullable|string',
            'recurring' => 'boolean',
            'late_fees' => 'boolean',
            'notes' => 'boolean',
            'tax_rate' => 'nullable|numeric',
            'tax_value' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'discount_value' => 'nullable|numeric',
            'discount_rate' => 'nullable|numeric',
            'attachments.*' => 'nullable|file|max:2048', // Add validation for attachments
        ], [
            'name.*.required' => 'The item name is required.',
            'name.*.string' => 'The item name must be a string.',
            'name.*.max' => 'The item name may not be greater than 255 characters.',
            'quantity.*.required' => 'The quantity is required.',
            'quantity.*.integer' => 'The quantity must be an integer.',
            'quantity.*.min' => 'The quantity must be at least 1.',
            'price.*.required' => 'The price is required.',
            'price.*.numeric' => 'The price must be a number.',
            'price.*.min' => 'The price must be at least 0.',
            'tax.*.numeric' => 'The tax must be a number.',
            'tax.*.min' => 'The tax must be at least 0.',
            'total.*.required' => 'The total is required.',
            'total.*.numeric' => 'The total must be a number.',
            'total.*.min' => 'The total must be at least 0.',
            'description.*.string' => 'The description must be a string.',
            'attachments.*.file' => 'Each attachment must be a file.',
            'attachments.*.max' => 'Each attachment may not be greater than 2MB.',
        ]);

        $data = $request->all();
        $billForm = BillingCategory::where('id', $data['bill_from'])->first();
        $billTo = Client::where('id', $data['bill_to'])->first();

        $taxIds = $data['tax']; // Array of tax IDs

        // Filter out IDs with value 0
        $filteredTaxIds = array_filter($taxIds, function ($id) {
            return $id != "0";
        });

        if (!empty($filteredTaxIds)) {
            // Fetch taxes excluding the ones with ID 0 and order by the given IDs
            $taxes = InvoiceTax::whereIn('id', $filteredTaxIds)
                ->orderByRaw("FIELD(id, " . implode(',', $filteredTaxIds) . ")")
                ->get();
        } else {
            // No valid tax IDs, so return an empty collection
            $taxes = collect();
        }

        // Create a map with IDs as keys for easier lookup
        $taxMap = $taxes->keyBy('id')->toArray();

        // Organize the taxes in an array, adding '0' when necessary
        $invoiceTaxes = array_map(function ($id) use ($taxMap) {
            if ($id == "0") {
                return [
                    'name' => null, // or whatever default you want for 'name'
                    'rate' => 0,    // or whatever default you want for 'rate'
                ];
            }

            return [
                'name' => $taxMap[$id]['tax_title'] ?? null,
                'rate' => $taxMap[$id]['tax_rate'] ?? 0,
            ];
        }, $taxIds);

        return view('pages.finance.admin.preview-invoice', compact(['data', 'billForm', 'billTo', 'invoiceTaxes']));
    }

    public function downloadPdf(Request $request)
    {
        $request->merge([
            'recurring' => $request->has('recurring') ? true : false,
            'late_fees' => $request->has('late_fees') ? true : false,
            'notes' => $request->has('notes') ? true : false,
        ]);

        $validatedData = $request->validate([
            'custom_id' => 'required|string|max:255|unique:invoices',
            'date' => 'required|date',
            'due_date' => 'required|date',
            'bill_from' => 'required|exists:billing_categories,id',
            'bill_from_name' => 'required|string|max:255',
            'bill_from_email' => 'required|email|max:255',
            'bill_from_description' => 'nullable|string',
            'bill_to' => 'required|exists:clients,id',
            'bill_to_name' => 'required|string|max:255',
            'bill_to_email' => 'required|email|max:255',
            'bill_to_description' => 'nullable|string',
            'name.*' => 'required|string|max:255',
            'quantity.*' => 'required|integer|min:1',
            'price.*' => 'required|numeric|min:0',
            'tax.*' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value != 0 && !DB::table('invoice_taxes')->where('id', $value)->exists()) {
                        $fail('The selected tax is invalid.');
                    }
                },
            ],
            'tax_values.*' => 'nullable|numeric|min:0',
            'total.*' => 'required|numeric|min:0',
            'description.*' => 'nullable|string',
            'invoice_notes' => 'nullable|string',
            'recurring' => 'boolean',
            'late_fees' => 'boolean',
            'notes' => 'boolean',
            'tax_rate' => 'nullable|numeric',
            'tax_value' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'discount_value' => 'nullable|numeric',
            'discount_rate' => 'nullable|numeric',
            'attachments.*' => 'nullable|file|max:2048', // Add validation for attachments
        ], [
            'name.*.required' => 'The item name is required.',
            'name.*.string' => 'The item name must be a string.',
            'name.*.max' => 'The item name may not be greater than 255 characters.',
            'quantity.*.required' => 'The quantity is required.',
            'quantity.*.integer' => 'The quantity must be an integer.',
            'quantity.*.min' => 'The quantity must be at least 1.',
            'price.*.required' => 'The price is required.',
            'price.*.numeric' => 'The price must be a number.',
            'price.*.min' => 'The price must be at least 0.',
            'tax.*.numeric' => 'The tax must be a number.',
            'tax.*.min' => 'The tax must be at least 0.',
            'total.*.required' => 'The total is required.',
            'total.*.numeric' => 'The total must be a number.',
            'total.*.min' => 'The total must be at least 0.',
            'description.*.string' => 'The description must be a string.',
            'attachments.*.file' => 'Each attachment must be a file.',
            'attachments.*.max' => 'Each attachment may not be greater than 2MB.',
        ]);

        $data = $request->all();
        $billForm = BillingCategory::where('id', $data['bill_from'])->first();
        $billTo = Client::where('id', $data['bill_to'])->first();

        $taxIds = $data['tax']; // Array of tax IDs

        // Filter out IDs with value 0
        $filteredTaxIds = array_filter($taxIds, function ($id) {
            return $id != "0";
        });

        if (!empty($filteredTaxIds)) {
            // Fetch taxes excluding the ones with ID 0 and order by the given IDs
            $taxes = InvoiceTax::whereIn('id', $filteredTaxIds)
                ->orderByRaw("FIELD(id, " . implode(',', $filteredTaxIds) . ")")
                ->get();
        } else {
            // No valid tax IDs, so return an empty collection
            $taxes = collect();
        }

        // Create a map with IDs as keys for easier lookup
        $taxMap = $taxes->keyBy('id')->toArray();

        // Organize the taxes in an array, adding '0' when necessary
        $data['tax'] = array_map(function ($id) use ($taxMap) {
            if ($id == "0") {
                return [
                    'name' => null, // or whatever default you want for 'name'
                    'rate' => 0,    // or whatever default you want for 'rate'
                ];
            }

            return [
                'name' => $taxMap[$id]['tax_title'] ?? null,
                'rate' => $taxMap[$id]['tax_rate'] ?? 0,
            ];
        }, $taxIds);

        $pdf = Pdf::loadView('pages.finance.admin.invoice-pdf', ['data' => $data, 'billForm' => $billForm, 'billTo' => $billTo]);

        return $pdf->download('invoice.pdf');
    }

    public function download(Invoice $invoice)
    {
        $pdf = Pdf::loadView('pages.finance.admin.invoice-pdf-download', ['invoice' => $invoice]);

        return $pdf->download('invoice.pdf');
    }

    public function send(Invoice $invoice)
    {
        Mail::to($invoice->bill_to_email)->send(new InvoiceMail('', '', '', '', $invoice, true));

        return redirect()->back();
    }

    public function previewInvoice(Invoice $invoice)
    {
        return view('pages.finance.admin.preview-invoice-send', compact('invoice'));
    }
}
