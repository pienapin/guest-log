import { baseApi } from "../plugins/axios";

const api = '/api/pengunjung'

export async function getPengunjungList() {
  const response = await baseApi
    .get(`${api}`);
    
  return response.data;
}

export async function getPengunjungPage(page) {
  const response = await baseApi
    .get(`${api}?perPage=15&paginate=1&page=${page}`);
    
  return response.data;
}

export async function addPengunjung(body) {
  const response = await baseApi
    .post(`${api}/submit`, body);
    
  return response.data;
}
