import type { ReactNode } from "react";

interface Props {
    open: boolean;
    title: string;
    onClose: () => void;
    children: ReactNode;
}

export default function Modal({ open, title, onClose, children }: Props) {
    if (!open) {
        return null;
    }

    return (
        <div
            className="
                fixed
                inset-0
                z-50
                flex
                items-center
                justify-center
                bg-black/40
            "
            onClick={onClose}
        >
            <div
                className="
                    bg-white
                    rounded-xl
                    shadow-xl
                    w-full
                    max-w-md
                    p-6
                "
                onClick={(e) => e.stopPropagation()}
            >
                <div
                    className="
                    flex
                    justify-between
                    items-center
                    mb-6
                "
                >
                    <h2
                        className="
                        text-xl
                        font-semibold
                    "
                    >
                        {title}
                    </h2>

                    <button
                        onClick={onClose}
                        className="
                            text-gray-500
                            hover:text-gray-800
                        "
                    >
                        ✕
                    </button>
                </div>

                {children}
            </div>
        </div>
    );
}
