<?php use Illuminate\Support\Facades\Auth; ?>

<nav class="bg-white border-b border-gray-200 px-4 py-3 shadow-md sticky top-0 z-50">
    <div class="flex justify-between items-center">
        <!-- Logo / Title -->
        <div class="flex items-center gap-2">
            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2M5 11h14M5 11V7a2 2 0 012-2h10a2 2 0 012 2v4" />
            </svg>
            <span class="font-bold text-xl text-gray-800">POS System</span>
        </div>

        <!-- Links -->
        <div class="hidden md:flex items-center gap-6 text-sm text-gray-700">
            <a href="{{ route('items.index') }}" class="hover:text-green-600 font-medium"><svg xmlns="http://www.w3.org/2000/svg" class="hover:text-green-600" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M280-640q-33 0-56.5-23.5T200-720v-80q0-33 23.5-56.5T280-880h400q33 0 56.5 23.5T760-800v80q0 33-23.5 56.5T680-640H280Zm0-80h400v-80H280v80ZM160-80q-33 0-56.5-23.5T80-160v-40h800v40q0 33-23.5 56.5T800-80H160ZM80-240l139-313q10-22 30-34.5t43-12.5h376q23 0 43 12.5t30 34.5l139 313H80Zm260-80h40q8 0 14-6t6-14q0-8-6-14t-14-6h-40q-8 0-14 6t-6 14q0 8 6 14t14 6Zm0-80h40q8 0 14-6t6-14q0-8-6-14t-14-6h-40q-8 0-14 6t-6 14q0 8 6 14t14 6Zm0-80h40q8 0 14-6t6-14q0-8-6-14t-14-6h-40q-8 0-14 6t-6 14q0 8 6 14t14 6Zm120 160h40q8 0 14-6t6-14q0-8-6-14t-14-6h-40q-8 0-14 6t-6 14q0 8 6 14t14 6Zm0-80h40q8 0 14-6t6-14q0-8-6-14t-14-6h-40q-8 0-14 6t-6 14q0 8 6 14t14 6Zm0-80h40q8 0 14-6t6-14q0-8-6-14t-14-6h-40q-8 0-14 6t-6 14q0 8 6 14t14 6Zm120 160h40q8 0 14-6t6-14q0-8-6-14t-14-6h-40q-8 0-14 6t-6 14q0 8 6 14t14 6Zm0-80h40q8 0 14-6t6-14q0-8-6-14t-14-6h-40q-8 0-14 6t-6 14q0 8 6 14t14 6Zm0-80h40q8 0 14-6t6-14q0-8-6-14t-14-6h-40q-8 0-14 6t-6 14q0 8 6 14t14 6Z"/></svg> POS</a>
            <a href="{{ route('cart.view') }}" class="hover:text-green-600 font-medium"> <svg xmlns="http://www.w3.org/2000/svg" class="hover:text-green-600" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Cart</a>
            <a href="{{ route('sales.index') }}" class="hover:text-green-600 font-medium"><svg xmlns="http://www.w3.org/2000/svg" class="hover:text-green-600" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M856-390 570-104q-12 12-27 18t-30 6q-15 0-30-6t-27-18L103-457q-11-11-17-25.5T80-513v-287q0-33 23.5-56.5T160-880h287q16 0 31 6.5t26 17.5l352 353q12 12 17.5 27t5.5 30q0 15-5.5 29.5T856-390ZM513-160l286-286-353-354H160v286l353 354ZM260-640q25 0 42.5-17.5T320-700q0-25-17.5-42.5T260-760q-25 0-42.5 17.5T200-700q0 25 17.5 42.5T260-640Zm220 160Z"/></svg> Sales</a>
            <a href="{{ route('sales.summary') }}" class="hover:text-green-600 font-medium"> <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M200-120q-33 0-56.5-23.5T120-200v-640h80v640h640v80H200Zm40-120v-360h160v360H240Zm200 0v-560h160v560H440Zm200 0v-200h160v200H640Z"/></svg> Sales Summary</a>
            {{-- <a href="{{ route('sales.output') }}" class="hover:text-green-600 font-medium">📊 All Summary</a> --}}
            <a href="{{ route('admin.dashboard') }}" class="hover:text-green-600 font-medium"> <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M680-280q25 0 42.5-17.5T740-340q0-25-17.5-42.5T680-400q-25 0-42.5 17.5T620-340q0 25 17.5 42.5T680-280Zm0 120q31 0 57-14.5t42-38.5q-22-13-47-20t-52-7q-27 0-52 7t-47 20q16 24 42 38.5t57 14.5ZM480-80q-139-35-229.5-159.5T160-516v-244l320-120 320 120v227q-19-8-39-14.5t-41-9.5v-147l-240-90-240 90v188q0 47 12.5 94t35 89.5Q310-290 342-254t71 60q11 32 29 61t41 52q-1 0-1.5.5t-1.5.5Zm200 0q-83 0-141.5-58.5T480-280q0-83 58.5-141.5T680-480q83 0 141.5 58.5T880-280q0 83-58.5 141.5T680-80ZM480-494Z"/></svg> Admin</a>
            <a href="{{ route('admin.dashboard') }}" class="hover:text-green-600 font-medium"> <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M320-480v-80h320v80H320Zm0-160v-80h320v80H320Zm-80 240h300q29 0 54 12.5t42 35.5l84 110v-558H240v400Zm0 240h442L573-303q-6-8-14.5-12.5T540-320H240v160Zm480 80H240q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h480q33 0 56.5 23.5T800-800v640q0 33-23.5 56.5T720-80Zm-480-80v-640 640Zm0-160v-80 80Z"/></svg> Reports</a>
            <a href="{{ route('item_histories.index') }}" class="hover:text-green-600 font-medium"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M120-560v-240h80v94q51-64 124.5-99T480-840q150 0 255 105t105 255h-80q0-117-81.5-198.5T480-760q-69 0-129 32t-101 88h110v80H120Zm2 120h82q12 93 76.5 157.5T435-204l48 84q-138 0-242-91.5T122-440Zm412 70-94-94v-216h80v184l56 56-42 70ZM719 0l-12-60q-12-5-22.5-10.5T663-84l-58 18-40-68 46-40q-2-13-2-26t2-26l-46-40 40-68 58 18q11-8 21.5-13.5T707-340l12-60h80l12 60q12 5 23 11.5t21 14.5l58-20 40 70-46 40q2 13 2 25t-2 25l46 40-40 68-58-18q-11 8-21.5 13.5T811-60L799 0h-80Zm40-120q33 0 56.5-23.5T839-200q0-33-23.5-56.5T759-280q-33 0-56.5 23.5T679-200q0 33 23.5 56.5T759-120Z"/></svg>Item History </a>
        </div>  

        <!-- User Dropdown -->
       <!-- User Dropdown -->
        <div class="relative">
            <button id="userMenuButton" class="flex items-center gap-2 bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                <span>{{ Auth::user()->name ?? 'Cashier' }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white rounded shadow-lg hidden">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">👤 Profile</a>
                <a href="{{ route('cashiers.create') }}"class="block px-4 py-2 text-gray-700 hover:bg-gray-100">👤 Create Cashier</a>
                <a href="{{ route('profile.change-password') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">🔑 Change Password</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-100">🚪 Logout</button>
                </form>
            </div>
        </div>

    </div>
</nav>