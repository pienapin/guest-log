import { baseApi } from "../plugins/axios";

const api = '/api/user'

export async function getUserList() {
  const response = await baseApi
    .get(`${api}`);
    
  return response.data;
}

export async function getUserPage(page) {
  const response = await baseApi
    .get(`${api}?perPage=15&paginate=1&page=${page}`);
    
  return response.data;
}

export async function searchUser(body, page) {
  let params = ""
  if (body.nama) params += `&nama=${body.nama}`
  if (body.nip) params += `&nip=${body.nip}`
  if (body.no_wa) params += `&no_wa=${body.no_wa}`
  if (body.email) params += `&email=${body.email}`
  const response = await baseApi
    .get(`${api}?perPage=15&paginate=1&page=${page}${params}`)
  
  return response.data
}

export async function addUser(body) {
  const response = await baseApi
    .post(`${api}/add`, body);
    
  return response.data;
}