import { useEffect, useState } from "react";
import api from "../api/api";
import type { Currency } from "../types/api";
import Button from "../components/ui/Button";
import CurrencyForm from "../components/currency/CurrencyForm";
import Modal from "../components/ui/Modal";

export default function Currency() {
    const [currency, setCurrency] = useState<Currency>();
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [showCreateModal, setShowCreateModal] = useState(false);
    const [selectedCurrency, setSelectedCurrency] = useState<Currency | null>(
        null,
    );

    async function loadCurrency() {
        try {
            setLoading(true);

            const response = await api.get("/api/admin/currency");

            setCurrency(response.data.currency);
        } catch {
            setError("Failed to load currency settings");
        } finally {
            setLoading(false);
        }
    }

    useEffect(() => {
        loadCurrency();
    }, []);

    function editCurrency(currency: Currency) {
        setSelectedCurrency(currency);
        setShowCreateModal(true);
    }

    if (loading) {
        return <div>Loading drinks...</div>;
    }

    return (
        <div>
            <div className="flex justify-between items-center mb-6">
                <h1 className="text-2xl font-semibold">Currency settings</h1>
                <Modal
                    open={showCreateModal}
                    title="Update currency"
                    onClose={() => setShowCreateModal(false)}
                >
                    <CurrencyForm
                        currency={selectedCurrency}
                        onSaved={() => {
                            setShowCreateModal(false);
                            loadCurrency();
                        }}
                    />
                </Modal>
            </div>

            {error && <div className="mb-4 text-red-600">{error}</div>}

            <div className="bg-white rounded-xl shadow overflow-hidden">
                <table className="w-full">
                    <thead className="bg-gray-50 border-b">
                        <tr>
                            <th className="text-left px-6 py-3">Sign</th>

                            <th className="text-left px-6 py-3">Space</th>

                            <th className="text-left px-6 py-3">Position</th>

                            <th className="text-left px-6 py-3">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr className="border-b last:border-b-0">
                            <td className="px-6 py-4">{currency?.sign}</td>

                            <td className="px-6 py-4">{currency?.space}</td>

                            <td className="px-6 py-4">{currency?.position}</td>

                            <td className="px-6 py-4">
                                <Button onClick={() => editCurrency(currency!)}>
                                    Edit
                                </Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    );
}
