import { Navigate } from "react-router-dom";

import { useAuth } from "./AuthContext";

type Props = {
    children: React.ReactNode;
};

export default function ProtectedRoute({ children }: Props) {
    const { loading, isAuthenticated } = useAuth();

    if (loading) {
        return <div>Loading...</div>;
    }

    if (!isAuthenticated) {
        return <Navigate to="/admin/login" replace />;
    }

    return <>{children}</>;
}
