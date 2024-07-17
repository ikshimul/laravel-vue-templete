<template>
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <slot name="header"></slot>
        </tr>
      </thead>
      <tbody>
        <slot name="body"></slot>
      </tbody>
    </table>
  </div>
</template>
  
  <script>
export default {
  name: "TableCompnent",
};
</script>
  
<style scoped>
.table-responsive {
  margin-top: 20px;
}
tbody, td, tfoot, th, thead, tr{
  /* text-align: center; */
  vertical-align: middle;
}
</style>
  