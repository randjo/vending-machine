import { useEffect } from "react";
import { NavLink } from "react-router-dom";

type Props = {
    open: boolean;
    onClose: () => void;
};

const menu = [
    {
        title: "Dashboard",
        path: "/admin",
    },
    {
        title: "Currency",
        path: "/admin/currency",
    },
    {
        title: "Drinks",
        path: "/admin/drinks",
    },
    {
        title: "Coins",
        path: "/admin/coins",
    },
    {
        title: "Display",
        path: "/admin/display",
    },
];

export default function Sidebar({ open, onClose }: Props) {
    useEffect(() => {
        function handleEscape(event: KeyboardEvent) {
            if (event.key === "Escape") {
                onClose();
            }
        }

        if (open) {
            document.addEventListener("keydown", handleEscape);
        }

        return () => {
            document.removeEventListener("keydown", handleEscape);
        };
    }, [open, onClose]);

    return (
        <>
            {open && (
                <div
                    className="
                        fixed
                        inset-0
                        z-30
                        bg-black/40
                        backdrop-blur-sm
                        transition-opacity
                        lg:hidden"
                    onClick={onClose}
                />
            )}

            <aside
                className={`
                    fixed
                    top-16
                    left-0
                    bottom-0

                    z-40

                    w-64

                    bg-white

                    border-r

                    rounded-r-xl
                    lg:rounded-none

                    shadow-lg

                    transition-transform
                    duration-300
                    ease-in-out

                    ${open ? "translate-x-0" : "-translate-x-full"}

                    lg:translate-x-0
                `}
            >
                <nav className="p-4 space-y-2">
                    {menu.map((item) => (
                        <NavLink
                            key={item.path}
                            to={item.path}
                            end={item.path === "/admin"}
                            onClick={onClose}
                            className={({ isActive }) =>
                                `
                                block
                                rounded-lg
                                px-4
                                py-2
                                transition

                                ${
                                    isActive
                                        ? "bg-blue-600 text-white"
                                        : "hover:bg-gray-100"
                                }
                                `
                            }
                        >
                            {item.title}
                        </NavLink>
                    ))}
                </nav>
            </aside>
        </>
    );
}
