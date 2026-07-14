import { AxiosError } from "axios";
import axios from "axios";

import type { ValidationErrorResponse } from "../types/api";

export function getValidationErrors(error: unknown): Record<string, string[]> {
    const axiosError = error as AxiosError<ValidationErrorResponse>;

    if (axiosError.response?.status !== 422) {
        return {};
    }

    return axiosError.response.data.errors;
}

export function getApiErrorMessage(
    error: unknown,
    fallback = "Something went wrong",
): string {
    if (!axios.isAxiosError(error)) {
        return fallback;
    }

    const data = error.response?.data;

    if (data?.errors) {
        return Object.values(data.errors).flat().join("\n");
    }

    return data?.message ?? fallback;
}
