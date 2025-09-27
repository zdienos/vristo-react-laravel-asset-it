import { Navigate } from 'react-router-dom';
import { useEffect, useState } from 'react';
import axios from '../lib/axios';

const ProtectedRoute = ({ children }: { children: React.ReactNode }) => {
    const [isAuthenticated, setIsAuthenticated] = useState<boolean | null>(null);

    useEffect(() => {
        const checkAuth = async () => {
            try {
                await axios.get('/api/user');
                setIsAuthenticated(true);
            } catch (error) {
                setIsAuthenticated(false);
            }
        };
        checkAuth();
    }, []);

    if (isAuthenticated === null) {
        // Tampilkan loading spinner atau komponen placeholder
        return <div>Loading...</div>;
    }

    if (!isAuthenticated) {
        // Redirect ke halaman login jika tidak terotentikasi
        return <Navigate to="/auth/signin" />;
    }

    return <>{children}</>;
};

export default ProtectedRoute;
