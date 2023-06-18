import { baseApi } from "../plugins/axios";
import fileDownload from "js-file-download";

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

export async function exportPelayanan(tgl) {
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
    fileDownload(response, 'rekap_pelayanan_'+tgl_file+'.xlsx');
  return response
}