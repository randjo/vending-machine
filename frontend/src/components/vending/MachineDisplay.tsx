interface Props {
    messages: string[];
    amount: string;
}

export default function MachineDisplay({ messages, amount }: Props) {
    return (
        <>
            <h2 className="text-xl font-semibold mb-4">Display</h2>
            <div
                className="
                rounded-lg
                bg-black
                p-4
                font-mono
                text-green-400
            "
            >
                <div className="border-b border-gray-700">
                    Balance: {amount}
                </div>

                {messages.map((message, index) => (
                    <div key={index}>{message}</div>
                ))}
            </div>
        </>
    );
}
