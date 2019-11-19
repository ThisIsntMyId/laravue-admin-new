<template>
  <div class="app-container">
    <el-form ref="form" v-loading="loading.cardData" :model="formData" :rules="validationRules">
      <el-form-item label="Name" prop="name">
        <el-input v-model="formData.name" />
      </el-form-item>
      <el-form-item label="Description" prop="description">
        <el-input v-model="formData.description" type="textarea" />
      </el-form-item>
      <el-form-item label="Price" prop="price">
        <el-input v-model="formData.price" />
      </el-form-item>
      <el-form-item label="Category" prop="category">
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
        <el-button @click="editable ? getCardData($route.params.id) : formDataReset()">Reset</el-button>
      </el-form-item>
    </el-form>
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
      formData: {
        name: '',
        description: '',
        price: '',
        category: '',
      },
      validationRules: {
        name: [
          {
            required: true,
            message: 'Please input Card name',
            trigger: 'blur',
          },
        ],
        description: [
          {
            required: true,
            message: 'Please input Card description',
            trigger: 'change',
          },
          {
            min: 30,
            message: 'Description should be atleast 30 chars long',
            trigger: 'change',
          },
        ],
        category: [
          {
            required: true,
            message: 'Please select Card category',
            trigger: 'change',
          },
        ],
        price: [
          {
            required: true,
            message: 'Please input Card price',
            trigger: 'change',
          },
          {
            pattern: /^[\.\d]+$/,
            message: 'Price should only be numbers or deciamls',
            trigger: 'change',
          },
        ],
      },
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
      this.getCardData(this.$route.params.id);
    } else {
      this.editable = false;
    }
  },
  methods: {
    // ? Methods to get Card and Category
    async getCardCategories() {
      this.categories = await CategoryResource.list({});
    },
    getCategoryName(id) {
      return this.categories[id - 1].name;
    },
    // ? form related methods to reset and pupulate fields
    async getCardData(id) {
      this.loading.cardData = true;
      this.formData = await CardResource.get(id);
      console.log(this.formData);
      this.loading.cardData = false;
    },
    formDataReset() {
      this.$refs['form'].resetFields();
    },
    // ? Crud Methods
    createCard() {
      // Todo: this stmt returns promise. Try to isolate it to one function
      this.$refs['form'].validate(valid => {
        if (valid) {
          CardResource.store(this.formData)
            .then(res => {
              this.$message.success('Card Added Successfully');
              this.formDataReset();
            })
            .catch(res => {
              this.$message.error(res);
            });
        } else {
          this.$message.error('Please fill all the fields as per instructions');
        }
      });
    },
    updateCard() {
      this.$refs['form'].validate(valid => {
        if (valid) {
          const id = this.$route.params.id;
          CardResource.update(id, this.formData)
            .then(res => {
              this.$message.success('Card Updated Successfully');
              this.getCardData(id);
            })
            .catch(res => {
              this.$message.error(res);
            });
        } else {
          this.$message.error('Please fill all the fields as per instructions');
        }
      });
    },
  },
};
</script>

<style scoped>
</style>
