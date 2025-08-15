@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    
@if (session('success-delete'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Item Deleted Successfully',
        text: '{{ session('success') }}'
    });
</script>
 @endif 
@if (session('success-item-deted'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Item Selected Deleted Successfully',
        text: '{{ session('success') }}'
    });
</script>
 @endif   
@if (session('success-item'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Item Created Successfully',
        text: '{{ session('success') }}'
    });
</script>
@endif
@if (session('successupdateitems'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Item Updated Successfully',
            text: '{{ session('success') }}'
        });
    </script>
@endif
<h1 class="text-2xl font-bold mb-4">POS - Item List</h1>

   <!-- Add Item Form -->
<form  enctype="multipart/form-data" action="{{ route('items.store') }}" method="POST" class="mb-6">
    @csrf
    <div class="mb-2">
        <label for="image">Item Image</label>
        <input type="file" name="image" class="form-control">
    </div>   
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
         <div class="col-start-1 col-end-7">
            <input type="text" name="name" placeholder="Item Name" required class="border p-2 rounded w-full">
        </div>
        <div class="col-span-2">
            <input type="text" name="barcode" placeholder="Barcode" required class="border p-2 rounded w-full">
        </div>
         <input type="number" name="price" placeholder="Price" required class="border p-2 rounded w-full">
            <input type="number" name="quantity" placeholder="Quantity" required class="border p-2 rounded w-full">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Add Item
            </button>
  
       
    </div>
</form>

    <!-- Barcode Scanner Input -->
    {{-- <div class="mb-6">
        <input type="text" id="barcodeInput" placeholder="Scan Barcode..." autofocus
            class="border p-2 w-full rounded" onkeyup="searchBarcode(this.value)">
    </div> --}}
        {{-- <div class="flex justify-end mb-4">
            <form id="deleteSelectedForm" action="{{ route('items.deleteSelected') }}" method="POST">
                @csrf
                @method('DELETE')
                <span class="flex justify-between mb-4">
                <a  href="#"  onclick="confirmSelectedDelete()">
                        🗑️ Delete Selected
                </a>
                </span>
         </div> 
             <!-- 🔍 Search Form -->
             <div class="flex justify-end mb-4">
                <form method="GET" action="{{ route('items.index') }}" class="mb-4">
                    <input type="text" name="search" value="{{ $search }}" placeholder="🔎 Search by name or barcode..."
                    class="border border-gray-300 px-3 py-2 rounded w-full max-w-sm">
                </form>
              </div>
           --}}

           <div class="flex justify-between items-center mb-4">
                {{-- 🗑️ Delete Selected on the left --}}
            <div>
                 <form id="deleteSelectedForm" action="{{ route('items.deleteSelected') }}" method="POST">
                    @csrf
                    @method('DELETE')
                <span  onclick="confirmSelectedDelete()"
                        class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded shadow transition">
                   <span>🗑️ Delete Selected</span>
                </span>
            </div>
             {{-- 🔍 Search box on the right --}}
                <div>
                    <form method="GET" action="{{ route('items.index') }}" class="mb-4">
                        <input type="text" placeholder="🔎 Search by name or barcode" class="border border-gray-300 px-3 py-2 rounded w-full max-w-xs">
                    </form>
                </div>
            </div>
            <!-- Item List -->
            <div class="overflow-x-auto">
               <table id="itemsTable" class="display nowrap w-full">
                        <thead class="bg-gray-200 text-sm">
                  <tr>
                    <th class="px-2 py-2 border text-center">
                        <input type="checkbox" onclick="toggleAll(this)">
                    </th>
                    <th class="p-2 border">ID</th>
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
                    <td class="px-2 py-2 border text-center">
                            <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="item-checkbox">
                    </td>
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
 
         <!-- 📄 Pagination -->
        <div class="mt-4">
            {{ $items->withQueryString()->links() }}
        </div>
    </form>
  </div>

        <!-- ⚠️ Confirmation Modal -->
        <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
            <div class="bg-white rounded p-6 w-full max-w-md shadow">
                <h3 class="text-lg font-semibold mb-2">Confirm Deletion</h3>
                <p>Are you sure you want to delete the selected items?</p>
                <div class="flex justify-end gap-2 mt-4">
                    <button onclick="hideModal()" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                    <button onclick="submitDeleteSelected()" class="bg-red-600 text-white px-4 py-2 rounded">Yes, Delete</button>
                </div>
            </div>
        </div> <!-- ⚠️ END Confirmation Modal -->
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
        
        function toggleAll(source) {
            document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = source.checked);
        }

        function confirmSelectedDelete() {
            const checked = document.querySelectorAll('.item-checkbox:checked');
            if (checked.length === 0) {
                alert('Please select at least one item to delete.');
                return;
            }
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function hideModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        function submitDeleteSelected() {
            document.getElementById('deleteSelectedForm').submit();
        }
    
    </script>
@endsection

 