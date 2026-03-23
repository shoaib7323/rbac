import React, { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import Can from '@/Components/RBAC/Can';

export default function Index({ auth, roles, all_permissions }) {
    const [editingRole, setEditingRole] = useState(null);
    const { data, setData, post, put, processing, errors, reset } = useForm({
        name: '',
        permissions: [],
    });

    const modules = [...new Set(all_permissions.map(p => p.module))];

    const handleSubmit = (e) => {
        e.preventDefault();
        if (editingRole) {
            put(`/roles/${editingRole.id}`, {
                onSuccess: () => {
                    setEditingRole(null);
                    reset();
                }
            });
        } else {
            post('/roles', {
                onSuccess: () => reset()
            });
        }
    };

    const togglePermission = (id) => {
        const newPermissions = data.permissions.includes(id)
            ? data.permissions.filter(p => p !== id)
            : [...data.permissions, id];
        setData('permissions', newPermissions);
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Role Management</h2>}
        >
            <Head title="Roles" />

            <div className="py-12">
                <div className="max-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                    <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <header>
                            <h3 className="text-lg font-medium text-gray-900">
                                {editingRole ? 'Edit Role' : 'Create New Role'}
                            </h3>
                            <p className="mt-1 text-sm text-gray-600">
                                Assign granular permissions across 8 modules.
                            </p>
                        </header>

                        <form onSubmit={handleSubmit} className="mt-6 space-y-6">
                            <div>
                                <label className="block font-medium text-sm text-gray-700">Role Name</label>
                                <input
                                    type="text"
                                    value={data.name}
                                    onChange={e => setData('name', e.target.value)}
                                    className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required
                                />
                                {errors.name && <div className="text-red-500 text-sm mt-1">{errors.name}</div>}
                            </div>

                            <div className="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                {modules.map(moduleName => (
                                    <div key={moduleName} className="border rounded-lg p-4 bg-gray-50 hover:shadow-md transition-shadow">
                                        <h4 className="font-bold text-indigo-700 mb-2 border-b pb-1">{moduleName}</h4>
                                        <div className="space-y-2">
                                            {all_permissions.filter(p => p.module === moduleName).map(permission => (
                                                <label key={permission.id} className="flex items-center space-x-2 cursor-pointer group">
                                                    <input
                                                        type="checkbox"
                                                        checked={data.permissions.includes(permission.id)}
                                                        onChange={() => togglePermission(permission.id)}
                                                        className="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                    />
                                                    <span className="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">
                                                        {permission.feature}: {permission.action}
                                                    </span>
                                                </label>
                                            ))}
                                        </div>
                                    </div>
                                ))}
                            </div>

                            <div className="flex items-center gap-4">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    {editingRole ? 'Update Role' : 'Create Role'}
                                </button>
                                {editingRole && (
                                    <button
                                        type="button"
                                        onClick={() => { setEditingRole(null); reset(); }}
                                        className="text-sm text-gray-600 hover:text-gray-900 underline"
                                    >
                                        Cancel
                                    </button>
                                )}
                            </div>
                        </form>
                    </div>

                    <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">Name</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">Type</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">Permissions</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {roles.map(role => (
                                    <tr key={role.id}>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{role.name}</td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {role.is_predefined ? <span className="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Predefined</span> : 'Custom'}
                                        </td>
                                        <td className="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                            {role.permissions.map(p => p.name).join(', ')}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {!role.is_predefined && (
                                                <button
                                                    onClick={() => {
                                                        setEditingRole(role);
                                                        setData({
                                                            name: role.name,
                                                            permissions: role.permissions.map(p => p.id)
                                                        });
                                                    }}
                                                    className="text-indigo-600 hover:text-indigo-900 transition-colors"
                                                >
                                                    Edit
                                                </button>
                                            )}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
