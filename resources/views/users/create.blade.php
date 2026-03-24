<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - RBAC PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Create New User</h1>
            <a href="{{ route('users.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Users</a>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('name') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Assign Roles & Scopes</h3>
                        <div class="space-y-4">
                            @foreach($roles as $role)
                                <div class="border rounded-lg p-4 bg-gray-50">
                                    <div class="flex items-center justify-between mb-4">
                                        <label class="flex items-center space-x-3 cursor-pointer">
                                            <input type="checkbox" name="role_assignments[{{ $role->id }}][enabled]" class="rounded text-indigo-600 focus:ring-indigo-500 role-toggle" data-role-id="{{ $role->id }}">
                                            <span class="text-base font-bold text-gray-900">{{ $role->name }}</span>
                                        </label>
                                        <span class="text-xs text-gray-500 italic">Select modules to restrict this role, or leave empty for global access.</span>
                                    </div>
                                    
                                    <div id="scope-{{ $role->id }}" class="hidden pl-8">
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Module Restriction (Optional)</label>
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                            @foreach($modules as $module)
                                                <label class="flex items-center space-x-2 p-2 bg-white border rounded hover:border-indigo-300 cursor-pointer transition-colors">
                                                    <input type="checkbox" name="role_assignments[{{ $role->id }}][modules][]" value="{{ $module->id }}" class="rounded text-indigo-600">
                                                    <span class="text-xs text-gray-700">{{ $module->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg shadow hover:bg-indigo-700 transition">
                            Create User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.role-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const scopeDiv = document.getElementById('scope-' + this.dataset.roleId);
                if (this.checked) {
                    scopeDiv.classList.remove('hidden');
                } else {
                    scopeDiv.classList.add('hidden');
                    scopeDiv.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                }
            });
        });
    </script>
</body>
</html>
