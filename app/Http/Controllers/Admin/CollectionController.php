<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// Add these new imports for export functionality
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpWord\Style\Font;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CollectionsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::withCount('products')->latest()->get();
        return view('admin.collections.index', compact('collections'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->get();
        return view('admin.collections.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:collections',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'nullable|string|max:255',
            'is_featured' => 'sometimes',
            'is_new' => 'sometimes',
        ]);

        // Generate slug
        $slug = Str::slug($validated['name']);

        // استخدام نفس منطق المنتجات لتحميل الصورة
        $image_path = null;
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('collections', 'public');
        }

        // Create the collection
        $collection = Collection::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'image_path' => $image_path,
            'category' => $validated['category'] ?? null,
            'is_featured' => $request->has('is_featured'),
            'is_new' => $request->has('is_new'),
            'is_active' => true,
        ]);

        // Attach products if any were selected
        if ($request->has('products')) {
            $collection->products()->attach($request->products);
        }

        return redirect()->route('admin.collections.index')
            ->with('success', 'Collection created successfully');
    }

    public function show(Collection $collection)
    {
        return view('admin.collections.show', compact('collection'));
    }

    public function edit(Collection $collection)
    {
        $products = Product::where('is_active', true)->get();
        return view('admin.collections.edit', compact('collection', 'products'));
    }

    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:collections,name,' . $collection->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'nullable|string|max:255',
            'is_featured' => 'sometimes',
            'is_new' => 'sometimes',
        ]);

        // Generate slug if name changed
        if ($collection->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // استخدام نفس منطق المنتجات لتحديث الصورة
        if ($request->hasFile('image')) {
            if ($collection->image_path) {
                Storage::disk('public')->delete($collection->image_path);
            }
            $image_path = $request->file('image')->store('collections', 'public');
            $collection->image_path = $image_path;
        }

        // Update the collection
        $collection->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? $collection->slug,
            'description' => $validated['description'] ?? $collection->description,
            'category' => $validated['category'] ?? $collection->category,
            'is_featured' => $request->has('is_featured'),
            'is_new' => $request->has('is_new'),
        ]);

        // Sync products - properly handles both attaching and detaching
        if ($request->has('products')) {
            $collection->products()->sync($request->products);
        } else {
            $collection->products()->detach();
        }

        return redirect()->route('admin.collections.index')
            ->with('success', 'Collection updated successfully');
    }

    public function destroy(Collection $collection)
    {
        // استخدام نفس منطق المنتجات لحذف الصورة
        if ($collection->image_path) {
            Storage::disk('public')->delete($collection->image_path);
        }

        // Detach all products before deleting
        $collection->products()->detach();
        
        // Delete the collection
        $collection->delete();

        return redirect()->route('admin.collections.index')
            ->with('success', 'Collection deleted successfully');
    }

    public function products(Collection $collection)
    {
        $products = $collection->products()->paginate(20);
        return view('admin.collections.products', compact('collection', 'products'));
    }

    /**
     * Export collections to PDF
     */
    public function exportPDF()
    {
        $collections = Collection::withCount('products')->latest()->get();
        $pdf = PDF::loadView('admin.collections.exports.pdf', compact('collections'));
        return $pdf->download('collections-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export collections to Word document
     */
    public function exportWord()
    {
        $collections = Collection::withCount('products')->latest()->get();
        
        // Create new Word document
        $phpWord = new PhpWord();
        
        // Add styles
        $titleStyle = [
            'bold' => true,
            'size' => 16,
            'name' => 'Arial',
        ];
        
        $headerStyle = [
            'bold' => true,
            'size' => 12,
            'name' => 'Arial',
        ];
        
        $textStyle = [
            'size' => 10,
            'name' => 'Arial',
        ];
        
        // Add title page
        $section = $phpWord->addSection();
        $section->addText('Collections Report', $titleStyle, ['alignment' => 'center']);
        $section->addText('Generated on: ' . now()->format('Y-m-d H:i'), $textStyle, ['alignment' => 'center']);
        $section->addTextBreak(2);
        
        // Create collections table
        $table = $section->addTable([
            'borderSize' => 1,
            'borderColor' => '000000',
            'width' => 100 * 50,
            'unit' => 'pct',
            'alignment' => 'center',
        ]);
        
        // Add header row
        $table->addRow();
        $table->addCell(800)->addText('ID', $headerStyle);
        $table->addCell(2000)->addText('Collection Name', $headerStyle);
        $table->addCell(1200)->addText('Category', $headerStyle);
        $table->addCell(1000)->addText('Products', $headerStyle);
        $table->addCell(1000)->addText('Featured', $headerStyle);
        $table->addCell(1000)->addText('New', $headerStyle);
        $table->addCell(1400)->addText('Created At', $headerStyle);
        
        // Add data rows
        foreach ($collections as $collection) {
            $table->addRow();
            $table->addCell(800)->addText($collection->id, $textStyle);
            $table->addCell(2000)->addText($collection->name, $textStyle);
            $table->addCell(1200)->addText($collection->category ?? 'Not categorized', $textStyle);
            $table->addCell(1000)->addText($collection->products_count, $textStyle);
            $table->addCell(1000)->addText($collection->is_featured ? 'Yes' : 'No', $textStyle);
            $table->addCell(1000)->addText($collection->is_new ? 'Yes' : 'No', $textStyle);
            $table->addCell(1400)->addText($collection->created_at->format('Y-m-d'), $textStyle);
        }
        
        // Create Word file
        $filename = 'collections-' . now()->format('Y-m-d') . '.docx';
        $objWriter = WordIOFactory::createWriter($phpWord, 'Word2007');
        
        // Save to storage and download
        $tempFile = storage_path('app/public/temp/' . $filename);
        $dir = storage_path('app/public/temp');
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        
        $objWriter->save($tempFile);
        
        return response()->download($tempFile, $filename)->deleteFileAfterSend();
    }

    /**
     * Export collections to Excel
     */
    public function exportExcel()
    {
        return Excel::download(new CollectionsExport, 'collections-' . now()->format('Y-m-d') . '.xlsx');
    }
}
