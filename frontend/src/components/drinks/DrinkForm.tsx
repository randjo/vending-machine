import { useEffect, useState } from "react";
import type { SubmitEvent } from "react";
import api from "../../api/api";
import Swal from "sweetalert2";

import Input from "../ui/Input";
import Button from "../ui/Button";
import type { Drink } from "../../types/api";

interface Props {
    drink?: Drink;
    onSaved: () => void;
}

export default function DrinkForm({ drink, onSaved }: Props) {
    const [name, setName] = useState("");
    const [price, setPrice] = useState("");
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        if (drink) {
            setName(drink.name);
            setPrice(String(drink.price));
        } else {
            setName("");
            setPrice("");
        }
    }, [drink]);

    async function submit(e: SubmitEvent) {
        e.preventDefault();

        try {
            setLoading(true);

            if (drink) {
                await api.put(`/api/admin/drinks/${drink.id}`, { name, price });
            } else {
                await api.post("/api/admin/drinks", { name, price });
            }

            setName("");
            setPrice("");
            onSaved();

            await Swal.fire({
                title: drink ? "Updated" : "Created!",
                text: `Drink was ${drink ? "updated" : "added"}`,
                icon: "success",
                timer: 2000,
                showConfirmButton: false,
            });
        } catch {
            Swal.fire({
                title: "Error",
                text: `Could not ${drink ? "update" : "create"} drink.`,
                icon: "error",
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
                label="Name"
                value={name}
                required
                onChange={(e) => setName(e.target.value)}
            />

            <Input
                label="Price (BGN)"
                type="number"
                step="0.05"
                min="0.05"
                value={price}
                required
                onChange={(e) => setPrice(e.target.value)}
            />

            <Button type="submit" disabled={loading}>
                {loading ? "Saving..." : drink ? "Update drink" : "Add drink"}
            </Button>
        </form>
    );
}
