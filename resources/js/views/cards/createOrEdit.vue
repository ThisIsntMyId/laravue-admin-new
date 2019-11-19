<template>
  <div class="app-container">
    <el-form ref="form" v-loading="loading.cardData">
      <el-form-item label="Name">
        <el-input v-model="formData.name" />
      </el-form-item>
      <el-form-item label="Description">
        <el-input v-model="formData.description" type="textarea" />
      </el-form-item>
      <el-form-item label="Price">
        <el-input v-model="formData.price" />
      </el-form-item>
      <el-form-item label="Category">
        <el-select v-model="formData.category" placeholder="please choose a category">
          <el-option
            v-for="category in categories"
            :key="category.id"
            :label="category.name"
            :value="category.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="editable ? updateCard() : createCard()">Save</el-button>
        <el-button @click="editable ? populateFormData($route.params.id) : formDataReset()">Reset</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import Resource from '@/api/resource';
// const CardResource = new Resource('cards');
const CategoryResource = new Resource('categories');

export default {
  name: 'CardView',
  data() {
    return {
      cardData: {},
      formData: {
        name: '',
        description: '',
        price: '',
        category: '',
      },
      //   validationRules: {
      //     name: [
      //       {
      //         required: true,
      //         message: 'Please input Card name',
      //         trigger: 'blur',
      //       },
      //     ],
      //     description: [
      //       {
      //         required: true,
      //         message: 'Please input Card description',
      //         trigger: 'change',
      //       },
      //       {
      //         min: 30,
      //         message: 'Description should be atleast 30 chars long',
      //         trigger: 'change',
      //       },
      //     ],
      //     category: [
      //       {
      //         required: true,
      //         message: 'Please select Card category',
      //         trigger: 'change',
      //       },
      //     ],
      //     price: [
      //       {
      //         required: true,
      //         message: 'Please input Card price',
      //         trigger: 'change',
      //       },
      //       {
      //         pattern: //,
      //         message: 'Please input Card price',
      //         trigger: 'change',
      //       },
      //     ],
      //   },
      editable: '',
      categories: [],
      loading: {
        cardData: false,
      },
    };
  },
  async created() {
    this.getCardCategories();
    if (this.$route.params.id) {
      this.editable = true;
      this.populateFormData(this.$route.params.id);
    } else {
      this.editable = false;
    }
    // await this.getCardCategories();
    // await this.getCardData(this.$route.params.id);
  },
  methods: {
    // ? Methods to get Card and Category
    // async getCardData(id) {
    //   this.loading.cardData = true;
    //   const data = await CardResource.get(id);
    //   this.cardData = data;
    //   this.cardData['category'] = this.getCategoryName(this.cardData.category);
    //   this.loading.cardData = false;
    // },
    async getCardCategories() {
      this.categories = await CategoryResource.list({});
    },
    // getCategoryName(id) {
    //   return this.categories[id - 1].name;
    // },
    createCard() {
      alert();
    },
  },
};
</script>

<style scoped>
</style>
