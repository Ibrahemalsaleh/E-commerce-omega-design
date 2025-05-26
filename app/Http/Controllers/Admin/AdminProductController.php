<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Collection; // Assuming you have a Collection model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::paginate(20); // Paginate results

        // Pass products to the admin index view
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        // Get all collections to populate dropdown/checkboxes in the form
        $collections = Collection::all();

        // Pass collections to the create view
        return view('admin.products.create', compact('collections'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'collections' => 'nullable|array',
            'collections.*' => 'exists:collections,id',
            'category' => 'nullable|string|max:100',
            'height' => 'nullable|string|max:50',
            'width' => 'nullable|string|max:50',
            'thickness' => 'nullable|string|max:50',
        ]);
    
        // Create a unique slug from the product name
        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $count = 2;
    
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
    
        // Handle image upload if present
        $image_path = null;
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('products', 'public');
        }
    
        // تحضير بيانات المنتج
        $productData = [
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'price' => $validated['price'],
            'discount_price' => $request->input('discount_price'),
            'stock_quantity' => $validated['stock_quantity'],
            'image_path' => $image_path,
            'category' => $request->input('category'),
            'height' => $request->input('height'),
            'width' => $request->input('width'),
            'thickness' => $request->input('thickness'),
            // تعامل صريح مع خانات الاختيار
            'is_featured' => $request->has('is_featured') ? true : false,
            'is_new_arrival' => $request->has('is_new_arrival') ? true : false,
            'is_bestseller' => $request->has('is_bestseller') ? true : false,
            'is_active' => true,
        ];
    
        // إنشاء المنتج
        $product = Product::create($productData);
    
        // ربط المنتج بالمجموعات
        if ($request->has('collections')) {
            $product->collections()->attach($request->input('collections'));
        }
    
        // إعادة التوجيه بعد النجاح
        return redirect()->route('admin.products.index')
                         ->with('status', 'Product created successfully.');
    }
    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        // Eager load the collections relationship
        $product->load('collections');

        // Pass the product to the show view
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        // Get all collections for the form
        $collections = Collection::all();
        // Eager load the currently associated collections for the product
        $product->load('collections');

        // Pass the product and collections to the edit view
        return view('admin.products.edit', compact('product', 'collections'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        
            // Validate the incoming request data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'discount_price' => 'nullable|numeric|min:0|lt:price',
                'stock_quantity' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'collections' => 'nullable|array',
                'collections.*' => 'exists:collections,id',
                'category' => 'nullable|string|max:100',
                'height' => 'nullable|string|max:50',
                'width' => 'nullable|string|max:50',
                'thickness' => 'nullable|string|max:50',
            ]);
        
            // Update slug if the name changed
            if ($product->name !== $validated['name']) {
                $slug = Str::slug($validated['name']);
                $originalSlug = $slug;
                $count = 2;
        
                while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }
                $product->slug = $slug;
            }
        
            // Handle image upload if a new image is provided
            if ($request->hasFile('image')) {
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }
                $image_path = $request->file('image')->store('products', 'public');
                $product->image_path = $image_path;
            }
        
            // تحديث بيانات المنتج
            $product->name = $validated['name'];
            $product->description = $validated['description'];
            $product->price = $validated['price'];
            $product->discount_price = $request->input('discount_price');
            $product->stock_quantity = $validated['stock_quantity'];
            $product->category = $request->input('category');
            $product->height = $request->input('height');
            $product->width = $request->input('width');
            $product->thickness = $request->input('thickness');
            
            // تعامل صريح مع خانات الاختيار
            $product->is_featured = $request->has('is_featured') ? true : false;
            $product->is_new_arrival = $request->has('is_new_arrival') ? true : false;
            $product->is_bestseller = $request->has('is_bestseller') ? true : false;
        
            $product->save();
        
            // تحديث المجموعات
            if ($request->has('collections')) {
                $product->collections()->sync($request->input('collections'));
            } else {
                $product->collections()->detach();
            }
        
            return redirect()->route('admin.products.index')
                             ->with('status', 'Product updated successfully.');
        }
    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete the associated image if it exists
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        // Delete the product record from the database
        // Note: Related records (like collections pivot entries) might need handling
        // depending on foreign key constraints (cascade delete or manual detach)
        // $product->collections()->detach(); // Optionally detach all collections first
        $product->delete();

        // Redirect to the index page with a success message
        return redirect()->route('admin.products.index')
                         ->with('status', 'Product deleted successfully.'); // English status message
    }
    public function exportPDF()
    {
        $products = Product::all();
        $pdf = PDF::loadView('admin.products.export.pdf', compact('products'));
        
        return $pdf->download('products.pdf');
    }
    
    /**
     * Export products to Word
     */
    public function exportWord()
    {
        $products = Product::all();
        
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        
        // Add title
        $section->addText('Products List', ['bold' => true, 'size' => 16]);
        $section->addTextBreak();
        
        // Create table
        $table = $section->addTable(['borderSize' => 1, 'borderColor' => '000000']);
        
        // Add header row
        $table->addRow();
        $table->addCell(2000)->addText('Name', ['bold' => true]);
        $table->addCell(2000)->addText('Price (JOD)', ['bold' => true]);
        $table->addCell(2000)->addText('Discount', ['bold' => true]);
        $table->addCell(2000)->addText('Stock', ['bold' => true]);
        
        // Add data rows
        foreach ($products as $product) {
            $table->addRow();
            $table->addCell(2000)->addText($product->name);
            $table->addCell(2000)->addText(number_format($product->price, 2));
            $table->addCell(2000)->addText($product->discount_price ? number_format($product->discount_price, 2) : 'None');
            $table->addCell(2000)->addText($product->stock_quantity);
        }
        
        // Save file to server temporarily and return for download
        $filename = 'products.docx';
        $tempPath = storage_path('app/public/temp/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }
        
        $phpWord->save($tempPath);
        
        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }
    
    /**
     * Export products to Excel
     */
    public function exportExcel()
    {
        $products = Product::all();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'Price (JOD)');
        $sheet->setCellValue('C1', 'Discount Price');
        $sheet->setCellValue('D1', 'Stock Quantity');
        $sheet->setCellValue('E1', 'Featured');
        $sheet->setCellValue('F1', 'New Arrival');
        $sheet->setCellValue('G1', 'Bestseller');
        
        // Add data
        $row = 2;
        foreach ($products as $product) {
            $sheet->setCellValue('A' . $row, $product->name);
            $sheet->setCellValue('B' . $row, number_format($product->price, 2));
            $sheet->setCellValue('C' . $row, $product->discount_price ? number_format($product->discount_price, 2) : 'None');
            $sheet->setCellValue('D' . $row, $product->stock_quantity);
            $sheet->setCellValue('E' . $row, $product->is_featured ? 'Yes' : 'No');
            $sheet->setCellValue('F' . $row, $product->is_new_arrival ? 'Yes' : 'No');
            $sheet->setCellValue('G' . $row, $product->is_bestseller ? 'Yes' : 'No');
            $row++;
        }
        
        // Format the columns to auto size
        foreach(range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Create file
        $writer = new Xlsx($spreadsheet);
        $filename = 'products.xlsx';
        $tempPath = storage_path('app/public/temp/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }
        
        $writer->save($tempPath);
        
        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }
}



