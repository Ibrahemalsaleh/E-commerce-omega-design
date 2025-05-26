<?php

namespace App\Exports;

use App\Models\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CollectionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Collection::withCount('products')->latest()->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Collection Name',
            'Category',
            'Products Count',
            'Featured',
            'New',
            'Created At',
        ];
    }

    /**
     * @param Collection $collection
     * @return array
     */
    public function map($collection): array
    {
        return [
            $collection->id,
            $collection->name,
            $collection->category ?? 'Not categorized',
            $collection->products_count,
            $collection->is_featured ? 'Yes' : 'No',
            $collection->is_new ? 'Yes' : 'No',
            $collection->created_at->format('Y-m-d'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
            1 => ['font' => ['bold' => true]],
        ];
    }
}