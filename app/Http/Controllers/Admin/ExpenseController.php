<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExpensesExport;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\ExpenseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenseCategories = ExpenseCategory::all();
        $expenses = Expense::all();
        $allTags = [];
        $expenseDetail = ExpenseDetail::firstOrCreate(
            ['id' => 1],
            ['income' => 0, 'budget' => 0]
        );

        foreach ($expenses as $expense) {
            $tags = json_decode($expense->tags, true);
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    $allTags[] = $tag['value'];
                }
            }
        }

        // Ensure $tags is an indexed array
        $allTags = array_values(array_unique($allTags));

        $totalSpendAmount = Expense::getTotalAmountForCurrentMonth();
        $remainingBudget = $expenseDetail->budget - $totalSpendAmount;
        $today = new \DateTime();
        $endOfMonth = new \DateTime('first day of next month');
        $daysLeft = $endOfMonth->diff($today)->days;
        $safeToSpendPerDay = $daysLeft > 0 ? max($remainingBudget / $daysLeft, 0) : 0;
        $percentageUsed = $expenseDetail->budget > 0 ? min(($totalSpendAmount / $expenseDetail->budget) * 100, 100) : 0;

        $categoryUsageCounts = Expense::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->select('expense_category_id', DB::raw('count(*) as count'))
            ->groupBy('expense_category_id')
            ->with('expenseCategory')
            ->get();

        // Prepare data for the chart
        $categories = $categoryUsageCounts->pluck('expenseCategory.name');
        $counts = $categoryUsageCounts->pluck('count');

        return view('pages.expenses.admin.index', compact(['expenseCategories', 'expenses', 'allTags', 'expenseDetail', 'safeToSpendPerDay', 'percentageUsed', 'categoryUsageCounts', 'categories', 'counts', 'totalSpendAmount']));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'tags' => 'nullable|string',
            'amount' => 'required|numeric',
            'expense_category_id' => 'required|exists:App\Models\ExpenseCategory,id',
            'payment_mode' => 'required|string',
            'details' => 'nullable|string',
            'created_at' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'expense_category_id.required' => 'Please Select a category!',
            'images.*.image' => 'Each file must be an image.',
            'images.*.mimes' => 'Allowed file types are: jpeg, png, jpg, gif, svg.',
            'images.*.max' => 'Each image must not be greater than 2MB.',
        ]);

        $expense = new Expense();
        $expense->title = $validatedData['title'];
        $expense->tags = $validatedData['tags'];
        $expense->amount = $validatedData['amount'];
        $expense->expense_category_id = $validatedData['expense_category_id'];
        $expense->payment_mode = $validatedData['payment_mode'];
        $expense->details = $validatedData['details'];
        $expense->created_at = $validatedData['created_at'] ?? now();

        // Save payment image if provided
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = date('Ymd_His') . '-' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('images/expenses', $imageName, 'public');
                $imagePath = 'storage/' . $imagePath;
                $images[] = $imagePath;
            }
        }
        $expense->images = $images;
        // Save the expense to the database
        $expense->save();

        // Redirect the user back or do any other action after saving the expense
        return redirect()->back()->with('success', 'Expense added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'tags' => 'nullable|string',
            'amount' => 'required|numeric',
            'expense_category_id' => 'required|exists:App\Models\ExpenseCategory,id',
            'payment_mode' => 'required|string',
            'details' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'created_at' => 'nullable',
        ], [
            'expense_category_id.required' => 'Please Select a category!',
            'images.*.image' => 'Each file must be an image.',
            'images.*.mimes' => 'Allowed file types are: jpeg, png, jpg, gif, svg.',
            'images.*.max' => 'Each image must not be greater than 2MB.',
        ]);

        // Find the expense by ID
        $expense = Expense::findOrFail($id);

        // Update the expense attributes
        $expense->title = $validatedData['title'];
        $expense->tags = $validatedData['tags'];
        $expense->amount = $validatedData['amount'];
        $expense->expense_category_id = $validatedData['expense_category_id'];
        $expense->payment_mode = $validatedData['payment_mode'];
        $expense->details = $validatedData['details'];
        $expense->created_at = $validatedData['created_at'] ?? now();

        // Handle existing images
        $existingImages = $request->input('existing_images', []);
        $removeImages = $request->input('remove_images', []);
        $uploadedImages = $request->file('images', []);

        // Process new images
        $imagePaths = [];
        foreach ($uploadedImages as $image) {
            $imageName = date('Ymd_His') . '-' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('images/expenses', $imageName, 'public');
            $imagePath = 'storage/' . $imagePath;
            $imagePaths[] = $imagePath;
        }

        // Remove deleted images from storage
        foreach ($removeImages as $imagePath) {
            File::delete(public_path($imagePath));
        }

        // Combine existing and new image paths
        $finalImages = array_merge($existingImages, $imagePaths);
        $finalImages = array_diff($finalImages, $removeImages);
        $expense->images = array_values($finalImages);

        // Save the updated expense to the database
        $expense->save();

        // Redirect the user back or do any other action after updating the expense
        return redirect()->back()->with('success', 'Expense updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {

        // Delete the item
        if (!empty($expense->images)) {
            foreach ($expense->images as $imagePath) {
                File::delete(public_path($imagePath));
            }
        }

        $expense->delete();

        // Redirect or respond as needed
        return redirect('/dashboard/expenses')->with('success', 'Expenses deleted successfully');
    }

    public function getExpenseDrawer(Request $request)
    {
        $expenseId = $request->input('id');

        // Retrieve customer data based on customer ID
        $expense = Expense::find($expenseId);
        $images = $expense->images;
        $imageData = [];
        foreach ($images as $imagePath) {
            $imageSize = filesize(public_path($imagePath)); // Size in bytes

            $imageData[] = [
                'name' => basename($imagePath),
                'size' => $imageSize,
                'path' => $imagePath,
            ];
        }

        if (!$expense) {
            return response()->json(['error' => 'Expense not found'], 404);
        }

        $expenses = Expense::all();
        $allTags = [];

        foreach ($expenses as $data) {
            $tags = json_decode($data->tags, true);
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    $allTags[] = $tag['value'];
                }
            }
        }

        // Ensure $tags is an indexed array
        $tags = array_values(array_unique($allTags));

        $expenseCategories = ExpenseCategory::all();
        // Render the drawer card view with customer data
        $drawerContent = view('pages.expenses.admin.partials.editExpenseDrawerCard', ['expense' => $expense, 'expenseCategories' => $expenseCategories, 'tags' => $tags, 'imageData' => $imageData])->render();

        // Return the rendered drawer card HTML as part of the AJAX response
        return response()->json(['drawerContent' => $drawerContent]);
    }

    public function exportCSV()
    {
        return Excel::download(new ExpensesExport, 'expenses.csv');
    }

    public function exportExcel()
    {
        return Excel::download(new ExpensesExport, 'expenses.xlsx');
    }

    public function exportPDF()
    {
        $expenses = Expense::all();
        $pdf = Pdf::loadView('pdf.expenses.expenses', compact('expenses'));
        return $pdf->download('expenses.pdf');
    }

    public function updateIncome(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric', // Add any additional validation rules as needed
        ]);

        $newValue = $request->input('value');
        $expenseDetail = ExpenseDetail::first(); // Find or create new if not found

        // Update the value
        $expenseDetail->income = $newValue;
        $expenseDetail->save();

        return response()->json(['success' => true]);
    }

    public function updateBudget(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric', // Add any additional validation rules as needed
        ]);

        $newValue = $request->input('value');
        $expenseDetail = ExpenseDetail::first(); // Find or create new if not found

        // Update the value
        $expenseDetail->budget = $newValue;
        $expenseDetail->save();

        return response()->json(['success' => true]);
    }
}
