<template>
  <div class="d-flex justify-content-center" v-if="active">
    <div class="spinner-border text-danger" role="status">
      <span class="sr-only">Loading...</span>
    </div>
    <div class="loading-text cssload-loader">{{ text }}</div>
  </div>
</template>

<script>
import { clearInterval } from "timers";

export default {
  props: {
    active: {
      type: Boolean,
      default: false,
    },
    text: {
      type: String,
      default: "Đang xử lý...",
    },
  },
};
</script>

<style scoped language="scss">
.d-flex.justify-content-center {
  position: fixed;
  width: 100%;
  height: 100%;
  z-index: 100;
  background: #3e3f4078;
  top:0;
  left: 0;
}
.spinner-border {
  margin-top: 20%;
  position: absolute;
}
.loading-text {
  color: #fff;
  margin-top: 20%;
}
</style>