import { baseApi } from "../plugins/axios";
import fileDownload from "js-file-download";

const api = '/api/kunjungan'

export async function getKunjunganList() {
  const response = await baseApi
    .get(`${api}`);
    
  return response.data;
}

export async function getKunjunganPage(page, perPage) {
  const response = await baseApi
    .get(`${api}?perPage=${perPage}&paginate=1&page=${page}`);
    
  return response.data;
}

export async function searchKunjungan(body, page) {
  let params = ""
  if (body.keyword) params += `&keyword=${body.keyword}`
  if (body.instansi) params += `&instansi=${body.instansi}`
  if (body.jabatan) params += `&jabatan=${body.jabatan}`
  if (body.email) params += `&email=${body.email}`
  if (body.no_wa) params += `&no_wa=${body.no_wa}`
  if (body.no_hp) params += `&no_hp=${body.no_hp}`
  if (body.kategori) params += `&kategori=${body.kategori}`
  if (body.waktu_kunjungan) params += `&waktu_kunjungan=${body.waktu_kunjungan[0]}_${body.waktu_kunjungan[1]}`
  const response = await baseApi
    .get(`${api}?perPage=15&paginate=1&page=${page}${params}`);
    
  return response.data;
}

export async function addKunjungan(body) {
  const response = await baseApi
    .post(`${api}/submit`, body);
    
  return response.data;
}

export async function delKunjungan(body) {
  const response = await baseApi
    .delete(`${api}/delete/${body}`);
    
  return response.message;
}

export async function countKunjungan() {
  const response = await baseApi
    .get(`${api}/count`);
    
  return response.data;
}

export async function exportKunjungan(tgl) {
  const response = await baseApi
    .get(`${api}/export?waktu_kunjungan=${tgl}`, {
      responseType: 'blob',
      headers: {'Accept': 'multipart/form-data'}
    });
    const tgl_awal = tgl[0];
    let tgl_akhir;
    if (tgl[1] != "") {
      tgl_akhir = tgl[1];
    } else {
      tgl_akhir = tgl_awal;
    }
    const tgl_file = tgl_awal.replace('-', '').replace('-', '') + '_' + tgl_akhir.replace('-', '').replace('-', '');
    fileDownload(response, 'rekap_kunjungan_'+tgl_file+'.xlsx');
  return response
}