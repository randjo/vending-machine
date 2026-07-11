import { Outlet } from "react-router-dom";

export default function VendingLayout() {
    return (
        <div
            className="
            min-h-screen
            bg-gray-100
        "
        >
            <Outlet />
        </div>
    );
}
