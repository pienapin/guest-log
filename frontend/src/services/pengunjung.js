import { baseApi } from "../plugins/axios";

const api = '/api/pengunjung'

export async function getPengunjungList() {
  const response = await baseApi
    .get(`${api}`);
    
  return response.data;
}

