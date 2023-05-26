import axios from 'axios';

// Restful API Config
axios.defaults.headers['Content-Type'] = 'application/json';

// Endpoint
const hostname = import.meta.env.VITE_BASE_API_URL;

// Instance Creation
const baseApi = axios.create({
  baseURL: hostname,
});

// Request Config
baseApi.interceptors.request.use(
  (config) => {
    return config;
  },
  (error) => {
    throw error;
  }
);

// Response Config
baseApi.interceptors.response.use(
  (response) => response.data,
  (error) => {
    throw error?.response?.error ?? error?.response?.message ?? error;
  },
);

export { baseApi };