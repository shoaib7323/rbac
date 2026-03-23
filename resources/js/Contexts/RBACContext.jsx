import React, { createContext, useContext } from 'react';
import { usePage } from '@inertiajs/react';

const RBACContext = createContext();

export const RBACProvider = ({ children }) => {
    const { auth } = usePage().props;
    const userPermissions = auth.user?.permissions || [];
    const userRoles = auth.user?.roles || [];

    const hasPermission = (permission) => {
        if (userRoles.includes('Super Admin')) return true;
        return userPermissions.includes(permission);
    };

    const hasRole = (role) => {
        return userRoles.includes(role);
    };

    return (
        <RBACContext.Provider value={{ hasPermission, hasRole }}>
            {children}
        </RBACContext.Provider>
    );
};

export const useRBAC = () => {
    const context = useContext(RBACContext);
    if (!context) {
        throw new Error('useRBAC must be used within an RBACProvider');
    }
    return context;
};
