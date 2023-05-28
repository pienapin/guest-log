<template>
  <div class="min-h-screen grid auto-rows-max gap-0 grid-flow-row content-between bg-gray-100">
    <!-- Navbar -->
    <div>
      <div class="navbar md:py-10 pt-5">
        <div class="navbar-start"></div>
        <div class="navbar-center max-md:flex max-md:flex-col">
          <img class="w-16 mx-5" src="@/assets/img/bps-logo.png">
          <div class="grid grid-flow-row">
            <a class="uppercase font-medium text-4xl max-md:text-2xl ff-oswald text-center">BUTEP BPS Provinsi Riau</a>
            <h3 class="uppercase font-medium text-xl max-md:text-sm ff-oswald text-center">Buku Tamu Elektronik Pengenal Wajah</h3>
          </div>
        </div>
        <div class="navbar-end"></div>
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
      <GuestForm @close="closeForm" :key="renderCount" :mounted="mounted" :kategori="kategoriList" :pengunjung="pengunjung" :descriptorDetected="detected"></GuestForm>
    </Teleport>

    <!-- Footer -->
    <div class="h-[52px]">
      <footer class="footer footer-center p-4 absolute bottom-0">
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
  ]).then(() => {
    console.log('Models loaded')
  });
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
  }, 5000);
}


const stopPolling = () => {
  clearInterval(intervalId);
} 

// function to get image from camera stream
// and recognize the face captured
const detectFace = async () => {
  stopPolling();
  console.log('detecting...');
  const video = videoRef.value;
  const canvas = canvasRef.value;

  let detectedJSON;

  // draw image from camera stream to canvas
  const context = canvas.getContext('2d', { willReadFrequently: true });
  context.drawImage(video, 0, 0, canvas.width, canvas.height);

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
      console.log('Result Exists!');

      const index = pengunjungList.findIndex(pengunjung => {
        return pengunjung.id === parseInt(result.toString(false))
      });

      pengunjung.value = pengunjungList[index];
      
    } else {
      console.log("Result Doesn't Exist!");
      pengunjung.value = null;
      detected.value = detectedJSON;
    }
    console.log(pengunjung.value);
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