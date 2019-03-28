<template>

  <div class="tags_form mt30">

      <div class="form-group">
        <header :class="{'active': showCountry}" class="form-header" @click="showCountry = !showCountry">
          <h4 class="mb_1">Страны и регионы</h4>
          <label class="check-all">
            <input type="checkbox">
            <span @click="allCountries">Выбрать все</span>
          </label>
          <!--label class="form-search">
            <span class="form-search__text">Искать в разделе</span>
            <input  @input="onChange"
                    v-model="search_country"
                    type="text">
            <span class="form-search__btn"></span>
          </label-->
        </header>
        <div v-show="showCountry" class="form-check">
          <div id="form-check-countries" class="form-check-label grid-col-check-5"
               v-bind="bindTagGrid('#form-check-countries',this.countries.length,4)">
            <label class="d-flex flex-row align-items-start check_box"
                   v-for="country in countries">
              <input  @change="checkboxfilter()"
                      name="countries[]"
                      type="checkbox"
                      :value="country.id"
                      v-model="selcountries">
              <span>{{ country.title}}</span>
            </label>
          </div>
        </div>
      </div>

      <div class="form-group">
        <header :class="{'active': showVVT}" class="form-header" @click="showVVT = !showVVT">
          <h4 class="mb_1">Тип ВВТ</h4>
          <label class="check-all">
            <input type="checkbox">
            <span @click="allVVT">Выбрать все</span>
          </label>
          <!--label class="form-search">
            <span class="form-search__text">Искать в разделе</span>
            <input  @input="onChange"
                    v-model="search_vvt"
                    type="text">
            <span class="form-search__btn"></span>
          </label-->
        </header>
        <div v-show="showVVT" class="form-check">
          <div id="form-check-vvt_types" class="form-check-label grid-col-check-6"
              v-bind="bindTagGrid('#form-check-vvt_types',this.vvt_types.length,4)">
            <label class="d-flex flex-row align-items-start check_box" v-for="vvt_type in vvt_types">
              <input  @change="checkboxfilter()" 
                      name="vvt_types[]" 
                      type="checkbox" 
                      :value="vvt_type.id"
                      v-model="selvvt_types">
              <span>{{ vvt_type.title}}</span>
            </label>
          </div>
        </div>
      </div>

      <div class="form-group">
        <header :class="{'active': showCompany}" class="form-header" @click="showCompany = !showCompany">
          <h4 class="mb_1">Компании и организации</h4>
          <label class="check-all">
            <input type="checkbox">
            <span @click="allCompanies">Выбрать все</span>
          </label>
          <!--label class="form-search">
            <span class="form-search__text">Искать в разделе</span>
            <input  @input="onChange"
                    v-model="search_company"
                    type="text">
            <span class="form-search__btn"></span>
          </label-->
        </header>        
        <div v-show="showCompany" class="form-check">
          <div id="form-check-companies" class="form-check-label grid-col-check-2"
              v-bind="bindTagGrid('#form-check-companies',this.companies.length,4)">
            <label class="d-flex flex-row align-items-start check_box" v-for="company in companies">
              <input  @change="checkboxfilter()"
                      name="companies[]" 
                      type="checkbox" 
                      :value="company.id" 
                      v-model="selcompanies">
              <span>{{ company.title}}</span>
            </label>
          </div>
        </div>
      </div>

      <div class="form-group">
        <header :class="{'active': showPersonalities}" class="form-header" @click="showPersonalities = !showPersonalities">
          <h4 class="mb_1">Персоналии</h4>
          <label class="check-all">
            <input type="checkbox">
            <span @click="allPersonalities">Выбрать все</span>
          </label>
          <!--label class="form-search">
            <span class="form-search__text">Искать в разделе</span>
            <input  @input="onChange"
                    v-model="search_person"
                    type="text">
            <span class="form-search__btn"></span>
          </label-->
        </header>
        <div v-show="showPersonalities" class="form-check">
          <div id="form-check-personalities" class="form-check-label grid-col-check-6"
              v-bind="bindTagGrid('#form-check-personalities',this.personalities.length,4)">
            <label class="d-flex flex-row align-items-start check_box" v-for="personality in personalities">
              <input  @change="checkboxfilter()"
                      name="personalities[]" 
                      type="checkbox" 
                      :value="personality.id" 
                      v-model="selpersonalities">
              <span>{{ personality.title}}</span>
            </label>
          </div>
        </div>
      </div>
    </div>

</template>

<script>
export default {
  props: ["selectedtags",
          ],
  data() {
    return {
      search_company: '',
      search_country: '',
      search_person: '',
      search_vvt: '',
      countries: [],
      selcountries: [],
      vvt_types: [],
      selvvt_types: [],
      companies: [],
      selcompanies: [],
      personalities: [],
      selpersonalities: [],
      showCountry: false,
      showCompany: false,
      showVVT: false,
      showPersonalities: false,
    };
  },
  mounted() {
    if (this.selectedtags != undefined) {
      this.selcountries = this.selectedtags.countries;
      this.selvvt_types = this.selectedtags.vvt_types;
      this.selcompanies = this.selectedtags.companies;
      this.selpersonalities = this.selectedtags.personalities;
    }

    this.checkboxfilter();
  },
  methods: {
    checkboxfilter() {
      axios
        .post("/tags", {
          countries: this.selcountries,
          vvt_type: this.selvvt_types
        })
        .then(response => {
          //console.log(response.data)
          this.countries = response.data.countries;
          this.companies = response.data.companies;
          this.vvt_types = response.data.vvt_types;
          this.personalities = response.data.personalities;
        });
    },
    gettags() {
      axios.get("/tags").then(response => {
        this.countries = response.data.countries;
        this.companies = response.data.companies;
        this.vvt_types = response.data.vvt_types;
        this.personalities = response.data.personalities;
      });
    },
    bindTagGrid(selector, count, n) {
      var row = Number.parseInt(count / n) + 1;
      jQuery(selector).css("grid-template-rows", "repeat(" + row + ", 1fr)");
    },
    allCountries() {
      if (this.selcountries.length) {
        this.selcountries = []
      } else {
        this.selcountries = this.countries.map(item => item.id);
      }
      
      this.checkboxfilter();
    },
    allVVT() {
      if (this.selvvt_types.length) {
        this.selvvt_types = []
      } else {
        this.selvvt_types = this.vvt_types.map(item => item.id);
      }
      this.checkboxfilter();
    },
    allCompanies() {
      if (this.selcompanies.length) {
        this.selcompanies = []
      } else {
        this.selcompanies = this.companies.map(item => item.id);
      }
      this.checkboxfilter();
    },
    allPersonalities() {
      if (this.selpersonalities.length) {
        this.selpersonalities = []
      } else {
        this.selpersonalities = this.personalities.map(item => item.id);
      }
      this.checkboxfilter();
    },
      onChange() {
          axios
              .post("/search_tag", {

                      search_country: this.search_country,
                      search_vvt: this.search_vvt,
                      search_person: this.search_person,
                      search_company: this.search_company,

              })
              .then(response => {
                  //console.log(response.data)
                  this.countries = response.data.countries;
                  this.companies = response.data.companies;
                  this.vvt_types = response.data.vvt_types;
                  this.personalities = response.data.personalities;
              });
      },
  }
};
</script>

<style>
  .form-header {
    padding-right: 50px;
  }

  .form-search {
    display: flex;
    align-items: center;
    position: relative;
    margin: 0;
    margin-left: auto;
  }

  .form-search__text {
    display: block;
    width: 100%;
  }

  .form-search input {
    height: 30px !important;
    color: #939393;
    font-size: 13px;
    padding: 0 45px 0 15px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
    -ms-flex-direction: row;
    flex-direction: row;
    -webkit-box-pack: start;
    -ms-flex-pack: start;
    justify-content: flex-start;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    max-width: 325px;
    width: 100%;
  }

  .form-search__btn {
    width: 30px;
    height: 100%;
    background: #0d004c;
    color: #fff;
    position: absolute;
    top: 0;
    right: 0;
  }

  .form-search__btn::before {
    content: "";
    background: url(/images/search.png) no-repeat center center;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }

</style>