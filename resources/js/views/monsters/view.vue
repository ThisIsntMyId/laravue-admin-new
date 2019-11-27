<template>
  <div class="app-container">
    <el-row>
      <el-col :span="24">
        <h1>Monster {{ monsterData.name }}'s Details</h1>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="8">
        <el-row>
          <details-card :monster-data="monsterData" />
        </el-row>
        <el-row style="margin-top: 1rem">
          <stats-card :monster-data="monsterData" />
        </el-row>
      </el-col>
      <el-col :span="16">
        <other-details
          v-loading="loading.monsterArrayData"
          style="margin-left: 1rem;"
          :fav-foods="foodsArr"
          :natures="naturesArr"
          :locations="locationsArr"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import DetailsCard from './components/DetailsCard.vue';
import StatsCard from './components/StatsCard';
import OtherDetails from './components/OtherDetails';
const MonsterResource = new Resource('monsters');
import axios from 'axios';

export default {
  name: 'ViewMonster',
  components: {
    DetailsCard,
    StatsCard,
    OtherDetails,
  },
  data() {
    return {
      monsterData: {},
      loading: {
        monsterData: false,
        monsterArrayData: false,
      },
      language: {
        detail: 'en',
      },
      foodsArr: [],
      naturesArr: [],
      locationsArr: [],
    };
  },
  async created() {
    await this.getMonsterData(this.$route.params.id);
    console.log(this.monsterData);
    await this.getMonstersArrayData();
  },
  methods: {
    async getMonsterData(id) {
      this.loading.monsterData = true;
      const data = await MonsterResource.get(id);
      this.monsterData = data;
      this.loading.monsterData = false;
    },
    sortArrayByOrder(array, order) {
      /*
       * sorts the given array on the basis of given order
       * @params
       * array => array to be sorted
       * order => string of ids according to which array needs to be sorted
       *
       * returns sorted array
       *
       */
      const orderArr = JSON.parse(`[${order}]`);
      const sortedArr = [];
      orderArr.forEach(id => {
        sortedArr.push(array.find(el => el.id === id));
      });
      return sortedArr;
    },
    async getMonstersArrayData() {
      this.loading.monsterArrayData = true;
      const localfoodsArr = (await axios.get(
        `http://127.0.0.1:8000/api/foods?ids=${this.monsterData.fav_food}`
      )).data;
      const localnaturesArr = (await axios.get(
        `http://127.0.0.1:8000/api/natures?ids=${this.monsterData.nature}`
      )).data;
      const locallocationsArr = (await axios.get(
        `http://127.0.0.1:8000/api/locations?ids=${this.monsterData.found_in}`
      )).data;
      this.foodsArr = this.sortArrayByOrder(
        localfoodsArr,
        this.monsterData.fav_food
      );
      this.naturesArr = this.sortArrayByOrder(
        localnaturesArr,
        this.monsterData.nature
      );
      this.locationsArr = this.sortArrayByOrder(
        locallocationsArr,
        this.monsterData.found_in
      );
      this.loading.monsterArrayData = false;
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
