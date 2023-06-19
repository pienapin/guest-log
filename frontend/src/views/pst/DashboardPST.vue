<template>
  <div class="flex h-full flex-col">
    <div>
      <div class="grid grid-cols-4 gap-4" :key="renderCount">
        <div class="card bg-base-100 border-t-8 border-indigo-500">
          <div class="card-body items-center text-center">
            <h2 class="card-title">Kunjungan Hari ini</h2>
            <h3 class="text-5xl font-semibold mt-3">{{ data.jumlah_hari_ini }}</h3>
          </div>
        </div>
        <div class="card bg-base-100 border-t-8 border-indigo-500">
          <div class="card-body items-center text-center">
            <h2 class="card-title">Kunjungan Pekan ini</h2>
            <h3 class="text-5xl font-semibold mt-3">{{ data.jumlah_pekan_ini }}</h3>
          </div>
        </div>
        <div class="card bg-base-100 border-t-8 border-indigo-500">
          <div class="card-body items-center text-center">
            <h2 class="card-title">Kunjungan Bulan ini</h2>
            <h3 class="text-5xl font-semibold mt-3">{{ data.jumlah_bulan_ini }}</h3>
          </div>
        </div>
        <div class="card bg-base-100 border-t-8 border-indigo-500">
          <div class="card-body items-center text-center">
            <h2 class="card-title">Kunjungan Tahun ini</h2>
            <h3 class="text-5xl font-semibold mt-3">{{ data.jumlah_tahun_ini }}</h3>
          </div>
        </div>
      </div>
    </div>
    <div class="grow">
      <div class="flex h-full mt-4">
        <div class="grow max-h-full">
          <div class="card h-full bg-base-100">
            <div class="card-body items-center text-center">
              <h2 class="card-title text-2xl uppercase">Grafik Kunjungan berdasarkan Kategori</h2>
              <div class="w-[95%] h-[82%]">
                <Bar :data="chartData" :options="options" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
</template>

<script setup>
import { countKunjungan } from "@/services/kunjungan"
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js'
import { onBeforeUnmount, onMounted, ref } from "vue";
import { Bar } from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

let intervalId;
const data = ref({
  jumlah_hari_ini: 0,
  jumlah_pekan_ini: 0,
  jumlah_bulan_ini: 0,
  jumlah_tahun_ini: 0,
});
let chartData
const renderCount = ref(0);

const fetchData = async () => {
  data.value = await countKunjungan();
  renderCount.value += 1;
}

onMounted(() =>{
  startPolling();
});

onBeforeUnmount(() => {
  stopPolling();
});




/* ==================== functions ==================== */
const startPolling = () => {
  fetchData();
  renderCount.value += 1;
  intervalId = setInterval(() => {
    fetchData();
    chartData = {
      labels: data.value.kategori_label,
      datasets: [{
        label: 'Kunjungan',
        data: data.value.kategori_jumlah,
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(255, 159, 64, 0.2)',
          'rgba(255, 205, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(201, 203, 207, 0.2)'
        ],
        borderColor: [
          'rgb(255, 99, 132)',
          'rgb(255, 159, 64)',
          'rgb(255, 205, 86)',
          'rgb(75, 192, 192)',
          'rgb(54, 162, 235)',
          'rgb(153, 102, 255)',
          'rgb(201, 203, 207)'
        ],
        borderWidth: 1
      }]
    }
    renderCount.value += 1;
  }, 3000);
}

chartData = {
      labels: [],
      datasets: [{
        label: 'Kunjungan',
        data: [],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(255, 159, 64, 0.2)',
          'rgba(255, 205, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(201, 203, 207, 0.2)'
        ],
        borderColor: [
          'rgb(255, 99, 132)',
          'rgb(255, 159, 64)',
          'rgb(255, 205, 86)',
          'rgb(75, 192, 192)',
          'rgb(54, 162, 235)',
          'rgb(153, 102, 255)',
          'rgb(201, 203, 207)'
        ],
        borderWidth: 1
      }]
    }

const stopPolling = () => {
  clearInterval(intervalId);
}

const options = {
  responsive: true,
  maintainAspectRatio: false,
}

</script>