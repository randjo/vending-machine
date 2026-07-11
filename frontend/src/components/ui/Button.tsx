import type { ButtonHTMLAttributes } from "react";
import clsx from "clsx";

type Props = ButtonHTMLAttributes<HTMLButtonElement> & {
    variant?: "primary" | "danger" | "secondary";
};

export default function Button({
    children,
    variant = "primary",
    className = "",
    ...props
}: Props) {
    const variants = {
        primary: "bg-blue-600 hover:bg-blue-700 text-white",

        danger: "bg-red-600 hover:bg-red-700 text-white",

        secondary: "bg-gray-200 hover:bg-gray-300 text-gray-800",
    };

    return (
        <button
            className={clsx(
                "inline-flex items-center justify-center rounded-lg px-4 py-2.5 font-medium",
                "shadow-sm transition-all duration-200 hover:shadow-md",
                "disabled:opacity-50 disabled:cursor-not-allowed",
                variants[variant],
                className,
            )}
            {...props}
        >
            {children}
        </button>
    );
}
