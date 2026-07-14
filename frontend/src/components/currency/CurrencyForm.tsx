import { useEffect, useState } from "react";
import type { SubmitEvent } from "react";
import api from "../../api/api";
import Swal from "sweetalert2";

import Input from "../ui/Input";
import Button from "../ui/Button";
import type { Currency } from "../../types/api";
import type { CurrencyPosition } from "../../types/api";

interface Props {
    currency?: Currency | null;
    onSaved: () => void;
}

export default function CurrencyForm({ currency, onSaved }: Props) {
    const [sign, setSign] = useState("");
    const [space, setSpace] = useState("");
    const [position, setPosition] = useState<CurrencyPosition>("after");
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        if (currency) {
            setSign(currency?.sign);
            setSpace(currency?.space);
            setPosition(currency?.position);
        } else {
            setSign("");
            setSpace("");
            setPosition("after" as CurrencyPosition);
        }
    }, [currency]);

    async function submit(e: SubmitEvent) {
        e.preventDefault();

        try {
            setLoading(true);

            await api.put(`/api/admin/currency/${currency?.id}`, {
                sign,
                space,
                position,
            });

            onSaved();

            await Swal.fire({
                title: "Updated",
                text: "Currency settings were updated",
                icon: "success",
                timer: 2000,
                showConfirmButton: false,
            });
        } finally {
            setLoading(false);
        }
    }

    return (
        <form
            onSubmit={submit}
            className="
                space-y-4
            "
        >
            <Input
                label="Sign"
                value={sign}
                required
                onChange={(e) => setSign(e.target.value)}
            />

            <Input
                label="Space"
                value={space ?? ""}
                onChange={(e) => setSpace(e.target.value)}
            />

            <div className="space-y-1">
                <label
                    htmlFor="position"
                    className="
                    block
                    text-sm
                    font-medium
                "
                >
                    Position
                </label>
                <select
                    id="position"
                    value={position}
                    className="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5
                        text-gray-900 placeholder:text-gray-400 shadow-sm
                    focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20
                        disabled:bg-gray-100 disabled:cursor-not-allowed transition"
                    onChange={(e) =>
                        setPosition(e.target.value as CurrencyPosition)
                    }
                >
                    <option value="before">Before currency sign</option>

                    <option value="after">After currency sign</option>
                </select>
            </div>

            <Button type="submit" disabled={loading}>
                {loading ? "Saving..." : "Update currency"}
            </Button>
        </form>
    );
}
