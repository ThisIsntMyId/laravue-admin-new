<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input
        class="filter-item"
        style="width:200px; margin-right: 10px;"
        placeholder="Search"
        prefix-icon="el-icon-search"
      />
      <el-button class="filter-item" type="primary">import</el-button>
      <el-button
        class="filter-item"
        type="primary"
        :loading="loading.exportLoader"
        icon="el-icon-download"
        @click="handleExport"
      >Export</el-button>
      <el-button class="filter-item" type="primary">export selected</el-button>
      <el-button class="filter-item" type="danger">delete selected</el-button>
    </div>
    <el-row>
      <el-table
        ref="cardsTable"
        v-loading="loading.cardsData"
        :data="cardsData"
        style="width: 100%"
        border
        height="540px"
        @selection-change="handleSelectionChange"
      >
        <el-table-column type="selection" width="55" />
        <el-table-column prop="name" label="Name" sortable />
        <el-table-column prop="description" label="Description" show-overflow-tooltip sortable />
        <el-table-column prop="price" label="Price" width="100" sortable />
        <el-table-column prop="category" label="Category" sortable />
        <el-table-column label="Operations">
          <template slot-scope="scope">
            <el-button
              size="mini"
              icon="el-icon-view"
              type="primary"
              @click="handleView(scope.$index, scope.row)"
            >View</el-button>
            <el-button
              size="mini"
              icon="el-icon-edit"
              @click="handleEdit(scope.$index, scope.row)"
            >Edit</el-button>
            <el-button
              size="mini"
              icon="el-icon-delete"
              type="danger"
              @click="handleDelete(scope.$index, scope.row)"
            >Delete</el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-row>
    <el-row type="flex" justify="end">
      <pagination
        :total="paginationDetails.totalCards"
        :page.sync="paginationDetails.currentPage"
        :limit.sync="paginationDetails.limit"
        @pagination="updateCardsList"
      />
    </el-row>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
// import exportWithFieldsToExcel from './exportToExcel';
// import axios from 'axios';
const CardResource = new Resource('cards');
const CategoryResource = new Resource('categories');

export default {
  name: 'CardIndex',
  components: { Pagination },
  data() {
    return {
      cardsData: [],
      categories: [],
      paginationDetails: {
        totalCards: 0,
        totalPages: 0,
        currentPage: 0,
        limit: 0,
      },
      selectedCards: [],
      loading: {
        cardsData: false,
        exportLoader: false,
        exportSelectedLoader: false,
      },
    };
  },
  async created() {
    await this.getCardCategories();
    await this.getCardsData({ page: 1 });
  },
  methods: {
    // ? Methods to get Cards and Categories
    async getCardsData(query) {
      this.loading.cardsData = true;
      const data = await CardResource.list(query);
      this.cardsData = data.data;
      for (const card of this.cardsData) {
        // TODO: Fix card data should hold category id not category name so that the id can be reused at some other place while where name is required it should be processed
        card['category'] = this.getCategoryName(card.category);
      }
      // TODO: Remember to clean this
      this.paginationDetails.totalCards = data.total;
      this.paginationDetails.currentPage = data.current_page;
      this.paginationDetails.totalPages = data.last_page;
      this.paginationDetails.limit = data.per_page;
      this.loading.cardsData = false;
    },
    async getCardCategories() {
      this.categories = await CategoryResource.list({});
    },
    getCategoryName(id) {
      return this.categories[id - 1].name;
    },
    // ? Pagination Methods
    async updateCardsList(object) {
      await this.getCardsData({ page: object.page, limit: object.limit });
    },
    handleView(index, data) {
      this.$router.push(`/cards/view/${data.id}`);
    },
    handleEdit(index, data) {
      this.$router.push(`/cards/edit/${data.id}`);
    },
    handleDelete(index, data) {
      CardResource.destroy(data.id)
        .then(res => {
          this.$message.success('Card Deleted Successfully');
          this.getCardsData({ page: this.paginationDetails.currentPage });
        })
        .error(err => {
          this.$message.error(err);
        });
    },
    handleSelectionChange(selection) {
      this.selectedCards = selection;
    },
    handleExport() {
      const header = ['Name', 'Description', 'Price', 'Category'];
      const fields = ['name', 'description', 'price', 'category'];
      const data = this.cardsData;
      console.log(data);
      import('./exportToExcel').then(excel => {
        alert();
        console.log(excel);
        excel.exportWithFieldsToExcel(
          fields,
          header,
          this.cardsData,
          'CardsData.csv',
          this.loading.exportLoader
        );
      });
    },
  },
};
</script>

<style scoped>
.pagination-container {
  margin: 0;
}
</style>
