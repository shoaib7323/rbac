<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles - RBAC PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .module-section:nth-child(even) { background-color: #f9fafb; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Role & Permissions Management</h1>
            <div class="flex space-x-4">
                <a href="{{ route('users.index') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold p-2">Manage Users</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-red-600 text-sm font-semibold p-2">Logout</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Role List -->
            <div class="lg:col-span-1 bg-white shadow rounded-lg p-6 h-fit">
                <h2 class="text-xl font-bold mb-4">Existing Roles</h2>
                <div class="space-y-2">
                    @foreach($roles as $role)
                        <div class="p-3 bg-gray-50 rounded flex justify-between items-center group">
                            <div>
                                <span class="font-semibold">{{ $role->name }}</span>
                                @if($role->is_predefined)
                                    <span class="block text-[10px] uppercase tracking-tighter text-blue-600 font-bold">System Role</span>
                                @endif
                            </div>
                            <button onclick="editRole({{ $role->id }}, '{{ $role->name }}', {{ $role->modules->pluck('pivot.full_access', 'id') }}, {{ $role->actions->pluck('id') }})" class="text-indigo-600 hover:text-indigo-900 text-xs font-bold">Edit</button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Permission Matrix -->
            <div class="lg:col-span-3 bg-white shadow rounded-lg p-6">
                <h2 id="form-title" class="text-xl font-bold mb-4">Create New Role</h2>
                <form id="role-form" action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700">Role Name</label>
                        <input type="text" name="name" id="role-name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border focus:ring-indigo-500">
                    </div>

                    <h3 class="font-bold text-lg mb-4 text-indigo-700 border-b pb-2 flex justify-between items-center">
                        <span>Permissions Hierarchy</span>
                        <span class="text-xs font-normal text-gray-500 italic">Select Entire Module or Specific Actions</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 min-h-[400px]">
                        <!-- Module Sidebar Navigation -->
                        <div class="md:col-span-1 border-r border-gray-100 pr-4 space-y-1" id="module-tabs">
                            @foreach($modules as $index => $module)
                                <button type="button" 
                                        onclick="switchTab({{ $module->id }})" 
                                        id="tab-{{ $module->id }}"
                                        class="module-tab w-full text-left px-4 py-3 text-sm font-bold rounded-lg transition-all {{ $index === 0 ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 border-l-4 border-transparent' }}">
                                    {{ $module->name }}
                                </button>
                            @endforeach
                        </div>
                        
                        <!-- Module Content Area -->
                        <div class="md:col-span-3" id="module-contents">
                            @foreach($modules as $index => $module)
                                <div class="module-section {{ $index === 0 ? '' : 'hidden' }}" id="mod-content-{{ $module->id }}">
                                    <div class="bg-gray-50 px-4 py-3 flex justify-between items-center border rounded-t-lg">
                                        <div class="flex items-center space-x-3">
                                            <input type="checkbox" name="modules[{{ $module->id }}][enabled]" id="mod-{{ $module->id }}-enabled" class="rounded text-indigo-600 module-enabler">
                                            <h4 class="font-bold text-gray-900 uppercase text-xs tracking-widest">{{ $module->name }}</h4>
                                        </div>
                                        <label class="flex items-center space-x-2 cursor-pointer bg-white px-3 py-1 rounded border shadow-sm hover:border-green-300">
                                            <input type="checkbox" name="modules[{{ $module->id }}][full_access]" id="mod-{{ $module->id }}-full" class="rounded text-green-600 full-access-toggle">
                                            <span class="text-[10px] font-bold text-green-700 uppercase">Full Access</span>
                                        </label>
                                    </div>
                                    
                                    <div class="p-6 border border-t-0 rounded-b-lg border-gray-200 grid grid-cols-1 sm:grid-cols-2 gap-8 actions-container" id="actions-{{ $module->id }}">
                                        @foreach($module->features as $feature)
                                            <div>
                                                <h5 class="text-[10px] font-extrabold text-indigo-400 uppercase mb-3 tracking-widest border-b border-indigo-50 pb-1">{{ $feature->name }}</h5>
                                                <div class="space-y-2">
                                                    @foreach($feature->actions as $action)
                                                        <label class="flex items-center space-x-3 cursor-pointer group">
                                                            <input type="checkbox" name="actions[]" value="{{ $action->id }}" id="action-{{ $action->id }}" class="rounded text-indigo-600 action-checkbox transition-transform group-hover:scale-110">
                                                            <span class="text-sm text-gray-600 group-hover:text-indigo-900 group-hover:font-medium transition-colors">{{ $action->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <button type="submit" class="flex-1 bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 shadow-lg transition">
                            Save Role & Permissions
                        </button>
                        <button type="button" onclick="resetForm()" class="bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-lg hover:bg-gray-300 transition">
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function switchTab(moduleId) {
            // Hide all contents
            document.querySelectorAll('.module-section').forEach(section => section.classList.add('hidden'));
            // Remove active classes from all tabs
            document.querySelectorAll('.module-tab').forEach(tab => {
                tab.classList.remove('bg-indigo-50', 'text-indigo-700', 'border-l-4', 'border-indigo-600');
                tab.classList.add('text-gray-600', 'border-transparent');
            });

            // Show current content
            document.getElementById('mod-content-' + moduleId).classList.remove('hidden');
            // Add active class to current tab
            const activeTab = document.getElementById('tab-' + moduleId);
            activeTab.classList.remove('text-gray-600', 'border-transparent');
            activeTab.classList.add('bg-indigo-50', 'text-indigo-700', 'border-l-4', 'border-indigo-600');
        }

        function editRole(id, name, fullAccessMap, actionIds) {
            document.getElementById('form-title').innerText = 'Edit Role: ' + name;
            document.getElementById('role-form').action = '/roles/' + id;
            document.getElementById('form-method').value = 'PUT';
            document.getElementById('role-name').value = name;

            // Reset all checkboxes first
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
                cb.disabled = false;
            });
            document.querySelectorAll('.action-checkbox').forEach(cb => {
                cb.nextElementSibling.classList.remove('opacity-50');
            });

            // Set Full Access and Enable modules
            Object.keys(fullAccessMap).forEach(moduleId => {
                const enabledCB = document.getElementById('mod-' + moduleId + '-enabled');
                const fullCB = document.getElementById('mod-' + moduleId + '-full');
                if (enabledCB) enabledCB.checked = true;
                if (fullCB) {
                    fullCB.checked = fullAccessMap[moduleId] == 1;
                    if (fullCB.checked) {
                        // Apply disabled state to actions
                        document.querySelectorAll('#actions-' + moduleId + ' .action-checkbox').forEach(cb => {
                            cb.checked = true;
                            cb.disabled = true;
                            cb.nextElementSibling.classList.add('opacity-50');
                        });
                    }
                }
            });

            // Set Specific Actions
            actionIds.forEach(actionId => {
                const cb = document.getElementById('action-' + actionId);
                if (cb && !cb.disabled) cb.checked = true;
            });
            
            // Switch to first tab or first enabled tab? Let's just switch to the first tab.
            const firstTab = document.querySelector('.module-tab');
            if (firstTab) {
                const firstId = firstTab.id.split('-')[1];
                switchTab(firstId);
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetForm() {
            document.getElementById('form-title').innerText = 'Create New Role';
            document.getElementById('role-form').action = '{{ route("roles.store") }}';
            document.getElementById('form-method').value = 'POST';
            document.getElementById('role-name').value = '';
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
                cb.disabled = false;
            });
            document.querySelectorAll('.action-checkbox').forEach(cb => {
                cb.nextElementSibling.classList.remove('opacity-50');
            });

            // Switch to first tab
            const firstTab = document.querySelector('.module-tab');
            if (firstTab) {
                const firstId = firstTab.id.split('-')[1];
                switchTab(firstId);
            }
        }

        // Logic to handle auto-selection and enabling
        document.querySelectorAll('.full-access-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const moduleId = this.id.split('-')[1];
                const enabler = document.getElementById('mod-' + moduleId + '-enabled');
                if (this.checked) {
                    enabler.checked = true;
                    // Optionally disable/check all actions in this module
                    document.querySelectorAll('#actions-' + moduleId + ' .action-checkbox').forEach(cb => {
                        cb.checked = true;
                        cb.disabled = true;
                        cb.nextElementSibling.classList.add('opacity-50');
                    });
                } else {
                    document.querySelectorAll('#actions-' + moduleId + ' .action-checkbox').forEach(cb => {
                        cb.disabled = false;
                        cb.nextElementSibling.classList.remove('opacity-50');
                    });
                }
            });
        });

        // Initialize state for already checked "Full Access"
        document.querySelectorAll('.full-access-toggle').forEach(toggle => {
            if (toggle.checked) {
                const moduleId = toggle.id.split('-')[1];
                document.querySelectorAll('#actions-' + moduleId + ' .action-checkbox').forEach(cb => {
                    cb.checked = true;
                    cb.disabled = true;
                });
            }
        });
    </script>
</body>
</html>
