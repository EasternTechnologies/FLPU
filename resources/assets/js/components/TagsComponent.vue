<template>
    <div>
        <div class="container tags_form">
            <div class="row">
                <div class="form-group">
                    <h4 class="mb_1">Страны и регионы <a class="butt_add butt_tag_click butt_add_tag1 button_small" href="#">
                        <button>Добавить тег</button>
                    </a></h4>
                    <div id="form-check-countries" class="form-check grid-col-check-5" v-bind="bindTagGrid('#form-check-countries',this.countries.length,5)">
                        <div class="form-check-label" v-for="country in countries">
                            <label class="d-flex flex-row align-items-start check_box"><input @change="checkboxfilter()" name="countries[]" type="checkbox" :value="country.id" v-model="selcountries"><span>{{ country.title}}</span></label>
                            <div class="del_tag" @click="del_tag(country.id,'country',country.title)">x</div>
                            <div class="edit_tag" @click="edit_tag(country.id,'country',country.title)"><i class="fas fa-pencil-alt"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <h4 class="mb_1">Тип ВВТ <a class="butt_add butt_tag_click butt_add_tag2 button_small" href="#">
                        <button>Добавить тег</button>
                    </a></h4>
                    <div id="form-check-vvt_types" class="form-check grid-col-check-6" v-bind="bindTagGrid('#form-check-vvt_types',this.vvt_types.length,6)">
                        <div class="form-check-label" v-for="vvt_type in vvt_types">
                            <label class="d-flex flex-row align-items-start check_box"><input @change="checkboxfilter()" name="vvt_types[]" type="checkbox" :value="vvt_type.id" v-model="selvvt_types"><span>{{ vvt_type.title}}</span></label>
                            <div class="del_tag" @click="del_tag(vvt_type.id,'vvttypes',vvt_type.title)">x</div>
                            <div class="edit_tag" @click="edit_tag(vvt_type.id,'vvttypes',vvt_type.title)"><i class="fas fa-pencil-alt"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <h4 class="mb_1">Компании и организации <a class="butt_add butt_tag_click butt_add_tag3 button_small" href="#">
                        <button>Добавить тег</button>
                    </a></h4>
                    <div id="form-check-companies" class="form-check grid-col-check-2" v-bind="bindTagGrid('#form-check-companies',this.companies.length,2)">
                        <div class="form-check-label" v-for="company in companies">
                            <label class="d-flex flex-row align-items-start check_box"><input name="companies[]" type="checkbox" :value="company.id" v-model="selcompanies"><span>{{ company.title}}</span></label>
                            <div class="del_tag" @click="del_tag(company.id,'company',company.title)">x</div>
                            <div class="edit_tag" @click="edit_tag(company.id,'company',company.title)"><i class="fas fa-pencil-alt"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <h4 class="mb_1">Персоналии <a class="butt_add butt_tag_click butt_add_tag4 button_small" href="#">
                        <button>Добавить тег</button>
                    </a></h4>
                    <div id="form-check-personalities" class="form-check grid-col-check-6" v-bind="bindTagGrid('#form-check-personalities',this.personalities.length,6)">
                        <div class="form-check-label" v-for="personality in personalities">
                            <label class="d-flex flex-row align-items-start check_box"><input name="personalities[]" type="checkbox" :value="personality.id" v-model="selpersonalities"><span>{{ personality.title}}</span></label>
                            <div class="del_tag" @click="del_tag(personality.id,'personalities',personality.title)">x</div>
                            <div class="edit_tag" @click="edit_tag(personality.id,'personalities',personality.title)"><i class="fas fa-pencil-alt"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- country tag  -->
        <div class="popup_tag popup_tag_country" style="display: none;">
            <div class="bg_popup_tag"></div>

            <div action="/country" method="POST" class="popup_tag_form">
                <div class="close_tag">x</div>

                <h4 class="mb30">Добавить <span>поисковую метку</span></h4>
                <div class="popup_tag_form_box mb30">
                    <input name="tag" placeholder="Введите название страны" v-model="addcountry"/>
                </div>
                <a class="butt_save butt_add_tag" href="#">
                    <button @click="storecountry()">Сохранить тег</button>
                </a>
            </div>

        </div>

        <!-- vvttype tag -->
        <div class="popup_tag popup_tag_vvttype" style="display: none;">
            <div class="bg_popup_tag"></div>

            <div action="/vvttype" method="POST" class="popup_tag_form">
                <div class="close_tag">x</div>

                <h4 class="mb30">Добавить <span>поисковую метку</span></h4>
                <div class="popup_tag_form_box">
                    <input name="tag" class="mb30" placeholder="Введите название типа ВВТ" v-model="addvvt"/>
                </div>
                <a class="butt_save butt_add_tag" href="#">
                    <button @click="storevvt()">Сохранить тег</button>
                </a>
            </div>

        </div>

        <!--add tag -->
        <div class="popup_tag popup_tag_company" style="display: none;">
            <div class="bg_popup_tag"></div>

            <div class="popup_tag_form">
                <div class="close_tag">x</div>

                <h4 class="mb30">Добавить <span>поисковую метку</span></h4>
                <div class="popup_tag_form_box">

                    <input name="tag" placeholder="Введите название компании" v-model="addcompany"/>

                    <div class="select_wrap">
                        <select name="company_select_country" v-model="attachcountrytocompany" @change="pushcountrytocompany()" class="company_select_country">
                            <option value="" disabled selected>--Страна--</option>
                            <option v-for="country in countries" :value="country.id">{{country.title}}</option>
                        </select>
                    </div>
                    
                    <div class="select_wrap">
                        <select name="company_select_vvt" v-model="attachvvttocompany" @change="pushvvttocompany()" class="company_select_vvt">
                            <option value="" disabled selected>--Тип ВВТ--</option>
                            <option v-for="vvt_type in vvt_types" :value="vvt_type.id">{{vvt_type.title}}</option>
                        </select>
                    </div>

                    <div class="mb10 d-flex flex-column justify-content-center">
                        <span class="out_country_select pl20"></span>
                        <input class="hide_company_select_contry" type="hidden" name="country_for_tag[]">
                        <br>
                        <span class="out_vvt_select pl20"></span>
                        <input class="hide_company_select_vvt" type="hidden" name="country_for_tag[]">
                        <br>
                    </div>
                </div>
                <a class="butt_save butt_add_tag" href="#">
                    <button @click="storecompany()">Сохранить тег</button>
                </a>
            </div>

        </div>

        <!-- personalities -->
        <!--<div class="popup_tag popup_tag_personalities" style="display: none;">-->
            <!--<div class="bg_popup_tag"></div>-->
            <!--<div class="popup_tag_form">-->
                <!--<div class="close_tag">x</div>-->
                <!--<h4 class="mb30">Добавить <span>поисковую метку</span></h4>-->
                <!--<div class="popup_tag_form_box">-->
                    <!--<input name="tag" placeholder="Введите название персоналии" v-model="addperson"/>-->
                    <!--<div class="select_wrap">-->
                        <!--<select name="personalities_select_country" @change="pushcountrytopersona()"v-model="attachcountrytopersona" class="personalities_select_country">-->
                            <!--<option value="" disabled>&#45;&#45;Страна&#45;&#45;</option>-->
                            <!--<option v-for="country in countries" :value="country.id">{{country.title}}</option>-->
                        <!--</select>-->
                    <!--</div>-->
                    <!--<div class="mb10 d-flex flex-column justify-content-center">-->
                        <!--<span class="out_personalities_country_select pl20"></span>-->
                        <!--<input class="hide_personalities_select_country" type="hidden" name="country_for_tag[]">-->
                        <!--<br>-->
                    <!--</div>-->
                <!--</div>-->
                <!--<a class="butt_save butt_add_tag" href="#">-->
                    <!--<button @click="storeperonality()">Сохранить тег</button>-->
                <!--</a>-->
            <!--</div>-->
        <!--</div>-->

        <!-- deltag modal -->
        <div class="popup popup_deltag" style="display: none;">
            <div class="bg_popup"></div>

            <div class="popup_form" style="display: none;">
                <h4 class="mb10">Вы действительно хотите удалить?</h4>
                <p class="mb30 deltag_text_out"></p>
                <div class="box_save_article">
                    <input class="name_tag" type="hidden" name="name_tag" data-name="">
                    <input class="value_tag" type="hidden" name="value_tag" data-value="">
                    <span class="button butt_delltag" @click="dellTagContinue();">Удалить</span><span class="button butt_close" @click="dellTagClose();">Отмена</span>
                </div>
            </div>

        </div>

        <!-- edittag modal -->
        <div class="popup popup_edittag" style="display: none;">
            <div class="bg_popup"></div>

            <div class="popup_form" style="display: none;">
                <h4 class="mb10">Исправьте тег</h4>
                <input class="title_tag" name="title_tag" value="">

                                    <div v-if="name_tag == 'company' || name_tag == 'personalities'" class="select_wrap">
                                        <select name="company_select_country" v-model="tocountries" @change="pushtocountryupdate()" class="company_select_country_edit">
                                            <option value="" disabled selected>--Страна--</option>
                                            <option v-for="country in countries" :value="country.id">{{country.title}}</option>
                                        </select>
                                    </div>

                                    <div v-if="name_tag == 'company'" class="select_wrap">
                                        <select name="company_select_vvt" v-model="tovvt" @change="pushtovvtupdate()" class="company_select_vvt_edit">
                                            <option value="" disabled selected>--Тип ВВТ--</option>
                                            <option v-for="vvt_type in vvt_types" :value="vvt_type.id">{{vvt_type.title}}</option>
                                        </select>
                                    </div>

                                    <div class="mb10 d-flex flex-column justify-content-center">
                                        <span class="out_country_select_edit pl20"></span>
                                        <span v-if="name_tag == 'company'" class="out_vvt_select_edit pl20"></span>
                                    </div>

                <div class="box_save_article">
                    <input class="name_tag" type="hidden" name="name_tag" data-name="">
                    <input class="value_tag" type="hidden" name="value_tag" data-value="">
                    <span class="button butt_delltag" @click="editTagContinue();">Исправить</span><span class="button butt_close" @click="editTagClose();">Отмена</span>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: ['selectedtags'],
        data() {
            return {
                countries: [],
                selcountries: [],
                vvt_types: [],
                selvvt_types: [],
                companies: [],
                selcompanies: [],
                personalities: [],
                selpersonalities: [],
                addcountry: '',
                addcompany: '',
                attachcountrytocompany: [],
                countryarraytocompany: [],
                addvvt: '',
                addperson: '',


                attachcountrytopersona: [],
                countryarraytopersona: [],
                vvtarraytopersona: [],
                attachvvttopersona: [],
                vvtarraytocompany: [],
                attachvvttocompany: [],

                tocountries:[],
                tovvt:[],

                country_id_array:[],
                vvt_id_array:[],

                name_tag:''
            }
        },
        mounted() {
            if (this.selectedtags != undefined) {console.log(this.selectedtags);
                this.selcountries = this.selectedtags.countries;
                this.selvvt_types = this.selectedtags.vvt_types;
                this.selcompanies = this.selectedtags.companies;
                this.selpersonalities = this.selectedtags.personalities;
            }

            this.checkboxfilter();
        },
        methods: {
            checkboxfilter() {
                axios.post('/tags', {countries: this.selcountries, vvt_type: this.selvvt_types}).then(response => {
                    this.countries = response.data.countries;
                    this.companies = response.data.companies;
                    this.vvt_types = response.data.vvt_types;
                    this.personalities = response.data.personalities;
                    this.country_id_array = response.data.country_id_array;
                    this.vvt_id_array = response.data.vvt_id_array;
                })
            },

            storecountry(e) {
                //e.preventDefault();
                jQuery('.popup_tag_country .popup_tag_form_box .mess_er_tag').text('');

                var is_tag = 0;
                var title = this.addcountry;
                this.countries.forEach(function (country) {
                    if (country.title == title) {
                        is_tag++;
                    }
                });

                if (is_tag == 0) {
                    axios.post('/country', {title: this.addcountry}).then(response => {

                        this.checkboxfilter();;
                    });
                    this.addcountry = '';
                    jQuery('.close_tag').click();
                } else {
                    if (jQuery('.popup_tag_country .popup_tag_form_box .mess_er_tag').length) {
                        jQuery('.popup_tag_country .popup_tag_form_box .mess_er_tag').text('Тег уже существует');
                    } else {
                        jQuery('.popup_tag_country .popup_tag_form_box').append('<p class="mess_er_tag mb30">Тег уже существует</p>');
                    }
                }
            },
            storevvt(e) {

                jQuery('.popup_tag_vvttype .popup_tag_form_box .mess_er_tag').text('');

                var is_tag = 0;
                var title = this.addvvt;
                this.vvt_types.forEach(function (vvt) {
                    if (vvt.title == title) {
                        is_tag++;
                    }
                });

                if (is_tag == 0) {
                    axios.post('/vvttypes', {title: this.addvvt}).then(response => {
                        console.log(response);
                        this.checkboxfilter();
                    });
                    this.addvvt = '';
                    jQuery('.close_tag').click();
                } else {
                    if (jQuery('.popup_tag_vvttype .popup_tag_form_box .mess_er_tag').length) {
                        jQuery('.popup_tag_vvttype .popup_tag_form_box .mess_er_tag').text('Тег уже существует');
                    } else {
                        jQuery('.popup_tag_vvttype .popup_tag_form_box').append('<p class="mess_er_tag mb30">Тег уже существует</p>');
                    }

                }

            },

            storecompany() {
                jQuery('.popup_tag_company .popup_tag_form_box .mess_er_tag').text('');

                var is_tag = 0;
                var title = this.addcompany;
                this.companies.forEach(function (company) {
                    if (company.title == title) {
                        is_tag++;
                    }
                });

                if(this.selectedtags != undefined) {

                    var $data = {
                        title: this.addcompany,
                        countries: this.countryarraytocompany,
                        vvt_tag: this.vvtarraytocompany,
                        article : this.selectedtags.article,
                        report : this.selectedtags.report,
                    } ;

                } else {

                    var $data = {
                        title: this.addcompany,
                        countries: this.countryarraytocompany,
                        vvt_tag: this.vvtarraytocompany,
                    } ;
                }

                if (is_tag == 0) {
                    axios.post('/company', $data).then(response => {
                        this.selcompanies = response.data;
                    this.checkboxfilter();

                });

                    this.addcompany = '';
                    this.attachcountrytocompany = [];
                    this.countryarraytocompany = [];
                    this.attachvvttopersona = [];
                    this.vvtarraytopersona = [];
                    this.attachvvttocompany = [];
                    this.vvtarraytocompany = [];
                    this.article = '';
                    this.report = '';

                    jQuery('.close_tag').click();
                } else {
                    if (jQuery('.popup_tag_company .popup_tag_form_box .mess_er_tag').length) {
                        jQuery('.popup_tag_company .popup_tag_form_box .mess_er_tag').text('Тег уже существует');
                    } else {
                        jQuery('.popup_tag_company .popup_tag_form_box').append('<p class="mess_er_tag mb30">Тег уже существует</p>');
                    }
                }
            },
            storeperonality(e) {
                //e.preventDefault();
                jQuery('.popup_tag_personalities .popup_tag_form_box .mess_er_tag').text('');

                var is_tag = 0;
                var title = this.addperson;
                this.personalities.forEach(function (personality) {
                    if (personality.title == title) {
                        is_tag++;
                    }
                });

                if(this.selectedtags != undefined) {

                    var $data = {
                        title: this.addperson,
                        countries: this.countryarraytopersona,
                        vvt_tag: this.vvtarraytopersona,
                        article : this.selectedtags.article,
                        report : this.selectedtags.report,
                    } ;

                } else {

                    var $data = {
                        title: this.addperson,
                        countries: this.countryarraytopersona,
                        vvt_tag: this.vvtarraytopersona,
                    } ;
                }

                if (is_tag == 0) {
                    axios.post('/personalities', $data).then(response => {
                        this.selpersonalities = response.data;
                        this.checkboxfilter();
                    });
                    this.addperson = '';
                    this.attachcountrytopersona = [];
                    this.attachvvttopersona = [];
                    this.countryarraytopersona = [];
                    this.vvtarraytopersona = [];
                    this.article = '';
                    this.report = '';

                    jQuery('.close_tag').click();
                } else {
                    if (jQuery('.popup_tag_personalities .popup_tag_form_box .mess_er_tag').length) {
                        jQuery('.popup_tag_personalities .popup_tag_form_box .mess_er_tag').text('Тег уже существует');
                    } else {
                        jQuery('.popup_tag_personalities .popup_tag_form_box').append('<p class="mess_er_tag mb30">Тег уже существует</p>');
                    }
                }
            },

            gettags() {
                axios.get('/tags').then(response => {
                    this.countries = response.data.countries;
                    this.companies = response.data.companies;
                    this.vvt_types = response.data.vvt_types;
                    this.personalities = response.data.personalities;

                })
            },

            pushcountrytopersona() {

                var index = -1;
                if (this.countryarraytopersona.length) {
                    index = this.countryarraytopersona.indexOf(this.attachcountrytopersona);
                }
                if (index >= 0) {
                    this.countryarraytopersona.splice(index, 1);
                } else {
                    this.countryarraytopersona.push(this.attachcountrytopersona);
                }

                console.log("arrary person: " + this.countryarraytopersona);
            },
            pushvvttopersona() {

                var index = -1;
                if (this.vvtarraytopersona.length) {
                    index = this.vvtarraytopersona.indexOf(this.attachvvttopersona);
                }
                if (index >= 0) {
                    this.vvtarraytopersona.splice(index, 1);
                } else {
                    this.vvtarraytopersona.push(this.attachvvttopersona);
                }

                console.log("arrary vvt: " + this.vvtarraytopersona);
            },
            pushvvttocompany() {

                var index = -1;
                if (this.vvtarraytocompany.length) {
                    index = this.vvtarraytocompany.indexOf(this.attachvvttocompany);
                }
                if (index >= 0) {
                    this.vvtarraytocompany.splice(index, 1);
                } else {
                    this.vvtarraytocompany.push(this.attachvvttocompany);
                }

                console.log("arrary vvt: " + this.vvtarraytocompany);
            },
            pushcountrytocompany() {

                var index = -1;
                if (this.countryarraytocompany.length) {
                    index = this.countryarraytocompany.indexOf(this.attachcountrytocompany);
                }
                if (index >= 0) {
                    this.countryarraytocompany.splice(index, 1);
                } else {
                    this.countryarraytocompany.push(this.attachcountrytocompany);
                }
                console.log("arrary: " + this.countryarraytocompany);

            },


            pushtocountryupdate() {

                var index = -1;
                if (this.countryarraytocompany.length) {
                    index = this.countryarraytocompany.indexOf(this.tocountries);
                }
                if (index >= 0) {
                    this.countryarraytocompany.splice(index, 1);
                    jQuery('.company_select_country_edit option[value='+this.tocountries+']').removeClass('active');
                } else {
                    this.countryarraytocompany.push(this.tocountries);
                    jQuery('.company_select_country_edit option[value='+this.tocountries+']').addClass('active');
                }

                var company_name = [];

                for(var key in this.countryarraytocompany) {
                    var country_id = this.countryarraytocompany[key];
                    company_name.push(" "+this.country_id_array[country_id].title);
                }

                jQuery(".out_country_select_edit").text(company_name);
                console.log("arrary country: " + this.countryarraytocompany);

            },
            pushtovvtupdate() {

                var index = -1;
                if (this.vvtarraytocompany.length) {
                    index = this.vvtarraytocompany.indexOf(this.tovvt);
                }
                if (index >= 0) {
                    this.vvtarraytocompany.splice(index, 1);
                    jQuery('.company_select_vvt_edit option[value='+this.tovvt+']').removeClass('active');
                } else {
                    this.vvtarraytocompany.push(this.tovvt);
                    jQuery('.company_select_vvt_edit option[value='+this.tovvt+']').addClass('active');
                }

                var vvt_name = [];

                for(var key in this.vvtarraytocompany) {
                    var vvt_id = this.vvtarraytocompany[key];
                    vvt_name.push(" "+this.vvt_id_array[vvt_id].title);
                }
                jQuery(".out_vvt_select_edit").text(vvt_name);
                console.log("arrary vvt: " + this.vvtarraytocompany);

            },

            del_tag(value, name_tag, text) {

                jQuery('.name_tag').attr('data-name', name_tag);
                jQuery('.value_tag').attr('data-value', value);

                jQuery('.popup_deltag').fadeIn(250);
                jQuery('.popup_deltag .popup_form').show(500);
                jQuery('.deltag_text_out').text(text);

            },

            edit_tag(value, name_tag, title) {

                $('.title_tag').val(title);

                this.countryarraytocompany = [];
                this.vvtarraytocompany = [];

//                console.log(name_tag);

                jQuery('.company_select_country_edit option').removeClass('active');
                jQuery('.company_select_vvt_edit option').removeClass('active');

                axios.post('/tags', {tag: value,name_tag:name_tag}).then(response => {

                    var company_name = [];
                    var vvt_name = [];

                    for(var key in response.data.countries) {
                        console.log(response.data.countries[key]);
                        jQuery('.company_select_country_edit option[value='+response.data.countries[key]+']').addClass('active');

                            company_name.push(" " + key);

                            var index = -1;
                            if (this.countryarraytocompany.length) {
                                index = this.countryarraytocompany.indexOf(response.data.countries[key]);
                            }
                            if (index >= 0) {
                                this.countryarraytocompany.splice(index, 1);
                            } else {
                                this.countryarraytocompany.push(response.data.countries[key]);
                            }
                    }
                    jQuery(".out_country_select_edit").text(company_name);

                    if(name_tag=='company') {
                        for(var key in response.data.vvt) {

                            jQuery('.company_select_vvt_edit option[value='+response.data.vvt[key]+']').addClass('active');

                            vvt_name.push(" " + key);

                            var index = -1;
                            if (this.vvtarraytocompany.length) {
                                index = this.vvtarraytocompany.indexOf(response.data.vvt[key]);
                            }
                            if (index >= 0) {
                                this.vvtarraytocompany.splice(index, 1);
                            } else {
                                this.vvtarraytocompany.push(response.data.vvt[key]);

                            }
                        }
                        jQuery(".out_vvt_select_edit").text(vvt_name);
                    }

                })

                this.value = value;
                this.name_tag = name_tag;
                this.title = title;

                jQuery('.popup_edittag').fadeIn(250);
                jQuery('.popup_edittag .popup_form').show(500);
                jQuery('.title_tag').attr('value', title);
               // jQuery('.edittag_text_out').text(title);

            },

            dellTagContinue() {
                var name_tag = jQuery('.name_tag').attr('data-name');
                var value = jQuery('.value_tag').attr('data-value');

                axios.delete('/' + name_tag + '/' + value).then(response => {
                    this.checkboxfilter();
                    jQuery('.popup_alert').fadeIn(250);
                    jQuery('.popup_alert .popup_form').show(500);
                    jQuery('.alert_text_out').html('<p>Поисковая метка удалена!</p>');
                })

                jQuery('.deltag_text_out').text('');
                jQuery('.popup_deltag .popup_form').hide(500);
                jQuery('.popup_deltag').fadeOut(250);

                jQuery('.name_tag').attr('data-name', '');
                jQuery('.value_tag').attr('data-value', '');
            },

            editTagContinue() {

                var data = {
                    title: $('.title_tag').val(),
                    countries: this.countryarraytocompany,
                    vvt_tag: this.vvtarraytocompany,
                };

                console.log(data);

                axios.put('/' + this.name_tag + '/' + this.value, {data} ).then(response => {
                    this.checkboxfilter();
                    jQuery('.popup_alert').fadeIn(250);
                    jQuery('.popup_alert .popup_form').show(500);
                    jQuery('.alert_text_out').html('<p>Поисковая метка отредактирована!</p>');
                })

                jQuery('.popup_edittag .popup_form').hide(500);
                jQuery('.popup_edittag').fadeOut(250);

            },

            dellTagClose() {

                jQuery('.deltag_text_out').text('');
                jQuery('.popup_deltag .popup_form').hide(500);
                jQuery('.popup_deltag').fadeOut(250);

                jQuery('.name_tag').attr('data-name', '');
                jQuery('.value_tag').attr('data-value', '');
            },
            editTagClose() {

                //jQuery('.edittag_text_out').text('');
                jQuery('.popup_edittag .popup_form').hide(500);
                jQuery('.popup_edittag').fadeOut(250);

                jQuery('.name_tag').attr('data-name', '');
                jQuery('.value_tag').attr('data-value', '');
            },
            bindTagGrid(selector, count, n) {
                var row = Number.parseInt(count / n) + 1;
                jQuery(selector).css('grid-template-rows', 'repeat(' + row + ', 1fr)');
            }
        }
    }
</script>

<style scoped>


</style>