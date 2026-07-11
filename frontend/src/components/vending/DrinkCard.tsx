import type { VendingDrink } from "../../types/api";

interface Props {
    drink: VendingDrink;
    onBuy: () => void;
}

export default function DrinkCard({ drink, onBuy }: Props) {
    return (
        <button
            onClick={onBuy}
            className="
                bg-white
                rounded-xl
                shadow
                p-5
                text-left
                hover:shadow-lg
                hover:-translate-y-1
                transition
            "
        >
            <div
                className="
                text-lg
                font-semibold
            "
            >
                {drink.name}
            </div>

            <div
                className="
                mt-3
                text-gray-600
            "
            >
                {drink.formatted_price}
            </div>
        </button>
    );
}
