import { baseApi } from "../plugins/axios";

const api = '/api/kategori'

export async function getKategoriList() {
  const response = await baseApi
    .get(`${api}`);
    
  return response;
}

export async function addKategori(body) {
  const response = await baseApi
    .post(`${api}/add`, body);
    
  return response.data;
}

export async function delKategori(body) {
  const response = await baseApi
    .post(`${api}/delete`, body);
    
  return response.message;
}
