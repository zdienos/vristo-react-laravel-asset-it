import { createSlice } from '@reduxjs/toolkit';

interface AuthState {
    user: object | null;
    isAuthenticated: boolean;
    loading: boolean;
}

const initialState: AuthState = {
    user: null,
    isAuthenticated: false,
    loading: true, // ⬅️ default true supaya ProtectedRoute bisa nunggu
};

const authSlice = createSlice({
    name: 'auth',
    initialState,
    reducers: {
        setUser: (state, action) => {
            state.user = action.payload;
            state.isAuthenticated = !!action.payload;
            state.loading = false;
        },
        logout: (state) => {
            state.user = null;
            state.isAuthenticated = false;
            state.loading = false;
        },
        setLoading: (state, action) => {
            state.loading = action.payload;
        },
    },
});

export const { setUser, logout, setLoading } = authSlice.actions;

export default authSlice.reducer;
