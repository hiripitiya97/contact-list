import axios from 'axios'
import router from './router'

axios.defaults.baseURL = '/api';
axios.defaults.withCredentials = true;

axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            localStorage.removeItem('token')
            router.push('/')
        }
        return Promise.reject(error)
    }
)

export default axios
