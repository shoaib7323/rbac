<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles - RBAC PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Role & Permissions Management</h1>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-gray-600 hover:text-red-600 text-sm font-semibold">Logout</button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Role List -->
            <div class="lg:col-span-1 bg-white shadow rounded-lg p-6 h-fit">
                <h2 class="text-xl font-bold mb-4">Existing Roles</h2>
                <div class="space-y-2">
                    @foreach($roles as $role)
                        <div class="p-3 bg-gray-50 rounded flex justify-between items-center group">
                            <span class="font-semibold">{{ $role->name }}</span>
                            @if($role->is_predefined)
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Predefined</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Permission Matrix -->
            <div class="lg:col-span-2 bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Create New Role</h2>
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700">Role Name</label>
                        <input type="text" name="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                    </div>

                    <h3 class="font-bold text-lg mb-4 text-indigo-700 border-b pb-2">Permissions Matrix</h3>
                    <div class="space-y-8">
                        @foreach($permissions->groupBy('module') as $module => $modPermissions)
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2 uppercase text-sm tracking-wider">{{ $module }}</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach($modPermissions as $permission)
                                        <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-gray-50 rounded transition-colors border border-gray-100">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="rounded text-indigo-600">
                                            <span class="text-sm text-gray-700">{{ $permission->feature }}: {{ $permission->action }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition">
                            Create Role & Assign Permissions
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
