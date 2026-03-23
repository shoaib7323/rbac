import React from 'react';
import Sidebar from '@/Components/RBAC/Sidebar';
import { RBACProvider } from '@/Contexts/RBACContext';

export default function AuthenticatedLayout({ user, header, children }) {
    return (
        <RBACProvider>
            <div className="flex min-h-screen bg-gray-100 italic-font">
                <Sidebar />
                <div className="flex-1">
                    <header className="bg-white shadow-sm h-16 flex items-center px-8 justify-between">
                        <div>{header}</div>
                        <div className="flex items-center gap-4 text-sm text-gray-600">
                            <span className="font-medium">{user.name}</span>
                            <span className="bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                                {user.roles?.[0] || 'User'}
                            </span>
                        </div>
                    </header>
                    <main className="p-8">
                        {children}
                    </main>
                </div>
            </div>
        </RBACProvider>
    );
}
