interface Props {
    messages: string[];
    amount: string;
}

export default function MachineDisplay({ messages, amount }: Props) {
    return (
        <div
            className="
                rounded-lg
                bg-black
                p-4
                font-mono
                text-green-400
            "
        >
            <div>Balance: {amount}</div>

            {messages.map((message, index) => (
                <div key={index}>{message}</div>
            ))}
        </div>
    );
}
