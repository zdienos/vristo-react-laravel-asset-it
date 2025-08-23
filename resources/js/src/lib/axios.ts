import axios from 'axios';

const axiosInstance = axios.create({
    baseURL: 'http://localhost:8000', // Sesuaikan dengan URL backend Laravel Anda
    withCredentials: true,
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
});

export default axiosInstance;
