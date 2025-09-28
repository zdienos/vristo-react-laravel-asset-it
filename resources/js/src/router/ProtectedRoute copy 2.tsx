import { useSelector } from 'react-redux';
import { Navigate, Outlet } from 'react-router-dom';
import { IRootState } from '../store';

const ProtectedRoute = () => {
    const { isAuthenticated, loading } = useSelector((state: IRootState) => state.auth);

    if (loading) {
        return <div>xxxxLoading...</div>; // atau spinner
    }
    return isAuthenticated ? <Outlet /> : <Navigate to="/auth/signin" replace />;
};

export default ProtectedRoute;
