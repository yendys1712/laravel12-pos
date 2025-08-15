@extends('layouts.app')

@section('content')
@if (session('success-item'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Item Created Successfully',
        text: '{{ session('success') }}'
    });
</script>
@endif
@if (session('success-item-bulk'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Bulk Item Created Successfully',
        text: '{{ session('success') }}'
    });
</script>
@endif

@if (session('error_item_bulk_failed'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Bulk Item Failed to Create',
        html: '{{ session('error_item_bulk_failed') }}'
    });
</script>
@endif
@if (session('success-item-uodated'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Item updated successfully.',
        text: '{{ session('success') }}'
    });
</script>
@endif
@if (session('success-delete'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Item Deleted Successfully',
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
<div class="container mt-4">
  <div class="flex justify-between items-center mb-4">
   <h1 class="text-2xl font-bold mb-4">POS - Item List</h1>
   </div>

     <a href="{{ route('items.scan_add') }}" class="hover:text-green-600 font-medium">📦 Add Item via Barcode</a>
     <br>
     <a href="{{ route('items.import_items') }}" class="hover:text-green-600 font-medium">📦 Import Items from CSV/Excel</a>


    <!-- Add Item Form -->
    <form  enctype="multipart/form-data" action="{{ route('items.store') }}" method="POST" class="mb-6">
        @csrf
        <div class="mb-3">
            <label for="image">Item Image</label>
            <input type="file" name="image" class="form-control">
        </div>   
       
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="col-start-1 col-end-7 rounded ">
                <input type="text" name="name" placeholder="Item Name" required class="border p-2 rounded  w-full">
            </div>
            <div class="col-span-2 rounded ">
                <input type="text" name="barcode" placeholder="Barcode" required class="border p-2 rounded  w-full">
            </div>
            <input type="number" name="price" placeholder="Price" required class="border p-2 rounded  w-full">
                <input type="number" name="quantity" placeholder="Quantity" required class="border p-2 rounded  w-full">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded  hover:bg-green-700">
                    ADD ITEM
                </button>
    
        
        </div>
    </form>
   {{-- min-w-full bg-white border bg-gray-100  table table-bordered" --}}
       <table id="itemTable" class="w-full border text-sm shadow ">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-3 py-2 border text-center">Image</th>
                    <th class="px-3 py-2 border text-center">Item Name</th>
                    <th class="px-3 py-2 border text-center">Barcode</th>
                    <th class="px-3 py-2 border text-right">Price</th>
                    <th class="px-3 py-2 border text-center">Qty</th>
                    <th class="px-3 py-2 border text-center">Added Date</th>
                    <th class="px-3 py-2 border text-center">Action</th>
                </tr>
            </thead>
            <tbody id="item-table-body">
                
            </tbody>
        </table>
              <!-- Edit Modal -->
                <div class="modal fade" id="editItemModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">

                    <form id="editItemForm"  enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('POST') <!-- Needed for Laravel PUT -->
                            <div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title">Edit Item</h5></div>
                            <div class="modal-body">
                                <input type="hidden" id="edit-id">
                                <div class="mb-3">
                                <label>Name</label>
                                <input type="text" id="edit-name" name="name" class="form-control">
                                </div>
                                <div class="mb-3">
                                <label>Barcode</label>
                                <input type="text" id="edit-barcode" name="barcode" class="form-control">
                                </div>
                                <div class="mb-3">
                                <label>Price</label>
                                <input type="number" id="edit-price" name="price"  class="form-control">
                                </div>
                                <div class="mb-3">
                                <label>Quantity</label>
                                <input type="number" id="edit-quantity"  name="quantity"  class="form-control">
                                </div>
                                 <!-- Image Upload -->
                                <div class="mb-3">
                                    <label>Image</label>
                                    <input type="file" id="edit-image" name="image" class="form-control" accept="image/*">
                                    <img id="current-image" src="#" class="mt-2" style="max-width: 150px; display: none;" />
                                </div>
                            </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Cancel</button>
                            <button type="submit" class="btn btn-primary"> 💾 Save</button>
                      </div>
                    </div>
                 </form>
                </div>
            </div>

        <!-- ⚠️ delete Confirmation Modal -->
            <div class="modal fade" id="deleteItemModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Edit Item</h5></div>
                    <div class="modal-body">
                           <h3 class="text-lg font-semibold mb-2">Confirm Deletion</h3>
                         <p>Are you sure you want to delete the selected items?</p>
                    </div>
                    <div class="modal-footer">
                          <button onclick="" class="bg-gray-300 px-4 py-2 rounded"  data-bs-dismiss="modal">Cancel</button>
                        <button onclick="deleteConfirmation(id)" class="bg-red-600 text-white px-4 py-2 rounded">Yes, Delete</button>
                    </div>
                    </div>
                </div>
            </div>  <!-- ⚠️ END Confirmation Modal -->


</div>
@endsection


 @push('scripts')

 <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">
         
        $(function() {
            $('#itemTable').DataTable({
                stripeClasses: ['even:bg-gray-100', 'odd:bg-white'],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route('items.data') }}',
                columns: [
                    { data: 'image', name: 'image', orderable: false, searchable: false },
                    { data: 'name' },
                    { data: 'barcode' },
                    { data: 'price' },
                    { data: 'quantity' },
                    { data: 'created_at',
                        render: function(data, type, row) {
                                if(data == null){
                                    return 'No date and time';
                                }else{
                                    return dayjs(data).format('MMMM D, YYYY h:mm A');
                                }   
                       }
                     },
                    { data: 'action', orderable: false, searchable: false },
                ],
               dom: 'Bfrtip',
                buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    className: 'bg-green-600 text-white px-3 py-1 rounded'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Export to PDF',
                    className: 'bg-red-600 text-white px-3 py-1 rounded'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'bg-blue-600 text-white px-3 py-1 rounded'
                }
                ],
                createdRow: function (row, data, dataIndex) {
                    $('td', row).addClass('border px-3 py-2 hover:bg-gray-50');
                }
            });
        });

            // Load item details into modal
        function editItem(id) {
              $.get('/items/' + id, function (data) {
                $('#edit-id').val(data.id);
                $('#edit-name').val(data.name);
                $('#edit-price').val(data.price);
                $('#edit-quantity').val(data.quantity);
                $('#edit-barcode').val(data.barcode);

                if (data.image_url) {
                    $('#current-image').attr('src', data.image_url).show();
                }

                $('#editItemForm').attr('action', '/items/' + id);
                $('#editItemModal').modal('show');
            });
        }

        // Submit form via AJAX with FormData (including image)
        $(document).ready(function () {
            $('#editItemForm').submit(function (e) {
                e.preventDefault();

                const actionUrl = $(this).attr('action');
                const formData = new FormData(this);

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                     headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    success: function (response) {
                         $('#editItemModal').modal('hide');
                         Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: response.message || 'Item updated successfully!',
                            text: 'Reloading...',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 2000); // match timer above
                       
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let messages = Object.values(errors).flat().join('\n');
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: messages
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Update Failed',
                                text: 'An unexpected error occurred.'
                            });
                        }
                    }
                });
            });
        });
                    
    
        function deleteItem(id) {
                if (!confirm("Are you sure you want to delete this item?")) return;
                const appUrl = document.querySelector('meta[name="app-url"]').getAttribute('content');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const url = `${appUrl}/items/${id}`;

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        alert(response.message);
                        // Optionally remove the row or refresh table
                        location.reload(); // or use DataTable .ajax.reload()
                    },
                    error: function(xhr) {
                        console.error("Delete failed:", xhr.status, xhr.responseText);
                        alert("Failed to delete item.");
                    }
                });
        }
       
</script>
@endpush

