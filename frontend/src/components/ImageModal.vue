<template>
  <Teleport to="body">
    <input type="checkbox" :id="fileName" class="modal-toggle" />
    <div class="modal">
      <div class="modal-box max-xl:max-w-5xl">
        <label :for="fileName" class="btn btn-sm btn-circle btn-ghost absolute right-[0.5px] top-[0.5px]" @click="poll">✕</label>
        <img :id="fileName+'img'" :src="url" class="mx-auto">
      </div>
      <label :for="fileName" class="modal-backdrop" @click="poll"></label>
    </div>
  </Teleport>
</template>

<script setup>
const props = defineProps({
  url: String,
  fileName: String,
  poll: Function,
});
</script>