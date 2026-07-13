import Swal from "sweetalert2";

import api from "../api/api";

import Card from "../components/ui/Card";
import Button from "../components/ui/Button";
import { useState } from "react";

export default function Dashboard() {
    const [restarting, setRestarting] = useState(false);

    async function restartMachine() {
        const result = await Swal.fire({
            title: "Restart machine?",
            text: "This will reset the vending machine state.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Restart",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#dc2626",
        });

        if (!result.isConfirmed) {
            return;
        }

        try {
            setRestarting(true);
            const response = await api.post("/api/admin/restart");

            await Swal.fire({
                title: "Success",
                text: response.data.message,
                icon: "success",
            });
        } catch {
            await Swal.fire({
                title: "Error",
                text: "Could not restart machine.",
                icon: "error",
            });
        } finally {
            setRestarting(false);
        }
    }

    return (
        <Card>
            <h2 className="mb-2 text-xl font-semibold">Machine controls</h2>

            <p className="mb-6 text-gray-600">
                Restart the vending machine and reset its current state.
            </p>

            <Button
                variant="danger"
                disabled={restarting}
                onClick={restartMachine}
            >
                {restarting ? "Restarting..." : "Restart machine"}
            </Button>
        </Card>
    );
}
