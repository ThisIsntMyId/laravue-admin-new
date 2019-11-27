<template>
  <div class="app-container">
    <el-row>
      <el-col :span="24">
        <h1>Add New Monster</h1>
      </el-col>
    </el-row>
    <el-form refs="form" :model="formData">
      <el-row>
        <el-col :span="8">
          <el-row>
            <!-- Details Field -->
            <el-card class="details">
              <div slot="header" class="clearfix">
                <div class="header">
                  <span>
                    <strong>Details</strong>
                  </span>
                  <el-radio-group v-model="language.details" style="float: right;" size="small">
                    <el-radio-button label="en">En</el-radio-button>
                    <el-radio-button label="ja">Ja</el-radio-button>
                  </el-radio-group>
                </div>
              </div>
              <el-row>
                <el-col :span="18">
                  <div v-if="language.details == 'en'">
                    <el-form-item class="details-field">
                      <el-row>
                        <strong>Name</strong>
                      </el-row>
                      <el-input v-model="formData.name" />
                    </el-form-item>
                    <el-form-item class="details-field">
                      <el-row>
                        <strong>Description</strong>
                      </el-row>
                      <el-input v-model="formData.description" type="textarea" />
                    </el-form-item>
                  </div>
                  <div v-if="language.details == 'ja'">
                    <el-form-item class="details-field">
                      <el-row>
                        <strong>Japanese Name</strong>
                      </el-row>
                      <el-input v-model="formData.japanese_name" />
                    </el-form-item>
                    <el-form-item class="details-field">
                      <el-row>
                        <strong>Japanese Description</strong>
                      </el-row>
                      <el-input v-model="formData.japanese_description" type="textarea" />
                    </el-form-item>
                  </div>
                </el-col>
                <el-col :span="6">
                  <div class="user-avatar box-center">
                    <pan-thumb
                      image="./profile.jpeg"
                      :height="'100px'"
                      :width="'100px'"
                      :hoverable="false"
                    />
                  </div>
                </el-col>
              </el-row>
            </el-card>
          </el-row>
          <!-- Stats Fields -->
          <el-row style="margin-top: 1rem">
            <el-card class="stats-card">
              <div slot="header" class="clearfix">
                <div class="stats-header">
                  <span>
                    <strong>Stats</strong>
                  </span>
                </div>
              </div>
              <el-row>
                <el-col :span="12">
                  <el-form-item class="stats-container">
                    <el-row class="center">
                      <strong>Health</strong>
                    </el-row>
                    <el-row>
                      <el-input v-model.number="formData.health" />
                    </el-row>
                  </el-form-item>
                </el-col>
                <el-col :span="12" class="stats-container">
                  <el-form-item class="stats-container">
                    <el-row class="center">
                      <strong>Energy</strong>
                    </el-row>
                    <el-row>
                      <el-input v-model.number="formData.energy" />
                    </el-row>
                  </el-form-item>
                </el-col>
              </el-row>
            </el-card>
          </el-row>
        </el-col>
        <!-- Select Fields for food, nature and locations -->
        <el-col :span="16">
          <el-card class="other-details">
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
                  <el-tag>food</el-tag>
                </div>
              </el-row>
              <el-row class="info">
                <div class="title">
                  <strong>Nature</strong>
                </div>
                <div>
                  <el-tag>nature</el-tag>
                </div>
              </el-row>
              <el-row class="info">
                <div class="title">
                  <strong>Found In</strong>
                  <el-radio-group v-model="language.location" style="float: right;" size="mini">
                    <el-radio-button label="en">English</el-radio-button>
                    <el-radio-button label="ja">Japanese</el-radio-button>
                  </el-radio-group>
                </div>
                <div>
                  <el-tag>language</el-tag>
                </div>
              </el-row>
            </el-col>
          </el-card>
          <el-card class="actions">
            <el-row>
              <el-col :span="3" class="action-button-container">
                <el-button type="primary">Save</el-button>
              </el-col>
              <el-col :span="3" class="action-button-container">
                <el-button>Reset</el-button>
              </el-col>
              <el-col :span="3" class="action-button-container">
                <el-button type="danger">Delete</el-button>
              </el-col>
            </el-row>
          </el-card>
        </el-col>
      </el-row>
    </el-form>
  </div>
</template>

<script>
import PanThumb from '@/components/PanThumb';
export default {
  name: 'AddOrUpdateMonster',
  components: { PanThumb },
  data() {
    return {
      language: {
        details: 'en',
        location: 'en',
      },
      formData: {
        name: '',
        japanese_name: '',
        description: '',
        japanese_description: '',
        health: '',
        energy: '',
      },
    };
  },
  methods: {
    submitForm() {
      this.$refs['form'].validate(valid => {
        if (valid) {
          alert('submit!');
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    resetForm() {
      this.$refs['form'].resetFields();
    },
  },
};
</script>

<style lang="scss" scoped>
.center {
  display: flex;
  justify-content: center;
  align-items: center;
}

strong {
  font-size: 1.2rem;
}

// Utility Classes
.stats-header {
  display: flex;
  justify-content: center;
  align-items: center;

  strong {
    font-size: 1.5rem;
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

.details-field {
  margin-bottom: 1rem;
  padding: 0px 1.5rem 0 0;
  letter-spacing: 0.5px;
  word-spacing: 5px;

  .el-row:nth-child(odd) {
    margin-bottom: 0.3rem;
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

.other-details {
  margin-left: 1rem;

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
}

.actions {
  margin: 1rem 0 1rem 1rem;

  .action-button-container {
    display: flex;
  }
}
</style>
