import { useState, type SubmitEvent } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";

import { useAuth } from "../auth/AuthContext";

import Card from "../components/ui/Card";
import Button from "../components/ui/Button";
import Input from "../components/ui/Input";
import { getValidationErrors } from "../utils/apiError";

export default function Login() {
    const { login } = useAuth();
    const navigate = useNavigate();

    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");

    const [errors, setErrors] = useState<Record<string, string[]>>({});
    const [loading, setLoading] = useState(false);

    async function handleSubmit(e: SubmitEvent<HTMLFormElement>) {
        e.preventDefault();

        setErrors({});
        setLoading(true);

        try {
            await login({
                email,
                password,
            });

            navigate("/admin");
        } catch (error) {
            const validationErrors = getValidationErrors(error);

            if (validationErrors) {
                setErrors(validationErrors);
                return;
            }

            if (axios.isAxiosError(error) && error.response?.status === 401) {
                setErrors({
                    password: ["Невалиден email или парола."],
                });

                return;
            }

            setErrors({
                password: ["Възникна неочаквана грешка."],
            });
        } finally {
            setLoading(false);
        }
    }

    return (
        <div className="flex min-h-screen items-center justify-center bg-gray-100 px-4">
            <Card className="w-full max-w-md">
                <div className="mb-8 text-center">
                    <h1 className="text-3xl font-bold">Vending Admin</h1>

                    <p className="mt-2 text-gray-500">Sign in to continue</p>
                </div>

                <form onSubmit={handleSubmit} className="space-y-5">
                    <Input
                        label="Email"
                        type="email"
                        value={email}
                        disabled={loading}
                        error={errors.email?.[0]}
                        onChange={(e) => setEmail(e.target.value)}
                    />

                    <Input
                        label="Password"
                        type="password"
                        value={password}
                        disabled={loading}
                        error={errors.password?.[0]}
                        onChange={(e) => setPassword(e.target.value)}
                    />

                    <Button type="submit" className="w-full" disabled={loading}>
                        {loading ? "Signing in..." : "Sign In"}
                    </Button>
                </form>
            </Card>
        </div>
    );
}
