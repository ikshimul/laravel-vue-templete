<template>
    <nav aria-label="Page navigation example">
      <ul class="pagination pagination-rounded">
        <li
          class="paginate_button page-item previous"
          :class="{ disabled: currentPage === 1 }"
        >
          <a @click="prevPage" class="page-link cursor-pointer">
            <i class="mdi mdi-chevron-left"></i>
          </a>
        </li>
        <li v-if="showFirst">
          <a class="page-link cursor-pointer" @click="gotoPage(1)">1</a>
        </li>
        <li v-if="showLeftEllipsis">
          <span class="page-link">...</span>
        </li>
        <li
          v-for="pageNumber in visiblePages"
          :key="pageNumber"
          :class="{ active: pageNumber === currentPage }"
          class="paginate_button page-item"
        >
          <a class="page-link cursor-pointer" @click="gotoPage(pageNumber)">
            {{ pageNumber }}
          </a>
        </li>
        <li v-if="showRightEllipsis">
          <span class="page-link">...</span>
        </li>
        <li v-if="showLast">
          <a class="page-link cursor-pointer" @click="gotoPage(totalPages)">
            {{ totalPages }}
          </a>
        </li>
        <li
          class="paginate_button page-item next"
          :class="{ disabled: currentPage === totalPages }"
        >
          <a @click="nextPage" class="page-link cursor-pointer">
            <i class="mdi mdi-chevron-right"></i>
          </a>
        </li>
      </ul>
    </nav>
  </template>
  
  <script>
  export default {
    props: {
      currentPage: {
        type: Number,
        required: true,
      },
      totalPages: {
        type: Number,
        required: true,
      },
    },
    computed: {
      visiblePages() {
        const maxVisiblePages = 5;
        let startPage = Math.max(this.currentPage - Math.floor(maxVisiblePages / 2), 1);
        let endPage = startPage + maxVisiblePages - 1;
  
        if (endPage > this.totalPages) {
          endPage = this.totalPages;
          startPage = Math.max(endPage - maxVisiblePages + 1, 1);
        }
  
        const pages = [];
        for (let i = startPage; i <= endPage; i++) {
          pages.push(i);
        }
        return pages;
      },
      showFirst() {
        return this.visiblePages[0] > 1;
      },
      showLeftEllipsis() {
        return this.visiblePages[0] > 2;
      },
      showRightEllipsis() {
        return this.visiblePages[this.visiblePages.length - 1] < this.totalPages - 1;
      },
      showLast() {
        return this.visiblePages[this.visiblePages.length - 1] < this.totalPages;
      },
    },
    methods: {
      gotoPage(pageNumber) {
        this.$emit("page-changed", pageNumber);
      },
      prevPage() {
        if (this.currentPage > 1) {
          this.gotoPage(this.currentPage - 1);
        }
      },
      nextPage() {
        if (this.currentPage < this.totalPages) {
          this.gotoPage(this.currentPage + 1);
        }
      },
    },
  };
  </script>
  
  <style>
  .cursor-pointer {
    cursor: pointer;
  }
  .page-link.disabled {
    color: #ced4da;
    pointer-events: none;
    background-color: #fff;
    border-color: #ced4da;
  }
  </style>
  