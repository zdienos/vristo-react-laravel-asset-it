import axios from "axios";
import store from "@/store";
import { setUser, setLoading } from "@/store/authSlice";

const axiosInstance = axios.create({
    baseURL: "http://localhost:8000", // URL backend Laravel
    withCredentials: true,            // penting untuk kirim cookie session
    headers: { "X-Requested-With": "XMLHttpRequest" },
});

// Interceptor Response → handle error global
// axiosInstance.interceptors.response.use(
//     (response) => response,
//     (error) => {
//         if (error.response?.status === 401) {
//             // Clear auth state di redux
//             store.dispatch(setUser(null));
//             store.dispatch(setLoading(false));

//             // Redirect ke login
//             if (window.location.pathname !== "/auth/signin") {
//                 window.location.href = "/auth/signin";
//             }
//         }
//         return Promise.reject(error);
//     }
// );

export default axiosInstance;
