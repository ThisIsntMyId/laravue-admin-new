<template>
  <div>
    <h1>Filters Component</h1>
    <el-form ref="filterForm" :model="formData">
      <el-form-item label="Name">
        <el-input v-model="formData.name" />
      </el-form-item>
      <el-form-item label="Description">
        <el-input v-model="formData.description" />
      </el-form-item>
      <el-form-item label="Price">
        <el-slider v-model="formData.price" range :min="0" :max="10000" />
      </el-form-item>
      <el-form-item label="Category">
        <el-select
          v-model="formData.category"
          multiple
          collapse-tags
          placeholder="Select some category"
        >
          <el-option
            v-for="item of categoryOptions"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="Sort">
        <el-select v-model="formData.sortField" placeholder="Select field to sort">
          <el-option
            v-for="(item, index) of ['name', 'description', 'price', 'category']"
            :key="index"
            :label="item"
            :value="item"
          />
        </el-select>
        <el-select v-model="formData.sortOrder" width="80px" placeholder="Sort order">
          <el-option
            v-for="item in sortOptions"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </el-form-item>
    </el-form>
    <el-button @click="submitFilterData">Fitler</el-button>
    <el-button @click="resetForm">Reset</el-button>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const CategoryResource = new Resource('categories');

export default {
  data() {
    return {
      filterParams: {
        name: '',
        description: '',
        price: '',
        category: '',
        sort: '',
        sortOrder: '',
      },
      formData: {
        name: '', // text
        description: '', // text
        price: 0, // range slider
        category: [], // Multiselect
        sortField: '',
        sortOrder: '',
      },
      sort: '',
      categoryOptions: [],
      sortOptions: [
        {
          value: 'asc',
          label: 'ASC',
        },
        {
          value: 'desc',
          label: 'DESC',
        },
      ],
    };
  },
  async created() {
    this.categoryOptions = await CategoryResource.list({});
  },
  methods: {
    submitFilterData() {
      const filters = Object.assign({}, this.formData);
      filters.category = this.formData.category.join(',');
      filters.price = this.formData.price.join(',');
      delete filters.sortField;
      delete filters.sortOrder;
      console.log(filters);
      const filtersQs = Object.entries(filters).map(filter => {
        return filter.join(':');
      }).join('::');
      console.log(filtersQs);

      this.$emit('filteredValues', {
        page: 1,
        filters: filtersQs,
        sort: this.formData.sortField,
        'sort-order': this.formData.sortOrder,
      });
      return;
    },
    resetForm() {
      this.$refs['filterForm'].resetFields();
    },
  },
};
</script>

<style>
.el-slider {
  margin: 25px 0px;
}
</style>
