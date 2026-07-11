import type { InputHTMLAttributes } from "react";
import clsx from "clsx";

type Props = InputHTMLAttributes<HTMLInputElement> & {
    label?: string;
    error?: string;
};

export default function Input({
    label,
    error,
    className = "",
    ...props
}: Props) {
    return (
        <div className="space-y-1">
            {label && (
                <label
                    className="
                    block
                    text-sm
                    font-medium
                "
                >
                    {label}
                </label>
            )}

            <input
                className={clsx(
                    "w-full rounded-lg border border-gray-300 bg-white",
                    "px-4 py-2.5",
                    "text-gray-900 placeholder:text-gray-400",
                    "shadow-sm",
                    "focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20",
                    "disabled:bg-gray-100 disabled:cursor-not-allowed",
                    "transition",
                    error &&
                        "border-red-500 focus:border-red-500 focus:ring-red-500/20",
                    className,
                )}
                {...props}
            />

            {error && (
                <p
                    className="
                    text-sm
                    text-red-600
                "
                >
                    {error}
                </p>
            )}
        </div>
    );
}
