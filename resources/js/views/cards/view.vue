<template>
  <div class="app-container">
    <h1 style="margin-bottom: 0;">Cards</h1>
    <el-row>
      <h3>{{ cardData.name }}</h3>
      <h3>{{ cardData.description }}</h3>
      <h3>{{ cardData.category }}</h3>
      <h3>{{ cardData.price }}</h3>
    </el-row>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const CardResource = new Resource('cards');
const CategoryResource = new Resource('categories');

export default {
  name: 'CardView',
  data() {
    return {
      cardData: {},
      categories: [],
      loading: {
        cardData: false,
      },
    };
  },
  async created() {
    await this.getCardCategories();
    await this.getCardData(this.$route.params.id);
  },
  methods: {
    // ? Methods to get Card and Category
    async getCardData(id) {
      this.loading.cardData = true;
      const data = await CardResource.get(id);
      this.cardData = data;
      this.cardData['category'] = this.getCategoryName(this.cardData.category);
      this.loading.cardData = false;
    },
    async getCardCategories() {
      this.categories = await CategoryResource.list({});
    },
    getCategoryName(id) {
      return this.categories[id - 1].name;
    },
  },
};
</script>

<style scoped>
</style>
