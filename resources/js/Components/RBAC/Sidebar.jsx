import React from 'react';
import { Link } from '@inertiajs/react';
import { useRBAC } from '../Contexts/RBACContext';
import Can from './Can';

const Sidebar = () => {
    const { hasRole } = useRBAC();

    const modules = [
        { name: 'Dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', route: 'dashboard', permission: 'dashboard.overview.view' },
        { name: 'Users', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197', route: 'dashboard', permission: 'user_management.users.view' },
        { name: 'Roles', icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 2.944a11.952 11.952 0 00-6.831 2.133.5.5 0 00-.184.54a12.032 12.032 0 007.015 7.325.5.5 0 00.184.54A11.952 11.952 0 0012 21', route: 'roles.index', permission: 'role_&_permission.roles.view' },
        { name: 'Content', icon: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 4v5h5', route: 'dashboard', permission: 'content.posts.view' },
        { name: 'Inventory', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', route: 'dashboard', permission: 'inventory.products.view' },
        { name: 'Sales', icon: 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', route: 'dashboard', permission: 'sales.orders.view' },
        { name: 'Reports', icon: 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', route: 'dashboard', permission: 'reports.financial.view' },
        { name: 'Settings', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', route: 'dashboard', permission: 'settings.system.view' },
    ];

    return (
        <aside className="w-64 bg-indigo-900 text-white min-h-screen p-4 flex flex-col">
            <div className="text-2xl font-bold mb-8 flex items-center gap-2">
                <span className="bg-white text-indigo-900 p-1 rounded shadow-lg">RG</span>
                <span>RBAC Pro</span>
            </div>
            
            <nav className="flex-1 space-y-1">
                {modules.map(mod => (
                    <Can key={mod.name} perform={mod.permission}>
                        <Link
                            href={mod.route === 'dashboard' ? '/dashboard' : '/roles'}
                            className="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-800 transition-colors group"
                        >
                            <svg className="w-5 h-5 opacity-70 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d={mod.icon} />
                            </svg>
                            <span>{mod.name}</span>
                        </Link>
                    </Can>
                ))}
            </nav>

            <div className="mt-auto border-t border-indigo-800 pt-4">
                <div className="p-3 bg-indigo-800 rounded-lg text-xs opacity-70 italic text-center">
                    Flexible Multi-module RBAC System
                </div>
            </div>
        </aside>
    );
};

export default Sidebar;
