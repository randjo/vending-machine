import Button from "../ui/Button";
import { useAuth } from "../../auth/AuthContext";

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

                    <h1 className="text-lg font-semibold">Vending Admin</h1>
                </div>

                <div className="flex items-center gap-4">
                    <div className="hidden sm:block text-right">
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
