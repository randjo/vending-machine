import { useEffect, useState } from "react";

import api from "../api/api";

import type { VendingDrink, VendingCoin } from "../types/api";

import DrinkCard from "../components/vending/DrinkCard";
import CoinButton from "../components/vending/CoinButton";
import MachineDisplay from "../components/vending/MachineDisplay";
import Button from "../components/ui/Button";
import Swal from "sweetalert2";
import { Link } from "react-router-dom";
import { useAuth } from "../auth/AuthContext";

export default function Vending() {
    const [drinks, setDrinks] = useState<VendingDrink[]>([]);
    const [coins, setCoins] = useState<VendingCoin[]>([]);
    const [messages, setMessages] = useState<string[]>([]);
    const [amount, setAmount] = useState("");
    const { user } = useAuth();

    async function loadMachine() {
        const [drinksResponse, coinsResponse, displayResponse, amountResponse] =
            await Promise.all([
                api.get("/api/vending/drinks"),
                api.get("/api/vending/coins"),
                api.get("/api/vending/display"),
                api.get("/api/vending/balance"),
            ]);

        setDrinks(drinksResponse.data.drinks);
        setCoins(coinsResponse.data.coins);
        setMessages(displayResponse.data.messages);
        setAmount(amountResponse.data);
    }

    useEffect(() => {
        loadMachine();
    }, []);

    async function buyDrink(drink: VendingDrink) {
        try {
            await api.post("/api/vending/buy", {
                drink: drink.name,
            });

            await loadMachine();
        } catch (error) {
            console.error(error);

            await Swal.fire({
                title: "Cannot buy drink",
                text: "Not enough money or drink unavailable.",
                icon: "error",
            });
        }
    }

    async function insertCoin(coin: VendingCoin) {
        try {
            await api.post("/api/vending/coins", {
                value: coin.value,
            });

            await loadMachine();
        } catch (error) {
            console.error(error);
        }
    }

    async function returnChange() {
        try {
            await api.get("/api/vending/change");
            await loadMachine();
        } catch (error) {
            console.error(error);
        }
    }

    return (
        <div
            className="
            min-h-screen
            bg-gray-100
            p-6
        "
        >
            <div className="mb-6 flex items-center justify-between">
                <h1 className="text-3xl font-bold">Vending Machine</h1>

                {user && (
                    <Button>
                        <Link to="/admin">Admin Panel</Link>
                    </Button>
                )}
            </div>

            <div
                className="
                    grid
                    grid-cols-1
                    lg:grid-cols-3
                    gap-6"
            >
                {/* Drinks */}
                <div className="lg:col-span-2">
                    <h2 className="text-xl font-semibold mb-4">
                        Choose your drink
                    </h2>

                    <div
                        className="
                            grid
                            grid-cols-2
                            md:grid-cols-3
                            gap-4"
                    >
                        {drinks.map((drink) => (
                            <DrinkCard
                                key={drink.name}
                                drink={drink}
                                onBuy={() => buyDrink(drink)}
                            />
                        ))}
                    </div>
                </div>

                {/* Coins */}
                <div>
                    <h2 className="text-xl font-semibold mb-4">Insert coins</h2>

                    <div
                        className="
                            bg-white
                            rounded-xl
                            shadow
                            p-6"
                    >
                        <div
                            className="
                                flex
                                flex-wrap
                                gap-4"
                        >
                            {coins.map((coin) => (
                                <CoinButton
                                    key={coin.value}
                                    coin={coin}
                                    onInsert={() => insertCoin(coin)}
                                />
                            ))}
                        </div>

                        <Button onClick={returnChange} className="mt-6">
                            Return change
                        </Button>
                    </div>
                </div>
            </div>

            <div className="mt-6">
                <MachineDisplay messages={messages} amount={amount} />
            </div>
        </div>
    );
}
