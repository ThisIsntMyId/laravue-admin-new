<template>
  <el-card>
    <div slot="header" class="clearfix">
      <div class="header">
        <span>
          <strong>Other Details</strong>
        </span>
      </div>
    </div>
    <el-col>
      <el-row class="info">
        <div class="title">
          <strong>Favourite Food</strong>
        </div>
        <div>
          <el-tag
            v-for="food in favFoods"
            :key="food.id"
            :type="getRandomTagType(food.id)"
          >{{ food.id }} | {{ food.name }}</el-tag>
        </div>
      </el-row>
      <el-row class="info">
        <div class="title">
          <strong>Nature</strong>
        </div>
        <div>
          <el-tag
            v-for="nature in natures"
            :key="nature.id"
            :type="getRandomTagType(nature.id)"
          >{{ nature.id }} | {{ nature.name }}</el-tag>
        </div>
      </el-row>
      <el-row class="info">
        <div class="title">
          <strong>Found In</strong>
          <el-radio-group v-model="language" style="float: right;" size="mini">
            <el-radio-button label="en">English</el-radio-button>
            <el-radio-button label="ja">Japanese</el-radio-button>
          </el-radio-group>
        </div>
        <div>
          <el-tag
            v-for="location in locations"
            :key="location.id"
            :type="getRandomTagType(location.id)"
          >{{ location.id }} | {{ language=='en'? location.name.en : location.name.ja }}</el-tag>
        </div>
      </el-row>
    </el-col>
  </el-card>
</template>

<script>
export default {
  name: 'OtherDetails',
  props: {
    favFoods: {
      type: Array,
      default: function() {
        return [];
      },
    },
    natures: {
      type: Array,
      default: function() {
        return [];
      },
    },
    locations: {
      type: Array,
      default: function() {
        return [];
      },
    },
  },
  data() {
    return {
      language: 'en',
    };
  },
  created() {
    console.log(this.favFoods);
    console.log(this.natures);
    console.log(this.locations);
  },
  methods: {
    getRandomTagType(number) {
      switch (number % 3) {
        case 0:
          return '';
        case 1:
          return 'success';
        case 2:
          return 'danger';
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;

  strong {
    font-size: 1.5rem;
  }
}

.el-tag {
  margin-right: 1rem;
}

.info {
  margin: 1.5rem;

  .title {
    font-size: 1.2rem;
    padding: 0.3rem 0;
    margin-bottom: 0.5rem;
    border-bottom: 1px solid;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
}
.info:first-child {
  margin-top: 0;
}
</style>
