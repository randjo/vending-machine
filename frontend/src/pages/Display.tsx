import { useCallback, useEffect, useState } from "react";
import api from "../api/api";
import Card from "../components/ui/Card";
import Button from "../components/ui/Button";

export default function Display() {
    const [messages, setMessages] = useState<string[]>([]);
    const [loading, setLoading] = useState(true);

    const loadMessages = useCallback(async () => {
        try {
            const response = await api.get("/api/admin/display");

            setMessages(response.data.messages);
        } catch (error) {
            console.error(error);
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        loadMessages();
    }, []);

    return (
        <div className="space-y-6">
            <div className="flex items-center justify-between">
                <h1 className="text-2xl font-semibold">Machine logs</h1>

                <Button onClick={loadMessages}>Refresh</Button>
            </div>

            <Card>
                {loading ? (
                    <div className="py-10 text-center">Loading...</div>
                ) : (
                    <div
                        className="
                            rounded-lg
                            bg-gray-900
                            p-6
                            font-mono
                            text-lg
                            text-green-400
                            shadow-inner
                            max-h-150
                            overflow-y-auto"
                    >
                        {messages.map((message, index) => (
                            <div
                                key={index}
                                className="
                                    min-h-8
                                    border-b
                                    border-gray-700
                                    last:border-0
                                    flex
                                    items-start
                                    gap-4
                                    py-1"
                            >
                                <span className="w-6 shrink-0 text-gray-500">
                                    {index + 1}
                                </span>

                                <span className="wrap-break-word whitespace-normal">
                                    {message || "\u00A0"}
                                </span>
                            </div>
                        ))}
                    </div>
                )}
            </Card>
        </div>
    );
}
