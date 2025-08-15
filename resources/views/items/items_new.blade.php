@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">


  <!-- Item List -->
            <div class="p-4 bg-white rounded-lg shadow">
                <input type="text" id="barcodeSearch" placeholder=" 🔎 Search by barcode" class="border p-2 rounded mb-4 w-full" />
                {{-- <label for="barcodeSearch" class="block mb-2 text-sm font-medium">🔎 Search by Barcode:</label>
                <input type="text" id="barcodeSearch" class="border p-2 rounded w-full mb-4" placeholder="Enter barcode..."> --}}

                <div class="overflow-x-auto">
               {{-- <table class="w-full text-left border"> --}}
                {{-- <table id="itemsTable" class="display nowrap w-full text-sm" style="width:100%"> --}}
                {{-- <table id="itemsTable" class="min-w-full border text-sm text-left text-gray-600"> --}}
              <table id="itemsTable" class="display nowrap w-full">
                        <thead class="bg-gray-200 text-sm">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Image</th>
                    <th class="p-2 border">Item</th>
                    <th class="p-2 border">Barcode</th>
                    <th class="p-2 border">Price</th>
                    <th class="p-2 border">Current Quantity</th>
                    <th class="p-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr>
                    <td class="p-2 border">{{ $loop->iteration }}</td>
                    <td class="text-center">
                        @if ($item->image)
                            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" style="height: 50px;" class="rounded">
                        @else
                            <small class="text-muted">No image</small>
                        @endif
                    </td>
                    <td class="p-2 border">{{ $item->name }}</td>
                    <td class="p-2 border">{{ $item->barcode }}</td>
                    <td class="p-2 border">₱{{ number_format($item->price, 2) }}</td>
                    <td class="p-2 border">{{ $item->quantity }}</td>
                    <td class="p-2 border">
                       <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">✏️ Edit</button>
                        <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                            onsubmit="return confirm('Delete this item?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                      </td>
                    </td>
                </tr>
                 {{-- ✅ Edit Modal --}}
                    <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" enctype="multipart/form-data" action="{{ route('items.update', $item->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editItemLabel{{ $item->id }}">Edit Item</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                            <div class="mb-2">
                                            <label>Current Image:</label><br>
                                            @if ($item->image)
                                                <img src="{{ asset($item->image) }}" alt="Image" style="height: 60px;" class="rounded shadow mb-2">
                                            @else
                                                <p class="text-muted">No image</p>
                                            @endif
                                        </div>
                                            <div class="mb-2">
                                            <label>Upload New Image (optional):</label>
                                            <input type="file" name="image" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label>Item Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Price (₱)</label>
                                            <input type="number" name="price" class="form-control" value="{{ $item->price }}" step="0.01" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Quantity</label>
                                            <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Barcode</label>
                                            <input type="text" name="barcode" class="form-control" value="{{ $item->barcode }}">
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">💾 Save</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> <!--end of modal-->
              @endforeach
            </tbody>
        </table>   
       </div>
    </div>
     <script>
            $(document).ready(function () {
                let table = $('#itemsTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    dom: 'Bfrtip',
                    buttons: [
                        'excelHtml5', 'csvHtml5', 'pdfHtml5'
                    ],
                    language: {
                        paginate: {
                            previous: "‹",
                            next: "›"
                        },
                        search: "Search table:"
                    }
                });

                // Live barcode search (1st column)
                $('#barcodeSearch').on('keyup', function () {
                    table.column(0).search(this.value).draw();
                });
            });
    </script>

</div>
@endsection