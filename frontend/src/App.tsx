import api from "./api/axios";

function App() {
    const login = async () => {
        await api.get("/sanctum/csrf-cookie");

        const response = await api.post("/api/login", {
            email: "admin@test.com",
            password: "password",
        });

        console.log(response.data);
    };

    const user = async () => {
        const response = await api.get("/api/user");
        console.log(response.data);
    };

    const logout = async () => {
        const response = await api.post("/api/logout");
        console.log(response.data);
    };

    return (
        <>
            <button onClick={login}>Login</button>
            <button onClick={user}>User Data</button>
            <button onClick={logout}>Logout</button>
        </>
    );
}

export default App;
