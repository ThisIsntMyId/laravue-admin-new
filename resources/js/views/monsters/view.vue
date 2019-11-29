<template>
  <div class="app-container">
    <el-row style="display: flex; align-items: center;">
      <el-col :span="24">
        <h1 style="display: inline-block;">{{ $t('ViewMonster.title', {name: monsterData.name[language]}) }}</h1>
        <!-- <h1 style="display: inline-block;">Monster {{ monsterData.name.en }}'s Details</h1> -->
      </el-col>
      <el-col>
        <el-radio-group v-model="language" style="float: right;" size="mini">
          <el-radio-button label="en">English</el-radio-button>
          <el-radio-button label="ja">Japanese</el-radio-button>
        </el-radio-group>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="8">
        <el-row>
          <details-card :monster-data="monsterData" :language="language" />
        </el-row>
        <el-row style="margin-top: 1rem">
          <stats-card :monster-data="monsterData" :language="language" />
        </el-row>
      </el-col>
      <el-col :span="16">
        <other-details
          v-loading="loading.monsterArrayData"
          style="margin-left: 1rem;"
          :fav-foods="foodsArr"
          :natures="naturesArr"
          :locations="locationsArr"
          :language="language"
        />
        <el-card class="actions">
          <el-row>
            <el-col :span="3" class="action-button-container">
              <el-button @click="$router.push(`/monsters/edit/${monsterData.id}`)">Edit</el-button>
            </el-col>
            <el-col v-if="$route.params.id" :span="3" class="action-button-container">
              <el-button v-loading="loading.delete" type="danger" @click="deleteMonster">Delete</el-button>
            </el-col>
          </el-row>
        </el-card>
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
import local from './local.js';

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
        delete: false,
      },
      // language: 'en',
      foodsArr: [],
      naturesArr: [],
      locationsArr: [],
    };
  },
  computed: {
    language: {
      get() {
        const appLang = this.$store.state.app.language;
        if (appLang === 'ja') {
          return 'ja';
        } else if (appLang === 'en') {
          return 'en';
        } else {
          return 'en';
        }
      },
      set(lang) {
        this.$i18n.locale = lang;
        this.$store.dispatch('app/setLanguage', lang);
      },
    },
  },
  async created() {
    await this.getMonsterData(this.$route.params.id);
    await this.getMonstersArrayData();
    if (!this.$i18n.getLocaleMessage('en')['ViewMonster']) {
      this.$i18n.mergeLocaleMessage('en', local.en);
      this.$i18n.mergeLocaleMessage('ja', local.ja);
      this.$i18n.mergeLocaleMessage('ru', local.ru);
      this.$i18n.mergeLocaleMessage('vi', local.vi);
      this.$i18n.mergeLocaleMessage('zh', local.zh);
    }
  },
  methods: {
    async getMonsterData(id) {
      this.loading.monsterData = true;
      this.monsterData = await MonsterResource.get(id);
      this.monsterData.name = JSON.parse(this.monsterData.name);
      this.monsterData.description = JSON.parse(
        this.monsterData.description.replace('\n', '\\n')
      );
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
      let sortedArr = [];
      orderArr.forEach(id => {
        sortedArr.push(array.find(el => el.id === id));
      });
      sortedArr = sortedArr.filter(obj => obj);
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
        this.monsterData.fav_food.replace(/\[|\]/g, '')
      );
      this.naturesArr = this.sortArrayByOrder(
        localnaturesArr,
        this.monsterData.nature.replace(/\[|\]/g, '')
      );
      this.locationsArr = this.sortArrayByOrder(
        locallocationsArr,
        this.monsterData.found_in.replace(/\[|\]/g, '')
      );
      this.locationsArr.forEach(obj => {
        obj.name = JSON.parse(obj.name);
      });
      this.loading.monsterArrayData = false;
    },
    deleteMonster() {
      this.loading.delete = true;
      axios
        .delete(`http://127.0.0.1:8000/api/monsters/${this.monsterData.id}`)
        .then(() => {
          this.$message.success('Monster deleted successfully');
          this.loading.delete = false;
          this.$router.push('/monsters');
        })
        .catch(() => {
          this.$message.error('There was an error while deleting');
          this.loading.delete = false;
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.actions {
  margin: 1rem 0 1rem 1rem;

  .action-button-container {
    display: flex;
  }
}
</style>
