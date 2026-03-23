import React from 'react';
import { useRBAC } from '../Contexts/RBACContext';

const Can = ({ perform, children, fallback = null }) => {
    const { hasPermission } = useRBAC();

    if (hasPermission(perform)) {
        return <>{children}</>;
    }

    return fallback;
};

export default Can;
