import { Outlet } from "react-router-dom";
import { useState } from "react";

import Header from "../components/layout/Header";
import Sidebar from "../components/layout/Sidebar";

export default function AdminLayout() {
    const [sidebarOpen, setSidebarOpen] = useState(false);

    return (
        <div className="min-h-screen bg-gray-100">
            <Header onMenuClick={() => setSidebarOpen((prev) => !prev)} />

            <Sidebar open={sidebarOpen} onClose={() => setSidebarOpen(false)} />

            <main
                className="
                    p-4
                    lg:ml-64
                    lg:p-8
                "
            >
                <Outlet />
            </main>
        </div>
    );
}
