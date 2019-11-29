<template>
  <el-card class="stats-card">
    <div slot="header" class="clearfix">
      <div class="header">
        <span>
          <strong>{{ $t('ViewMonster.stats') }}</strong>
        </span>
      </div>
    </div>
    <el-row>
      <el-col :span="12" class="stats-container">
        <el-row>
          <strong>{{ $t('ViewMonster.health') }}</strong>
        </el-row>
        <el-row>
          <count-to
            ref="countTo"
            :start-val="0"
            :end-val="monsterData.health"
            :duration="2000"
            :autoplay="true"
            class="stats-number"
            style="color: mediumseagreen"
          />
        </el-row>
      </el-col>
      <el-col :span="12" class="stats-container">
        <el-row>
          <strong>{{ $t('ViewMonster.energy') }}</strong>
        </el-row>
        <el-row>
          <count-to
            ref="countTo"
            :start-val="0"
            :end-val="monsterData.energy"
            :duration="2000"
            :autoplay="true"
            style="color: cornflowerblue"
            class="stats-number"
          />
        </el-row>
      </el-col>
    </el-row>
  </el-card>
</template>

<script>
import countTo from 'vue-count-to';

export default {
  name: 'StatsCard',
  components: { countTo },
  props: {
    monsterData: {
      type: Object,
      default: function() {
        return {};
      },
    },
    language: {
      type: String,
      default: 'en',
    },
  },
  data() {
    return {
      // language: 'en',
    };
  },
};
</script>

<style lang="scss" scoped>
.header {
  display: flex;
  justify-content: center;
  align-items: center;

  strong {
    font-size: 1.5rem;
  }
}

.stats-card {
  .stats-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .stats-container:not(:last-child) {
    margin-bottom: 0.5rem;
  }

  .stats-number {
    font-size: 4rem;
    font-weight: bold;
  }
}
</style>
