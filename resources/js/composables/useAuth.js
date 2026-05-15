import { ref } from 'vue';
import axios from 'axios';

const user = ref(null);
const checked = ref(false);

export function useAuth() {
    async function fetchUser() {
        try {
            const { data } = await axios.get('/api/me');
            user.value = data.user;
        } catch {
            user.value = null;
        } finally {
            checked.value = true;
        }
    }

    async function login(email, password) {
        await axios.get('/sanctum/csrf-cookie');
        const { data } = await axios.post('/api/login', { email, password });
        user.value = data.user;
        checked.value = true;
    }

    async function logout() {
        await axios.post('/api/logout');
        user.value = null;
    }

    return { user, checked, fetchUser, login, logout };
}
