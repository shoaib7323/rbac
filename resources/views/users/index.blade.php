<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - RBAC PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
            <div class="flex space-x-4">
                <a href="{{ route('roles.index') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold p-2">Manage Roles</a>
                <a href="{{ route('users.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">Add User</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @foreach($user->roles->groupBy('id') as $roleGroupId => $roleEntries)
                                    <div class="mb-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $roleEntries->first()->name }}
                                        </span>
                                        <div class="text-[10px] text-gray-400 mt-1 ml-2">
                                            @php
                                                $modules = $roleEntries->map(function($r) {
                                                    return $r->pivot->module_id ? \App\Models\Module::find($r->pivot->module_id)?->name : 'Global';
                                                })->unique()->filter()->implode(', ');
                                            @endphp
                                            {{ $modules ?: 'Global' }}
                                        </div>
                                    </div>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
