import { baseApi } from "@/plugins/axios";

const api = '/api/auth';

export async function login(body) {
  const response = await baseApi
    .post(`${api}/login`, body);
    
  return response;
}

export async function logout() {
  const response = await baseApi
    .post(`${api}/logout`);
    
  return response;
}

export async function me() {
  const response = await baseApi
    .post(`${api}/me`);
    
    return response;
}