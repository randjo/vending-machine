import { createBrowserRouter } from "react-router-dom";

import Login from "../pages/Login";
import Dashboard from "../pages/Dashboard";
import ProtectedRoute from "../auth/ProtectedRoute";
import GuestRoute from "../auth/GuestRoute";
import AdminLayout from "../layouts/AdminLayout";
import VendingLayout from "../layouts/VendingLayout";
import Vending from "../pages/Vending";
import Drinks from "../pages/Drinks";
import Display from "../pages/Display";
import Coins from "../pages/Coins";
import Currency from "../pages/Currency";

const router = createBrowserRouter([
    {
        path: "/",
        element: <VendingLayout />,
        children: [
            {
                index: true,
                element: <Vending />,
            },
        ],
    },
    {
        path: "/admin/login",
        element: (
            <GuestRoute>
                <Login />
            </GuestRoute>
        ),
    },
    {
        path: "/admin",
        element: (
            <ProtectedRoute>
                <AdminLayout />
            </ProtectedRoute>
        ),
        children: [
            {
                index: true,
                element: <Dashboard />,
            },
            {
                path: "currency",
                element: <Currency />,
            },
            {
                path: "drinks",
                element: <Drinks />,
            },
            {
                path: "coins",
                element: <Coins />,
            },
            {
                path: "display",
                element: <Display />,
            },
        ],
    },
]);

export default router;
