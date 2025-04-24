import axios from 'axios';

// Use environment variables for API URL or default to localhost
const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:9000';

const api = axios.create({
  baseURL: `${API_URL}/api`,
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest', // Important for Laravel to identify AJAX requests
  }
});

export const initializeCsrf = async () => {
  try {
    await axios.get(`${API_URL}/sanctum/csrf-cookie`, { 
      withCredentials: true 
    });
    console.log('CSRF cookie initialized successfully');

  } catch (error) {
    console.error('Failed to fetch CSRF cookie:', error);
  }
};

api.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Add response interceptor for handling auth errors
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
    }
    return Promise.reject(error);
  }
);

export default api;