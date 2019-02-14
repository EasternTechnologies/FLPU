<template>

  <div class="container tags_form mt30">
    <div class="row mb30">
      <div class="form-group">
        <h4 class="mb_1">Страны и регионы 
          <span @click="allCountries" class="button button_small_small sel_all">Выбрать все</span>
        </h4>
        <div class="form-check">
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
    </div>
    <div class="row mb30">
      <div class="form-group">
        <h4 class="mb_1">Тип ВВТ 
          <span @click="allVVT" class="button button_small_small sel_all">Выбрать все</span>
        </h4>
        <div id="form-check-vvt_types" class="form-check-label grid-col-check-6"
             v-bind="bindTagGrid('#form-check-vvt_types',this.vvt_types.length,4)">
          <label class="d-flex flex-row align-items-start check_box" v-for="vvt_type in vvt_types">
            <input  @change="checkboxfilter()" 
                    name="vvt_types[]" 
                    type="checkbox" 
                    :value="vvt_type.id"
                    v-model="selvvt_types">
            <span>{{ vvt_type.title}}</span></label>
        </div>
      </div>
    </div>
    <div class="row mb30">
      <div class="form-group">
        <h4 class="mb_1">Компании и организации
          <span @click="allCompanies" class="button button_small_small sel_all">Выбрать все</span>
        </h4>
        <div id="form-check-companies" class="form-check-label grid-col-check-2"
             v-bind="bindTagGrid('#form-check-companies',this.companies.length,2)">
          <label class="d-flex flex-row align-items-start check_box" v-for="company in companies">
            <input  @change="checkboxfilter()"
                    name="companies[]" 
                    type="checkbox" 
                    :value="company.id" 
                    v-model="selcompanies">
            <span>{{ company.title}}</span></label>
        </div>
      </div>
    </div>
    <div class="row mb30">
      <div class="form-group">
        <h4 class="mb_1">Персоналии 
          <span @click="allPersonalities" class="button button_small_small sel_all">Выбрать все</span>
        </h4>
        <div id="form-check-personalities" class="form-check-label grid-col-check-6"
             v-bind="bindTagGrid('#form-check-personalities',this.personalities.length,3)">
          <label class="d-flex flex-row align-items-start check_box" v-for="personality in personalities">
            <input  @change="checkboxfilter()"
                    name="personalities[]" 
                    type="checkbox" 
                    :value="personality.id" 
                    v-model="selpersonalities">
            <span>{{ personality.title}}</span></label>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
export default {
  props: ["selectedtags"],
  data() {
    return {
      countries: [],
      selcountries: [],
      vvt_types: [],
      selvvt_types: [],
      companies: [],
      selcompanies: [],
      personalities: [],
      selpersonalities: []
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
          console.log(response.data)
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
    allCountries: function() {
      if (this.selcountries.length) {
        this.selcountries = []
      } else {
        this.selcountries = this.countries.map(item => item.id);
      }
      
      this.checkboxfilter();
    },
    allVVT: function() {
      if (this.selvvt_types.length) {
        this.selvvt_types = []
      } else {
        this.selvvt_types = this.vvt_types.map(item => item.id);
      }
      this.checkboxfilter();
    },
    allCompanies: function() {
      if (this.selcompanies.length) {
        this.selcompanies = []
      } else {
        this.selcompanies = this.companies.map(item => item.id);
      }
      // this.checkboxfilter();
    },
    allPersonalities: function() {
      if (this.selpersonalities.length) {
        this.selpersonalities = []
      } else {
        this.selpersonalities = this.personalities.map(item => item.id);
      }
      // this.checkboxfilter();
    },
  }
};
</script>

<style scoped>
</style>