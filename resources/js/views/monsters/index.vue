<template>
  <div class="app-container">
    <h1>View Monster Home</h1>
    <el-radio-group v-model="language" style="margin: 1rem 0;">
      <el-radio label="en">English</el-radio>
      <el-radio label="ja">Japanese</el-radio>
    </el-radio-group>
    <el-table
      ref="monstersTable"
      v-loading="loading.monstersData"
      :data="monstersData"
      style="width: 100%"
      border
      height="545px"
    >
      <el-table-column v-if="language==='en'" prop="name" label="Name" />
      <el-table-column v-else prop="japanese_name" label="Japanese Name" />
      <el-table-column v-if="language==='en'" prop="description" label="Description" />
      <el-table-column v-else prop="japanese_description" label="Japanese Description" />
      <el-table-column prop="health" label="Health" />
      <el-table-column prop="energy" label="Energy" />
      <!-- <el-table-column prop="fav_food" label="Favourite Food" /> -->
      <!-- <el-table-column prop="nature" label="Nature" /> -->
      <!-- <el-table-column prop="found_in" label="Found In Places" /> -->
      <el-table-column label="Actions">
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
    <pagination
      :total="paginationDetails.totalCards"
      :page.sync="paginationDetails.currentPage"
      :limit.sync="paginationDetails.limit"
      @pagination="updateCardsList"
    />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
// import axios from 'axios';
const MonsterResource = new Resource('monsters');

export default {
  name: 'MonsterList',
  components: {
    Pagination,
  },
  data() {
    return {
      loading: {
        monstersData: false,
      },
      monstersData: [],
      paginationDetails: {
        totalCards: 0,
        totalPages: 0,
        currentPage: 0,
        limit: 0,
      },
      language: 'en',
    };
  },
  created() {
    this.getMonstersData();
  },
  methods: {
    async getMonstersData(query) {
      this.loading.monstersData = true;
      // methods to get monster data and set pagination
      const data = await MonsterResource.list(query);
      this.monstersData = data.data;
      console.log(this.monstersData);
      // TODO: Remember to clean this
      this.paginationDetails.totalCards = parseInt(data.total);
      this.paginationDetails.currentPage = parseInt(data.current_page);
      this.paginationDetails.totalPages = parseInt(data.last_page);
      this.paginationDetails.limit = parseInt(data.per_page);
      this.loading.monstersData = false;
    },
    async updateCardsList(object) {
      console.log(object);
      await this.getMonstersData({
        page: object.page,
        limit: object.limit,
      });
    },
    handleView(index, data) {
      this.$router.push(`/monsters/view/${data.id}`);
    },
    handleEdit(index, data) {
      this.$router.push(`/monsters/edit/${data.id}`);
    },
    handleDelete(index, data) {
      MonsterResource.destroy(data.id)
        .then(res => {
          this.$message.success('Monster Deleted Successfully');
          this.getMonstersData({ page: this.paginationDetails.currentPage });
        })
        .catch(err => {
          this.$message.error(err);
        });
    },
  },
};
</script>

