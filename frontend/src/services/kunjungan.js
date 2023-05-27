import { baseApi } from "../plugins/axios";

const api = '/api/kunjungan'

export async function getKunjunganList() {
  const response = await baseApi
    .get(`${api}`);
    
  return response.data;
}

export async function addKunjungan(body) {
  const response = await baseApi
    .post(`${api}/submit`, body);
    
  return response.data;
}
