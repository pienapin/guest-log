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

export async function searchPengunjung(body, page) {
  let params = ""
  if (body.keyword) params += `&keyword=${body.keyword}`
  if (body.instansi) params += `&instansi=${body.instansi}`
  if (body.jabatan) params += `&jabatan=${body.jabatan}`
  if (body.email) params += `&email=${body.email}`
  if (body.no_wa) params += `&no_wa=${body.no_wa}`
  if (body.no_hp) params += `&no_hp=${body.no_hp}`
  const response = await baseApi
    .get(`${api}?perPage=15&paginate=1\&page=${page}${params}`)
  
  return response.data
}

export async function addPengunjung(body) {
  const response = await baseApi
    .post(`${api}/submit`, body);
    
  return response.data;
}

export async function editengunjung(body) {
  const response = await baseApi
    .post(`${api}/submit`, body);
    
  return response.data;
}

export async function delPengunjung(body) {
  const response = await baseApi
    .post(`${api}/delete/${body}`);
    
  return response.data;
}
