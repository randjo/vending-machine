import type { VendingCoin } from "../../types/api";

interface Props {
    coin: VendingCoin;
    onInsert: () => void;
}

export default function CoinButton({ coin, onInsert }: Props) {
    return (
        <button
            onClick={onInsert}
            className="
                w-20
                h-20
                rounded-full
                bg-gray-200
                shadow
                font-semibold
                hover:bg-gray-300
                transition
            "
        >
            {coin.formatted_value}
        </button>
    );
}
