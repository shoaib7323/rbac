import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import Can from '@/Components/RBAC/Can';

export default function Dashboard({ auth }) {
    const modules = [
        { name: 'Inventory Management', permission: 'inventory.products.view', desc: 'Manage your products, stock levels, and categories.' },
        { name: 'Sales & Orders', permission: 'sales.orders.view', desc: 'Process orders, manage customers, and handle refunds.' },
        { name: 'User & Role Management', permission: 'user_management.users.view', desc: 'Control user access, roles, and granular permissions.' },
        { name: 'Financial Reports', permission: 'reports.financial.view', desc: 'View revenue charts and export detailed financial statements.' },
    ];

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard Overview</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-6">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {modules.map((mod) => (
                            <Can key={mod.name} perform={mod.permission}>
                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-lg transition-shadow border-t-4 border-indigo-500">
                                    <h3 className="font-bold text-lg text-indigo-900 mb-2">{mod.name}</h3>
                                    <p className="text-sm text-gray-600 mb-4">{mod.desc}</p>
                                    <button className="text-indigo-600 text-sm font-semibold hover:underline">
                                        Open Module &rarr;
                                    </button>
                                </div>
                            </Can>
                        ))}
                    </div>

                    <div className="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div className="text-gray-900">
                            Welcome back, <span className="font-bold text-indigo-700">{auth.user.name}</span>! 
                            You are logged in as <span className="uppercase text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded font-bold">{auth.user.roles[0]}</span>.
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
