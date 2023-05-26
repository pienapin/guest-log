<template>
  <div class="min-h-screen">
    <!-- Navbar -->
    <div class="navbar py-10">
      <div class="navbar-start"></div>
      <div class="navbar-center">
        <img class="w-16 mx-5" src="@/assets/img/bps-logo.png">
        <div class="grid grid-flow-row">
          <a class="uppercase font-medium text-4xl ff-oswald text-center">BUTEP BPS Provinsi Riau</a>
          <h3 class="uppercase font-medium text-xl ff-oswald text-center">Buku Tamu Elektronik Pengenal Wajah</h3>
        </div>
      </div>
      <div class="navbar-end"></div>
    </div>

    <!-- Body -->
    <div class="grid grid-cols-2 mx-16 my-6 gap-12 max-h-screen">
      <div class="h-full grid items-center justify-items-center">
        <video ref="videoRef" class="rounded-lg" autoplay></video>
        <canvas class="hidden" ref="canvasRef" width="640" height="480"></canvas>
      </div>
      <div class="flex flex-col my-auto place-items-center gap-8">
        <div class="h-60">
          <img class="h-full" src="@/assets/img/face-id.png">
        </div>
        <div class="text-lg font-medium">
          <p>1. Pastikan wajah anda terlihat jelas di kamera</p>
          <p>2. Tekan tombol &nbsp;<kbd class="kbd kbd-md">ENTER</kbd>&nbsp; atau klik tombol di bawah</p>
          <p>3. Isi data yang diperlukan pada form yang muncul</p>
        </div>
        <div>
          <button @click="detectFace" class="uppercase btn btn-info text-base-100 text-lg">Pindai Wajah</button>
        </div>
      </div>
    </div>

    <!-- Form -->

    <!-- Footer -->
    <footer class="footer footer-center p-4 absolute bottom-0">
      <div>
        <p>Copyright © 2023 - All right reserved by Magang MBKM Mandiri by BPS Riau - Universitas Riau 2023</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { onBeforeMount, onBeforeUnmount, onMounted, ref } from 'vue';
import * as faceapi from 'face-api.js';
import { getPengunjungList } from '@/services/pengunjung';

  // get access to video tag within template
  const videoRef = ref();
  const canvasRef = ref();

  let pengunjungList;
  let intervalId;

  const fetchData = async () => {
    // baseApi.get('api/pengunjung')
    //   .then(results => {
    //     pengunjungList = results;
    //     console.log(pengunjungList);
    //   })
    //   .catch(error => {
    //     console.error(error);
    //   });
    pengunjungList = await getPengunjungList();
    console.log(pengunjungList);
  }

  const startPolling = () => {
    fetchData(); // Initial fetch

    // Set up a polling interval
    intervalId = setInterval(() => {
      fetchData();
    }, 5000); // Poll every 5 seconds (adjust as needed)
  }
  
  const stopPolling = () => {
    clearInterval(intervalId); // Clear the polling interval
  } 

  onMounted(() => {
    startPolling()
    
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
    ]).then(() => {
      console.log('Models loaded')
    });
  });

  onBeforeUnmount(() => {
    stopPolling();
  });
</script>

<style scoped>
  video {
    transform: scale(-1,1);
  }
  
  video::-webkit-media-controls-panel {
    transform: scale(-1,1);
  }
</style>