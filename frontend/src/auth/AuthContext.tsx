import { createContext, useContext, useEffect, useState } from "react";

import type { ReactNode } from "react";

import api from "../api/api";
import type { User, LoginData } from "../types/auth";

type AuthContextType = {
    user: User | null;
    loading: boolean;
    isAuthenticated: boolean;
    login: (data: LoginData) => Promise<void>;
    logout: () => Promise<void>;
};

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: ReactNode }) {
    const [user, setUser] = useState<User | null>(null);
    const [loading, setLoading] = useState(true);

    async function checkAuth() {
        try {
            const { data } = await api.get("/api/admin/check-user");

            if (data.authenticated) {
                setUser(data.user);
            } else {
                setUser(null);
            }
        } finally {
            setLoading(false);
        }
    }

    useEffect(() => {
        checkAuth();
    }, []);

    async function login(credentials: LoginData) {
        try {
            await api.get("/sanctum/csrf-cookie");

            await api.post("/api/admin/login", credentials);

            const { data } = await api.get("/api/admin/user");

            setUser(data);
        } catch (error) {
            console.error("Login failed:", error);

            throw error;
        }
    }

    async function logout() {
        try {
            await api.post("/api/admin/logout");
        } catch (error) {
            console.error("Logout failed:", error);
        } finally {
            setUser(null);
        }
    }

    return (
        <AuthContext.Provider
            value={{
                user,
                loading,
                isAuthenticated: !!user,
                login,
                logout,
            }}
        >
            {children}
        </AuthContext.Provider>
    );
}

export function useAuth() {
    const context = useContext(AuthContext);

    if (!context) {
        throw new Error("useAuth must be used inside AuthProvider");
    }

    return context;
}
