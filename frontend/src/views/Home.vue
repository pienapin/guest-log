<template>
  <div class="min-h-screen grid auto-rows-max gap-0 grid-flow-row content-between bg-gray-100">
    <div id="submitSuccess" class="alert alert-success w-[90%] justify-center flex mx-auto absolute left-0 right-0 top-5 shadow-lg z-[3] hidden">
      <div class="flex items-center align-middle justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span class="font-medium text-lg ms-3">Kunjungan anda telah tercatat! Terima kasih sudah berkunjung! Selamat datang di BPS Provinsi Riau!</span>
      </div>
    </div>
    <!-- Navbar -->
    <div>
      <div class="navbar py-3 px-5">
        <div class="navbar-start max-md:flex max-md:flex-col">
          <img class="w-14 mx-5" src="@/assets/img/bps-logo.png">
          <div class="grid grid-flow-row">
            <a class="uppercase font-medium text-2xl ff-oswald text-center">BUTEP BPS Provinsi Riau</a>
            <h3 class="uppercase font-medium text-sm ff-oswald text-center">Buku Tamu Elektronik Pengenal Wajah</h3>
          </div>
        </div>
        <div class="navbar-center max-md:flex max-md:flex-col">
          
        </div>
        <div class="navbar-end max-md:flex max-md:flex-col">
          <div class="dropdown dropdown-end lg:hidden">
            <label tabindex="0" class="btn btn-ghost">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /></svg>
            </label>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
              <li><router-link to="/">Beranda</router-link></li>
              <li><router-link to="/isi">Isi Buku Tamu</router-link></li>
              <li><router-link to="/kunjungan">Daftar Kunjungan</router-link></li>
            </ul>
          </div>
          <ul class="menu menu-horizontal px-1 hidden lg:flex">
            <li><router-link to="/" class="text-lg">Beranda</router-link></li>
            <li><router-link to="/isi" class="text-lg">Isi Buku Tamu</router-link></li>
            <li><router-link to="/kunjungan" class="text-lg">Daftar Kunjungan</router-link></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Body -->
    <div class="mb-1">
      <div class="grid md:grid-cols-2 max-md:grid-rows-2 md:mx-16 mx-5 my-6 md:gap-12 max-h-screen">
        <div class="h-full w-full grid items-center justify-items-center">
          <video ref="videoRef" class="rounded-lg" autoplay></video>
          <canvas class="hidden" ref="canvasRef" width="640" height="480"></canvas>
        </div>
        <div class="flex flex-col my-auto place-items-center gap-8">
          <div class="max-md:hidden h-60">
            <img class="h-full" src="@/assets/img/face-id.png">
          </div>
          <div class="md:text-lg max-md:text-sm font-semibold">
            <p>1. Pastikan wajah anda terlihat jelas di kamera</p>
            <p>2. Tekan tombol &nbsp;<kbd class="kbd kbd-md">ENTER</kbd>&nbsp; atau klik tombol di bawah</p>
            <p>3. Isi data yang diperlukan pada form yang muncul</p>
          </div>
          <div>
            <button @click="detectFace" class="uppercase btn btn-info text-base-100 md:text-lg max-md:text-md">Pindai Wajah</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Form -->
    <Teleport to="body">
      <GuestForm @close="closeForm" :key="renderCount" :mounted="mounted" :kategori="kategoriList" :pengunjung="pengunjung" :descriptorDetected="detected" :imgCaptured="imgCaptured"></GuestForm>
    </Teleport>

    <!-- Footer -->
    <div class="h-[32px]">
      <footer class="footer footer-center p-3 absolute bottom-0">
        <div>
          <p>Copyright © 2023 - All right reserved by Magang MBKM Mandiri by BPS Riau - Universitas Riau 2023</p>
        </div>
      </footer>
    </div>
  </div>
</template>

<script setup>
/* ==================== imports ==================== */
import { onBeforeUnmount, onMounted, ref } from 'vue';
import * as faceapi from 'face-api.js';
import { getPengunjungList } from '@/services/pengunjung';
import { getKategoriList } from '@/services/kategori';
import GuestForm from '@/components/GuestForm.vue';




/* ==================== variables ==================== */
// get access to video tag within template
const videoRef = ref();
const canvasRef = ref();

// declare faceMatcher for face recognition
let faceMatcher;
const pengunjung = ref(null);
const detected = ref();
const imgCaptured = ref();

let pengunjungList;
let kategoriList;
let intervalId;
const renderCount = ref(0);
const mounted = ref(false);




/* ==================== lifecycles ==================== */
onMounted(() => {
  startPolling();
  const video = videoRef.value;
  navigator.mediaDevices.getUserMedia({ video: true })
  .then(stream => {
    video.srcObject = stream;
  });
  
  // Load face detection and recognition models
  Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
    faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
    faceapi.nets.faceExpressionNet.loadFromUri('/models'),
    faceapi.nets.ssdMobilenetv1.loadFromUri('/models')
  ]).then(() => {});
});

onBeforeUnmount(() => {
  stopPolling();
});




/* ==================== functions ==================== */
const fetchData = async () => {
  pengunjungList = await getPengunjungList();
  kategoriList = await getKategoriList();
}


const startPolling = () => {
  fetchData();
  intervalId = setInterval(() => {
    fetchData();
    document.getElementById("submitSuccess").classList.add("hidden")
  }, 8000);
}


const stopPolling = () => {
  clearInterval(intervalId);
} 

// function to get image from camera stream
// and recognize the face captured
const detectFace = async () => {
  stopPolling();
  const video = videoRef.value;
  const canvas = canvasRef.value;

  let detectedJSON;

  // draw image from camera stream to canvas
  const context = canvas.getContext('2d', { willReadFrequently: true });
  context.drawImage(video, 0, 0, canvas.width, canvas.height);

  // save image
  let imagebase64data = canvas.toDataURL("image/png");
  imgCaptured.value = imagebase64data;

  // Send canvas image to face-api.js for processing
  const detection = await faceapi.detectSingleFace(canvas).withFaceLandmarks().withFaceDescriptor();
  let result = null;
  if (detection) {
    // console.log('Face detected!');
    
    // get detected face descriptor
    const detectedDescriptor = detection.descriptor;
    detectedJSON = JSON.stringify([detectedDescriptor]);
    
    // make faceMatcher based on face from database
    if (pengunjungList.length > 0) {
      const labeledDescriptors = pengunjungList.map(pengunjung => {
        const descriptorObject = JSON.parse(pengunjung.descriptors);
        const descriptorArray = Object.values(descriptorObject[0]);
        const descriptor = new Float32Array(descriptorArray);
        return new faceapi.LabeledFaceDescriptors(pengunjung.id.toString(), [descriptor]);
      })
      faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.5)
      
      
      // compare detected descriptor with face from database
      result = faceMatcher.findBestMatch(detectedDescriptor);
    } else {
      result = null;
    }
    
    if (result && result.toString(false) != "unknown") {

      const index = pengunjungList.findIndex(pengunjung => {
        return pengunjung.id === parseInt(result.toString(false))
      });
      
      pengunjung.value = pengunjungList[index];
      detected.value = detectedJSON;
      
    } else {
      pengunjung.value = null;
      detected.value = detectedJSON;
    }
    showForm();
  } else {
    alert('No face');
  }
}

// event listener, press enter to call detectFace function
const enterToDetect = ({ code }) => {
  if (code === "Enter") {
    detectFace();
  }
}

document.addEventListener('keypress', enterToDetect, true);

// form function
const showForm = () => {
  renderCount.value += 1;
  mounted.value = true;
}

const closeForm = () => {
  renderCount.value += 1;
  mounted.value = false;
  startPolling();
}
</script>

<style scoped>
  video {
    transform: scale(-1,1);
    /* override other styles to make responsive */
    width: 95%    !important;
    height: auto   !important;
  }
  
  video::-webkit-media-controls-panel {
    transform: scale(-1,1);
  }
</style>