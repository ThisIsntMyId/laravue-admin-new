<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input
        v-model="searchQuerry"
        class="filter-item"
        style="width:200px; margin-right: 10px;"
        placeholder="Search"
        prefix-icon="el-icon-search"
        clearable
        @keydown.enter.native="handleGlobalSearch"
      />
      <el-button class="filter-item" type="primary">import</el-button>
      <el-dropdown
        v-loading="loading.exportLoader"
        class="filter-item"
        style="margin-right: 10px; margin-left: 10px;"
        @command="handleExport"
      >
        <span class="el-dropdown-link">
          <el-button type="primary" icon="el-icon-download">
            Export
            <i class="el-icon-caret-bottom" />
          </el-button>
        </span>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item command="all-excel">Export all to excel</el-dropdown-item>
          <el-dropdown-item command="current-excel">Export current page to excel</el-dropdown-item>
          <el-dropdown-item command="selected-excel">Export selected to excel</el-dropdown-item>
          <el-dropdown-item command="all-csv" divided>Export all to csv</el-dropdown-item>
          <el-dropdown-item command="current-csv">Export current page to csv</el-dropdown-item>
          <el-dropdown-item command="selected-csv">Export selected to csv</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
      <el-button
        class="filter-item"
        type="danger"
        icon="el-icon-delete"
        @click="clearSelected"
      >Clear Selection</el-button>
      <el-button
        v-loading="loading.deleteSelected"
        class="filter-item"
        type="danger"
        icon="el-icon-delete"
        @click="handleDeleteSelected"
      >Delete Selected</el-button>
      <el-checkbox
        v-model="showId"
        class="filter-item"
        style="margin-right: 10px; margin-left: 10px;"
      >Show Id</el-checkbox>
      <el-button class="filter-item" type="primary" @click="dialog.filters = true">Filters</el-button>
    </div>
    <el-row>
      <el-table
        ref="cardsTable"
        v-loading="loading.cardsData"
        :data="cardsData"
        style="width: 100%"
        border
        height="545px"
        :row-key="getRowKeys"
        @selection-change="handleSelectionChange"
        @sort-change="handleSortChange"
        @filter-change="handleGlobalFilterChange"
      >
        <el-table-column type="selection" width="55" reserve-selection />
        <el-table-column v-if="showId" prop="id" label="id" width="70" sortable column-key="id" />
        <el-table-column
          prop="name"
          label="Name"
          :render-header="renderNameHeader"
          column-key="name"
        />
        <el-table-column
          prop="description"
          label="Description"
          show-overflow-tooltip
          :render-header="renderNameHeader"
          column-key="description"
        />
        <el-table-column
          prop="price"
          label="Price"
          width="100"
          sortable="custom"
          :filters="filters.price"
          column-key="price"
        />
        <!-- ? Remember to use formater attribute to display the category name -->
        <el-table-column
          prop="category"
          label="Category"
          sortable="custom"
          :filters="categoryFilterValues"
          column-key="category"
        />
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
    <el-dialog title="Tips" :visible.sync="dialog.filters">
      <FiltersComponent @filteredValues="assignFilterValues" />
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import FiltersComponent from './FiltersComponent';
import Resource from '@/api/resource';
import axios from 'axios';
import { exportWithFieldsToExcel, exportCSVFile } from './exports';
const CardResource = new Resource('cards');
const CategoryResource = new Resource('categories');

export default {
  name: 'CardIndex',
  components: { Pagination, FiltersComponent },
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
      currentSort: {
        field: '',
        order: 'asc',
      },
      selectedCards: [],
      filters: {
        price: [
          {
            text: '0-2500 Rs',
            value: '0,2500',
          },
          {
            text: '2500-5000 Rs',
            value: '2500,5000',
          },
          {
            text: '5000-7500 Rs',
            value: '5000,7500',
          },
          {
            text: '7500-10000 Rs',
            value: '7500,10000',
          },
        ],
        name: '',
        description: '',
      },
      filterParams: {},
      unfilteredData: [], // ? Temporary to store previous data to revert to unfiltered values locally
      loading: {
        cardsData: false,
        exportLoader: false,
        deleteSelected: false,
      },
      showId: false,
      popoverVisible: false,
      dialog: {
        filters: false,
      },
      searchQuerry: '',
    };
  },
  computed: {
    categoryFilterValues() {
      return this.categories.map(cat => {
        return {
          text: cat.name,
          value: cat.id,
        };
      });
    },
    filterParamsString: {
      cache: false,
      get() {
        if (this.filterParams === {}) {
          return '';
        } else if (typeof this.filterParams === 'string') {
          return this.filterParams;
        } else {
          return Object.entries(this.filterParams)
            .filter(filter => filter[1].length > 0)
            .map(filter => {
              return filter.join(':');
            })
            .join('::');
        }
      },
    },
  },
  watch: {
    async searchQuerry(newVal, oldVal) {
      await this.handleGlobalSearch();
    },
  },
  async created() {
    await this.getCardCategories();
    await this.getCardsData({ page: 1 });
  },
  methods: {
    renderNameHeader(h, { column, $index }) {
      return (
        <span>
          {column.label}
          <span class='caret-wrapper'>
            <i
              class='sort-caret ascending'
              on-click={$event => {
                this.handleClickSortIcon($event, column, {
                  prop: column.property,
                  order: 'ascending',
                });
              }}
            ></i>
            <i
              class='sort-caret descending'
              on-click={$event => {
                this.handleClickSortIcon($event, column, {
                  prop: column.property,
                  order: 'descending',
                });
              }}
            ></i>
          </span>
          <el-popover
            class='custom-filter'
            placement='bottom'
            transition='el-zoom-in-top'
            trigger='click'
            content={column.label}
          >
            <div style='padding: 12px;'>
              <el-input
                placeholder='Enter Text Here'
                prefix-icon='el-icon-search'
                value={this.filters[column.property]}
                onInput={e => (this.filters[column.property] = e)}
              />
            </div>
            <div class='el-table-filter__bottom'>
              <button
                type='text'
                disabled={this.filters[column.property].length <= 0}
                class={this.filters[column.property] ? '' : 'is-disabled'}
                on-click={$event => {
                  this.handleCustomFilterConfirm(column.property, column);
                }}
              >
                Confirm
              </button>
              <button
                type='text'
                on-click={$event => {
                  this.handleCustomFilterReset(column.property, column);
                }}
              >
                Reset
              </button>
            </div>
            <span class='el-table__column-filter-trigger' slot='reference'>
              <i class='el-icon-arrow-down'></i>
            </span>
          </el-popover>
        </span>
      );
    },
    alertMethod(val = 'nul') {
      alert(val);
    },
    // ? Methods to get Cards and Categories
    async getCardsData(query) {
      this.loading.cardsData = true;
      let data = '';
      if (this.searchQuerry) {
        data = await axios.get(
          `http://127.0.0.1:8000/api/cards/search?q=${this.searchQuerry}&limit=${query.limit}&page=${query.page}`
        );
        data = data.data;
      } else {
        data = await CardResource.list(query);
      }
      this.cardsData = data.data;
      for (const card of this.cardsData) {
        // TODO: Fix card data should hold category id not category name so that the id can be reused at some other place while where name is required it should be processed
        card['category'] = this.getCategoryName(card.category);
      }
      // TODO: Remember to clean this
      this.paginationDetails.totalCards = parseInt(data.total);
      this.paginationDetails.currentPage = parseInt(data.current_page);
      this.paginationDetails.totalPages = parseInt(data.last_page);
      this.paginationDetails.limit = parseInt(data.per_page);
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
      await this.getCardsData({
        page: object.page,
        limit: object.limit,
        filters: this.filterParamsString,
        sort: this.currentSort.field,
        'sort-order': this.currentSort.order,
      });
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
    clearSelected() {
      this.$refs['cardsTable'].clearSelection();
    },
    getRowKeys(row) {
      return row.id;
    },
    // ? method to handle and get sorted fields from backend
    // FIXME: Only change the color of the icon that has been changed and revert color of all other icons
    async handleSortChange(change) {
      this.currentSort.field = change.prop;
      if (change.order === 'ascending') {
        this.currentSort.order = 'asc';
      } else if (change.order === 'descending') {
        this.currentSort.order = 'desc';
      }
      await this.getCardsData({
        page: this.paginationDetails.currentPage,
        limit: this.paginationDetails.limit,
        sort: this.currentSort.field,
        filters: this.filterParamsString,
        'sort-order': this.currentSort.order,
      });
    },
    async handleClickSortIcon(event, column, change) {
      event.stopPropagation();
      let element = event.target;
      while (element.tagName !== 'TH') {
        element = element.offsetParent;
      }
      if (change.order === 'ascending') {
        element.classList.remove('descending');
        element.classList.toggle('ascending');
      } else {
        element.classList.toggle('descending');
        element.classList.remove('ascending');
      }
      await this.handleSortChange(change);
    },
    // FIXME: Its lagging a bit
    handleDeleteSelected() {
      this.loading.deleteSelected = true;
      if (this.selectedCards.length <= 0) {
        this.$message.error('Please select some fields');
      } else {
        const ids = this.selectedCards.map(cards => cards.id);
        axios
          .delete('/api/deletemanycards', { data: { ids: ids }})
          .then(async res => {
            this.$message.success('Delete success Full');
            await this.getCardsData({
              page: this.paginationDetails.currentPage,
            });
            if (this.cardsData.length === 0) {
              await this.getCardsData({
                page: this.paginationDetails.currentPage - 1,
              });
            }
          });
      }
      this.loading.deleteSelected = false;
    },
    async handleExport(command) {
      // ! Data provided here is not filtered for csv, i.e. ',' are not removed
      this.loading.exportLoader = true;
      const fileName = 'CardsData';
      const header = ['Id', 'Name', 'Description', 'Price', 'Category'];
      const fields = ['id', 'name', 'description', 'price', 'category'];
      const dataAmt = command.split('-')[0];
      const fileType = command.split('-')[1];
      let data = {};
      if (dataAmt === 'all') {
        data = await CardResource.list({ limit: -1 });
        for (const card of data) {
          card.category = this.getCategoryName(card.category);
        }
      } else if (dataAmt === 'current') {
        data = this.cardsData;
      } else {
        data = this.selectedCards;
      }
      if (fileType === 'excel') {
        exportWithFieldsToExcel(header, fields, data, fileName);
      } else {
        exportCSVFile(header, fields, data, fileName);
      }
      this.loading.exportLoader = false;
    },
    // // ? Filter Methods
    // handlePriceFilter(value, row, column) {
    //   return value.max > row.price && row.price >= value.min;
    // },
    // handleCategoryFilter(value, row, column) {
    //   return value === row.category;
    // },
    handleCustomFilterConfirm(colName, column) {
      // TODO: Make press enter to search, whenever clicked outside popover should be disabled
      // this.unfilteredData = this.cardsData;
      column.filteredValue = this.filters[column.property];
      this.$refs['cardsTable'].$emit('filter-change', {
        [colName]: this.filters[colName],
      });
      // ?// Call the server here for filtered data
      // this.cardsData = this.cardsData.filter(card =>
      // card[colName]
      // .toLowerCase()
      // .includes(this.filters[colName].toLowerCase())
      // );
    },
    handleCustomFilterReset(colName, column) {
      this.filters[colName] = '';
      column.filteredValue = [];
      this.$refs['cardsTable'].$emit('filter-change', {
        [colName]: this.filters[colName],
      });
      // if (this.unfilteredData.length > 0) {
      //   this.cardsData = this.unfilteredData;
      //   this.unfilteredData = '';
      // }
      // TODO: To unfilter the data locally and globally
    },
    async handleGlobalFilterChange(filter) {
      const colName = Object.keys(filter)[0];
      let filterParam = '';
      if (colName === 'category') {
        filterParam = filter.category.join(',') || [];
      } else {
        filterParam = Object.values(filter)[0];
      }
      this.filterParams[colName] = filterParam;
      await this.getCardsData({
        filters: this.filterParamsString,
        sort: this.currentSort.field,
        'sort-order': this.currentSort.order,
      });
    },
    async assignFilterValues(filterParams) {
      this.filterParams = filterParams.filters;
      this.currentSort.field = filterParams.sortField || this.currentSort.field;
      this.currentSort.order = filterParams.sortOrder || this.currentSort.order;
      await this.getCardsData({
        filters: this.filterParams,
        sort: this.currentSort.field,
        'sort-order': this.currentSort.order,
      });
    },
    // ? Search Functions
    async handleGlobalSearch() {
      await this.getCardsData({ limit: 10, page: 1 });
    },
  },
};
</script>

// FIXME: Scoped styles are not working
<style>
.pagination-container {
  margin: 0;
}
.el-popover {
  padding: 0;
  border-radius: 0;
}

.el-popover .popper-arrow {
  display: none;
}
</style>
