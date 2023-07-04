import { baseApi } from "../plugins/axios";
import fileDownload from "js-file-download";

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

export async function editPengunjung(body) {
  const response = await baseApi
    .post(`${api}/submit`, body);
    
  return response.data;
}

export async function delPengunjung(body) {
  const response = await baseApi
    .post(`${api}/delete/${body}`);
    
  return response.data;
}

export async function exportPengunjung(search) {
  let params = ""
  if (search.keyword) params += `&keyword=${search.keyword}`
  if (search.instansi) params += `&instansi=${search.instansi}`
  if (search.jabatan) params += `&jabatan=${search.jabatan}`
  
  const response = await baseApi
  .get(`${api}/export?${params}`, {
    responseType: 'blob',
    headers: {'Accept': 'multipart/form-data'}
  });
  
  let nama_file = ""
  if (search.keyword) nama_file += `_nama-${search.keyword}`
  if (search.instansi) nama_file += `_intansi-${search.instansi}`
  if (search.jabatan) nama_file += `_jabatan-${search.jabatan}`

  fileDownload(response, 'rekap_pengunjung'+nama_file+'.xlsx');
  return response
}