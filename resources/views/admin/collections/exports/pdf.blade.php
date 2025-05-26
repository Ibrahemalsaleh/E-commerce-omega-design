<!-- resources/views/admin/collections/exports/pdf.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Collections Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .date {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 30px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Collections Report</h1>
    <div class="date">Generated on: {{ now()->format('Y-m-d H:i') }}</div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Collection Name</th>
                <th>Category</th>
                <th>Products</th>
                <th>Featured</th>
                <th>New</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($collections as $collection)
            <tr>
                <td>{{ $collection->id }}</td>
                <td>{{ $collection->name }}</td>
                <td>{{ $collection->category ?? 'Not categorized' }}</td>
                <td>{{ $collection->products_count }}</td>
                <td>{{ $collection->is_featured ? 'Yes' : 'No' }}</td>
                <td>{{ $collection->is_new ? 'Yes' : 'No' }}</td>
                <td>{{ $collection->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        This is an automatically generated report.
    </div>
</body>
</html>