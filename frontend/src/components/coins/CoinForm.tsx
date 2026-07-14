import { useEffect, useState } from "react";
import type { SubmitEvent } from "react";
import api from "../../api/api";
import Swal from "sweetalert2";

import Input from "../ui/Input";
import Button from "../ui/Button";
import type { Coin } from "../../types/api";
import { getValidationErrors } from "../../utils/apiError";

interface Props {
    coin?: Coin | null;
    onSaved: () => void;
}

export default function CoinForm({ coin, onSaved }: Props) {
    const [value, setValue] = useState("");
    const [loading, setLoading] = useState(false);
    const [errors, setErrors] = useState<Record<string, string[]>>();

    useEffect(() => {
        if (coin) {
            setValue(String(coin?.value));
        } else {
            setValue("");
        }
    }, [coin]);

    async function submit(e: SubmitEvent) {
        e.preventDefault();

        try {
            setLoading(true);

            if (coin) {
                await api.put(`/api/admin/coins/${coin.id}`, { value });
            } else {
                await api.post("/api/admin/coins", { value });
            }

            setValue("");
            onSaved();

            await Swal.fire({
                title: coin ? "Updated" : "Created!",
                text: `Coin was ${coin ? "updated" : "added"}`,
                icon: "success",
                timer: 2000,
                showConfirmButton: false,
            });
        } catch (error) {
            const validationErrors = getValidationErrors(error);
            setErrors(validationErrors);

            // Swal.fire({
            //     title: "Error",
            //     text: `Could not ${coin ? "update" : "create"} coin. `,
            //     icon: "error",
            // });
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
                label="Price (BGN)"
                type="number"
                step="0.05"
                min="0.05"
                value={value}
                error={errors?.value?.[0]}
                required
                onChange={(e) => setValue(e.target?.value)}
            />

            <Button type="submit" disabled={loading}>
                {loading ? "Saving..." : coin ? "Update coin" : "Add coin"}
            </Button>
        </form>
    );
}
