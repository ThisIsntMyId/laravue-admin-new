<template>
  <div class="app-container">
    <el-row>
      <el-col :span="24">
        <h1>{{ $route.params.id ? 'Edit Monster Page' : 'Add Monster Page' }}</h1>
      </el-col>
    </el-row>
    <el-form ref="form" v-loading="loading.monsterData" :model="formData" :rules="validationRules">
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
                </div>
              </div>
              <el-row>
                <el-col :span="18">
                  <div>
                    <el-form-item
                      v-show="language.name == 'en'"
                      class="details-field"
                      prop="name.en"
                    >
                      <el-row>
                        <strong>Name</strong>
                        <el-radio-group v-model="language.name" style="float: right;" size="small">
                          <el-radio-button label="en">En</el-radio-button>
                          <el-radio-button label="ja">Ja</el-radio-button>
                        </el-radio-group>
                      </el-row>
                      <el-input v-model="formData.name.en" />
                    </el-form-item>
                    <el-form-item
                      v-show="language.name == 'ja'"
                      class="details-field"
                      prop="name.ja"
                    >
                      <el-row>
                        <strong>Japanese Name</strong>
                        <el-radio-group v-model="language.name" style="float: right;" size="small">
                          <el-radio-button label="en">En</el-radio-button>
                          <el-radio-button label="ja">Ja</el-radio-button>
                        </el-radio-group>
                      </el-row>
                      <el-input v-model="formData.name.ja" />
                    </el-form-item>
                    <el-form-item
                      v-show="language.description == 'en'"
                      class="details-field"
                      prop="description.en"
                    >
                      <el-row>
                        <strong>Description</strong>
                        <el-radio-group
                          v-model="language.description"
                          style="float: right;"
                          size="small"
                        >
                          <el-radio-button label="en">En</el-radio-button>
                          <el-radio-button label="ja">Ja</el-radio-button>
                        </el-radio-group>
                      </el-row>
                      <el-input v-model="formData.description.en" type="textarea" />
                    </el-form-item>
                    <el-form-item
                      v-show="language.description == 'ja'"
                      class="details-field"
                      prop="description.ja"
                    >
                      <el-row>
                        <strong>Japanese Description</strong>
                        <el-radio-group
                          v-model="language.description"
                          style="float: right;"
                          size="small"
                        >
                          <el-radio-button label="en">En</el-radio-button>
                          <el-radio-button label="ja">Ja</el-radio-button>
                        </el-radio-group>
                      </el-row>
                      <el-input v-model="formData.description.ja" type="textarea" />
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
                  <el-form-item class="stats-container" prop="health">
                    <el-row class="center">
                      <strong>Health</strong>
                    </el-row>
                    <el-row>
                      <el-input v-model.number="formData.health" />
                    </el-row>
                  </el-form-item>
                </el-col>
                <el-col :span="12" class="stats-container">
                  <el-form-item class="stats-container" prop="energy">
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
              <el-form-item prop="fav_food">
                <el-row class="info">
                  <div class="title">
                    <strong>Favourite Food</strong>
                  </div>
                  <div>
                    <el-select
                      v-model="formData.fav_food"
                      style="width:100%;"
                      multiple
                      filterable
                      remote
                      reserve-keyword
                      placeholder="Please enter a keyword"
                      :remote-method="remoteMethod"
                      :loading="loading.foodSelect"
                    >
                      <el-option
                        v-for="item in foodsArr"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id"
                      />
                    </el-select>
                  </div>
                </el-row>
              </el-form-item>
              <el-form-item prop="nature">
                <el-row class="info">
                  <div class="title">
                    <strong>Nature</strong>
                  </div>
                  <div>
                    <el-drag-select
                      v-model="formData.nature"
                      style="width:100%;"
                      multiple
                      placeholder="Select Nature"
                    >
                      <el-option
                        v-for="item in naturesArr"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id"
                      />
                    </el-drag-select>
                  </div>
                </el-row>
              </el-form-item>
              <el-form-item prop="found_in">
                <el-row class="info">
                  <div class="title">
                    <strong>Found In</strong>
                    <el-radio-group v-model="language.location" style="float: right;" size="mini">
                      <el-radio-button label="en">English</el-radio-button>
                      <el-radio-button label="ja">Japanese</el-radio-button>
                    </el-radio-group>
                  </div>
                  <div>
                    <el-drag-select
                      v-model="formData.found_in"
                      style="width:100%;"
                      multiple
                      filterable
                      remote
                      reserve-keyword
                      placeholder="Please enter a keyword"
                      :remote-method="remoteMethodLocation"
                      :loading="loading.locationSelect"
                    >
                      <el-option
                        v-for="item in locationsArr"
                        :key="item.id"
                        :label="language.location == 'en' ? item.name.en : item.name.ja"
                        :value="item.id"
                      />
                    </el-drag-select>
                  </div>
                </el-row>
              </el-form-item>
            </el-col>
          </el-card>
          <el-card class="actions">
            <el-row>
              <el-col :span="3" class="action-button-container">
                <el-button
                  v-loading="loading.formSubmit"
                  type="primary"
                  @click="$route.params.id ? updateMonster() : createMonster()"
                >{{ $route.params.id ? 'Save' : 'Create' }}</el-button>
              </el-col>
              <el-col :span="3" class="action-button-container">
                <el-button @click="resetForm">Reset</el-button>
              </el-col>
              <el-col v-if="$route.params.id" :span="3" class="action-button-container">
                <el-button v-loading="loading.delete" type="danger" @click="deleteMonster">Delete</el-button>
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
import ElDragSelect from '@/components/DragSelect';
import axios from 'axios';

export default {
  name: 'AddOrUpdateMonster',
  components: { PanThumb, ElDragSelect },
  data() {
    var healthValidator = (rule, value, callback) => {
      if (!value) {
        return callback(new Error('Please Enter Health'));
      }
      setTimeout(() => {
        if (!Number.isInteger(value)) {
          callback(new Error('Health must be in numbers only'));
        } else {
          if (value < 0 || value > 1000) {
            callback(new Error('Health must me between 0,1000'));
          } else {
            callback();
          }
        }
      }, 1000);
    };
    var energyValidator = (rule, value, callback) => {
      if (!value) {
        return callback(new Error('Please Enter Energy'));
      }
      if (!Number.isInteger(value)) {
        callback(new Error('Energy must be in numbers only'));
      } else {
        if (value < 0 || value > 1000) {
          callback(new Error('Energy must me between 0,1000'));
        } else {
          callback();
        }
      }
    };

    return {
      language: {
        name: 'en',
        description: 'en',
        location: 'en',
      },
      formData: {
        name: {
          en: '',
          ja: '',
        },
        description: {
          en: '',
          ja: '',
        },
        health: '',
        energy: '',
        fav_food: [],
        nature: [],
        found_in: [],
      },
      foodsArr: [],
      naturesArr: [],
      locationsArr: [],
      loading: {
        locationSelect: false,
        foodSelect: false,
        formSubmit: false,
        monsterData: false,
        delete: false,
      },
      validationRules: {
        'name.en': [
          {
            required: true,
            message: 'Please enter name of monster',
            trigger: 'blur',
          },
        ],
        'name.ja': [
          {
            required: true,
            message: 'Please enter japanese name of monster',
            trigger: 'blur',
          },
        ],
        health: [{ validator: healthValidator, trigger: 'blur' }],
        energy: [{ validator: energyValidator, trigger: 'blur' }],
      },
    };
  },
  async created() {
    this.naturesArr = (await axios.get(
      'http://127.0.0.1:8000/api/natures?limit=-1'
    )).data;
    if (this.$route.params.id) {
      await this.initializeMonsterData(this.$route.params.id);
    }
  },
  methods: {
    createMonster() {
      this.loading.formSubmit = true;
      this.$refs['form'].validate(valid => {
        if (valid) {
          const dataToBeSent = this.prepareFormData(this.formData);
          console.log(dataToBeSent);
          axios
            .post('http://127.0.0.1:8000/api/monsters', dataToBeSent)
            .then(response => {
              this.$message.success('Monster added successfully.');

              this.$router.push(`/monsters/view/${response.data.id}`);
              this.loading.formSubmit = false;
            })
            .catch(() => {
              this.$message.error(
                'There were some erors while adding monster. Plz try again later.'
              );
              this.loading.formSubmit = false;
            });
        } else {
          if (this.formData.name.en && this.formData.name.ja === '') {
            this.language.name = 'ja';
          }
          this.$message.error(
            'There are some erors in your form. Please Fix them.'
          );
          this.loading.formSubmit = false;
        }
      });
    },
    updateMonster() {
      this.loading.formSubmit = true;
      this.$refs['form'].validate(valid => {
        if (valid) {
          const dataToBeSent = this.prepareFormData(this.formData);
          axios
            .patch(
              `http://127.0.0.1:8000/api/monsters/${this.$route.params.id}`,
              dataToBeSent
            )
            .then(() => {
              this.$message.success('Monster updated successfully.');
              this.resetForm();
              this.loading.formSubmit = false;
              this.$router.push(`/monsters/view/${this.$route.params.id}`);
            })
            .catch(() => {
              this.$message.error(
                'There were some erors while updating monster. Plz try again later.'
              );
              this.loading.formSubmit = false;
            });
        } else {
          if (this.formData.name.en && this.formData.name.ja === '') {
            this.language.name = 'ja';
          }
          this.$message.error(
            'There are some erors in your form. Please Fix them.'
          );
          this.loading.formSubmit = false;
        }
      });
    },
    resetForm() {
      if (this.$route.params.id) {
        this.initializeMonsterData(this.$route.params.id);
      } else {
        this.$refs['form'].resetFields();
      }
    },
    prepareFormData(data) {
      const newData = Object.assign({}, data);
      newData.name = JSON.stringify(newData.name);
      newData.description = JSON.stringify(newData.description);
      newData.fav_food = JSON.stringify(newData.fav_food);
      newData.nature = JSON.stringify(newData.nature);
      newData.found_in = JSON.stringify(newData.found_in);
      console.log(newData);
      return newData;
    },
    async initializeMonsterData(id) {
      this.loading.monsterData = true;
      const monsterData = (await axios.get(
        `http://127.0.0.1:8000/api/monsters/${id}`
      )).data;
      const foods = (await axios.get(
        `http://127.0.0.1:8000/api/foods?ids=${monsterData.fav_food}`
      )).data;
      const locations = (await axios.get(
        `http://127.0.0.1:8000/api/locations?ids=${monsterData.found_in}`
      )).data;
      monsterData.fav_food = JSON.parse(monsterData.fav_food);
      monsterData.nature = JSON.parse(monsterData.nature);
      monsterData.found_in = JSON.parse(monsterData.found_in);
      this.formData = monsterData;
      // this.formData.fav_food =
      this.formData.fav_food = foods.map(food => food.id);
      this.formData.found_in = locations.map(location => location.id);
      this.foodsArr = foods;
      this.locationsArr = locations;
      console.log(this.formData);
      console.log(monsterData);
      this.formData.name = JSON.parse(this.formData.name);
      this.formData.description = JSON.parse(
        this.formData.description.replace('\n', '\\n')
      );
      this.locationsArr.forEach(obj => {
        obj.name = JSON.parse(obj.name);
      });
      this.loading.monsterData = false;
    },
    async remoteMethod(query) {
      if (query !== '') {
        this.foodsArr = (await axios.post(
          `http://127.0.0.1:8000/api/foods/search?q=${query}`
        )).data;
        const currentSelectedValued = (await axios.get(
          `http://127.0.0.1:8000/api/foods?ids=${this.formData.fav_food}`
        )).data;
        // When we search for new words, then the old array is gone which contains the labels for previously selected food values.
        // now if those values are lost then their ids are being used as label. so to fix that the foodsArr is filled with
        // first: the searched query as well as the values of the currently selected values
        this.foodsArr = [
          ...new Set([...this.foodsArr, ...currentSelectedValued]),
        ];
      } else {
        this.foodsArr = [];
      }
    },
    async remoteMethodLocation(query) {
      if (query !== '') {
        this.locationsArr = (await axios.post(
          `http://127.0.0.1:8000/api/locations/search?q=${query}`
        )).data;
        this.locationsArr.forEach(obj => {
          obj.name = JSON.parse(obj.name);
        });
        console.log(this.locationsArr);
      } else {
        this.locationsArr = [];
      }
    },
    deleteMonster() {
      this.loading.delete = true;
      axios
        .delete(`http://127.0.0.1:8000/api/monsters/${this.$route.params.id}`)
        .then(() => {
          this.$message.success('Monster deleted successfully');
          this.loading.delete = false;
          this.$router.go(-1);
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
