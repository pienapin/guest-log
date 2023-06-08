import { baseApi } from "../plugins/axios";

const api = '/api/pelayanan'

export async function getPelayananList() {
  const response = await baseApi
    .get(`${api}`);
    
  return response.data;
}

export async function getPelayananPage(page) {
  const response = await baseApi
    .get(`${api}?perPage=15&paginate=1&page=${page}`);
    
  return response.data;
}

export async function searchPelayanan(body, page) {
  let params = ""
  if (body.petugas) params += `&petugas=${body.petugas}`
  if (body.instansi) params += `&instansi=${body.instansi}`
  if (body.jabatan) params += `&jabatan=${body.jabatan}`
  if (body.tujuan) params += `&tujuan=${body.tujuan}`
  if (body.data_diminta) params += `&data_diminta=${body.data_diminta}`
  if (body.status_layanan) params += `&status_layanan=${body.status_layanan}`
  if (body.waktu_kunjungan) params += `&waktu_kunjungan=${body.waktu_kunjungan[0]}_${body.waktu_kunjungan[1]}`
  const response = await baseApi
    .get(`${api}?perPage=15&paginate=1&page=${page}${params}`);
    
  return response.data;
}

export async function getDokumentasiImg(fileName) {
  const response = await baseApi
    .get(`${api}/dokumentasi/${fileName}`);
  
  return response
}

export async function editPelayanan(body) {
  const response = await baseApi
    .post(`${api}/submit`, body, {headers: { 'content-type': 'multipart/form-data' }});
    
  return response.data;
}