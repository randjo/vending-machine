import Button from "../ui/Button";
import { useAuth } from "../../auth/AuthContext";
import { Link } from "react-router-dom";

type Props = {
    onMenuClick: () => void;
};

export default function Header({ onMenuClick }: Props) {
    const { user, logout } = useAuth();

    return (
        <header className="sticky top-0 z-30 bg-white border-b shadow-sm">
            <div className="flex h-16 items-center justify-between px-4 lg:px-6">
                <div className="flex items-center gap-4">
                    <button
                        className="lg:hidden text-2xl"
                        onClick={onMenuClick}
                    >
                        <span className="text-xl">☰</span>
                    </button>

                    <h1 className="text-lg font-semibold">Admin Panel</h1>
                </div>

                <div className="flex items-center gap-3">
                    <Button>
                        <Link to="/">Vending Machine</Link>
                    </Button>

                    <div className="hidden text-right sm:block">
                        <div className="font-medium">{user?.name}</div>

                        <div className="text-sm text-gray-500">
                            {user?.email}
                        </div>
                    </div>

                    <Button variant="danger" onClick={logout}>
                        Logout
                    </Button>
                </div>
            </div>
        </header>
    );
}
