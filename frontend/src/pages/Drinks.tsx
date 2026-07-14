import { useEffect, useState } from "react";
import api from "../api/api";
import type { Drink } from "../types/api";
import Button from "../components/ui/Button";
import { confirmDelete } from "../utils/confirm";
import DrinkForm from "../components/drinks/DrinkForm";
import Swal from "sweetalert2";
import Modal from "../components/ui/Modal";

export default function Drinks() {
    const [drinks, setDrinks] = useState<Drink[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [showCreateModal, setShowCreateModal] = useState(false);
    const [selectedDrink, setSelectedDrink] = useState<Drink | undefined>(
        undefined,
    );

    async function loadDrinks() {
        try {
            setLoading(true);

            const response = await api.get("/api/admin/drinks");

            setDrinks(response.data.drinks);
        } catch {
            setError("Failed to load drinks");
        } finally {
            setLoading(false);
        }
    }

    useEffect(() => {
        loadDrinks();
    }, []);

    async function deleteDrink(drink: Drink) {
        const result = await confirmDelete(drink.name);

        if (!result.isConfirmed) {
            return;
        }

        try {
            await api.delete(`/api/admin/drinks/${drink.id}`);

            setDrinks((prev) => prev.filter((item) => item.id !== drink.id));

            await Swal.fire({
                title: "Deleted!",
                text: `${drink.name} was deleted`,
                icon: "success",
                timer: 2000,
                showConfirmButton: false,
            });
        } catch {
            await Swal.fire({
                title: "Error",
                text: "Could not delete drink",
                icon: "error",
            });
        }
    }

    function editDrink(drink: Drink) {
        setSelectedDrink(drink);

        setShowCreateModal(true);
    }

    if (loading) {
        return <div>Loading drinks...</div>;
    }

    return (
        <div>
            <div className="flex justify-between items-center mb-6">
                <h1 className="text-2xl font-semibold">Drinks management</h1>

                <Button
                    onClick={() => {
                        setSelectedDrink(undefined);
                        setShowCreateModal(true);
                    }}
                >
                    Add Drink
                </Button>
                <Modal
                    open={showCreateModal}
                    title={selectedDrink ? "Update drink" : "Add Drink"}
                    onClose={() => setShowCreateModal(false)}
                >
                    <DrinkForm
                        drink={selectedDrink}
                        onSaved={() => {
                            setShowCreateModal(false);
                            loadDrinks();
                        }}
                    />
                </Modal>
            </div>

            {error && <div className="mb-4 text-red-600">{error}</div>}

            <div className="bg-white rounded-xl shadow overflow-hidden">
                <table className="w-full">
                    <thead className="bg-gray-50 border-b">
                        <tr>
                            <th className="text-left px-6 py-3">Name</th>

                            <th className="text-left px-6 py-3">Price</th>

                            <th className="text-left px-6 py-3">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        {drinks.map((drink) => (
                            <tr
                                key={drink.id}
                                className="border-b last:border-b-0"
                            >
                                <td className="px-6 py-4">{drink.name}</td>

                                <td className="px-6 py-4">{drink.price}</td>

                                <td className="px-6 py-4">
                                    <Button onClick={() => editDrink(drink)}>
                                        Edit
                                    </Button>
                                    &nbsp;
                                    <Button
                                        variant="danger"
                                        onClick={() => deleteDrink(drink)}
                                    >
                                        Delete
                                    </Button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
