import { useEffect, useState } from "react";
import api from "../api/api";
import type { Coin } from "../types/api";
import Button from "../components/ui/Button";
import { confirmDelete } from "../utils/confirm";
import CoinForm from "../components/coins/CoinForm";
import Swal from "sweetalert2";
import Modal from "../components/ui/Modal";

export default function Coins() {
    const [coins, setCoins] = useState<Coin[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [showCreateModal, setShowCreateModal] = useState(false);
    const [selectedCoin, setSelectedCoin] = useState<Coin | null>(null);

    async function loadCoins() {
        try {
            setLoading(true);

            const response = await api.get("/api/admin/coins");

            setCoins(response.data.coins);
        } catch (error) {
            setError("Failed to load coins");
        } finally {
            setLoading(false);
        }
    }

    useEffect(() => {
        loadCoins();
    }, []);

    async function deleteCoin(coin: Coin) {
        const result = await confirmDelete(coin.value);

        if (!result.isConfirmed) {
            return;
        }

        try {
            await api.delete(`/api/admin/coins/${coin.id}`);

            setCoins((prev) => prev.filter((item) => item.id !== coin.id));

            await Swal.fire({
                title: "Deleted!",
                text: `Coin ${coin.value} was deleted`,
                icon: "success",
                timer: 2000,
                showConfirmButton: false,
            });
        } catch {
            await Swal.fire({
                title: "Error",
                text: "Could not delete coin",
                icon: "error",
            });
        }
    }

    function editCoin(coin: Coin) {
        setSelectedCoin(coin);
        setShowCreateModal(true);
    }

    if (loading) {
        return <div>Loading coins...</div>;
    }

    return (
        <div>
            <div className="flex justify-between items-center mb-6">
                <h1 className="text-2xl font-semibold">Coins management</h1>

                <Button
                    onClick={() => {
                        setSelectedCoin(null);
                        setShowCreateModal(true);
                    }}
                >
                    Add Coin
                </Button>
                <Modal
                    open={showCreateModal}
                    title={selectedCoin ? "Update coin" : "Add coin"}
                    onClose={() => setShowCreateModal(false)}
                >
                    <CoinForm
                        coin={selectedCoin}
                        onSaved={() => {
                            setShowCreateModal(false);
                            loadCoins();
                        }}
                    />
                </Modal>
            </div>

            {error && <div className="mb-4 text-red-600">{error}</div>}

            <div className="bg-white rounded-xl shadow overflow-hidden">
                <table className="w-full">
                    <thead className="bg-gray-50 border-b">
                        <tr>
                            <th className="text-left px-6 py-3">ID</th>
                            <th className="text-left px-6 py-3">Value</th>
                        </tr>
                    </thead>

                    <tbody>
                        {coins.map((coin) => (
                            <tr
                                key={coin.id}
                                className="border-b last:border-b-0"
                            >
                                <td className="px-6 py-4">{coin.id}</td>

                                <td className="px-6 py-4">{coin.value}</td>

                                <td className="px-6 py-4">
                                    <Button onClick={() => editCoin(coin)}>
                                        Edit
                                    </Button>
                                    &nbsp;
                                    <Button
                                        variant="danger"
                                        onClick={() => deleteCoin(coin)}
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
