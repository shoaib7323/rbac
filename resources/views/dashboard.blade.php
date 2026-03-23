<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RBAC PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-indigo-600">
            <h1 class="text-2xl font-bold mb-4">Welcome to Your Dashboard!</h1>
            <p class="text-gray-600 mb-6">You are successfully logged in to the RBAC System.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('roles.index') }}" class="block p-6 bg-indigo-50 rounded-lg border border-indigo-100 hover:bg-indigo-100 transition">
                    <h2 class="text-lg font-bold text-indigo-900 mb-2">Manage Roles & Permissions &rarr;</h2>
                    <p class="text-sm text-indigo-700">Assign granular permissions (module, feature, action) to any role.</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
