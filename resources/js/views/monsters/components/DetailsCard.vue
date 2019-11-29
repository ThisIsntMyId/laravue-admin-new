<template>
  <el-card class="box-card">
    <div slot="header" class="clearfix">
      <div class="header">
        <span>
          <strong>{{ $t('ViewMonster.details') }}</strong>
        </span>
        <!-- <el-radio-group v-model="language" style="float: right;" size="mini">
          <el-radio-button label="en">English</el-radio-button>
          <el-radio-button label="ja">Japanese</el-radio-button>
        </el-radio-group>-->
      </div>
    </div>
    <el-row>
      <el-col :span="18">
        <div class="detail">
          <el-row>
            <strong>{{ $t('ViewMonster.name') }}</strong>
          </el-row>
          <el-row>{{ monsterData.name[language] }}</el-row>
        </div>
        <div class="detail">
          <el-row>
            <strong>{{ $t('ViewMonster.description') }}</strong>
          </el-row>
          <el-row>{{ monsterData.description[language] }}</el-row>
        </div>
      </el-col>
      <el-col :span="6">
        <div class="user-avatar box-center">
          <pan-thumb image="./profile.jpeg" :height="'100px'" :width="'100px'" :hoverable="false" />
        </div>
      </el-col>
    </el-row>
  </el-card>
</template>

<script>
import PanThumb from '@/components/PanThumb';

export default {
  name: 'DetailsCard',
  components: { PanThumb },
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
.detail {
  margin-bottom: 1rem;
  padding: 0px 1.5rem 0 0;
  letter-spacing: 0.5px;
  word-spacing: 5px;

  strong {
    font-size: 1.2rem;
  }

  .el-row:nth-child(odd) {
    margin-bottom: 0.3rem;
  }
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;

  strong {
    font-size: 1.5rem;
  }
}
</style>
