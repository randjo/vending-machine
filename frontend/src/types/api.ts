export interface ValidationErrorResponse {
    message: string;
    errors: Record<string, string[]>;
}

export interface ApiErrorResponse {
    message: string;
}

export interface Currency {
    id: number;
    sign: string;
    space: string;
    position: CurrencyPosition;
}

export type CurrencyPosition = "before" | "after";

export interface Drink {
    id: number;
    name: string;
    price: number;
}

export interface Coin {
    id: number;
    value: number;
}

export interface VendingDrink {
    name: string;
    price: number;
    formatted_price: string;
}

export interface VendingCoin {
    value: number;
    formatted_value: string;
}
