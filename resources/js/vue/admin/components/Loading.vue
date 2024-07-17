<template>
  <div class="page-loading">
    <div class="loading">
      <i class="ri-loader-line spin-icon"></i>
    </div>
  </div>
</template>
<style scoped>
.loading {
  position: fixed;
  z-index: 999;
  height: 2em;
  width: 2em;
  overflow: show;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}
.loading:before {
  content: "";
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgb(238 238 238 / 81%);
}
.loading .spin-icon {
    font-size: 56px;
    color: #0f9cf3;
    position: relative;
    display: inline-block;
    -webkit-animation: spin 1.6s infinite linear;
    animation: spin 1.6s infinite linear;
}
</style>