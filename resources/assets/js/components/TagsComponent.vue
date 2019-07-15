<template>
    <div>
        <div class="container tags_form">

            <div class="row">
                <div class="form-group">
                    <h4 class="mb_1">Страны и регионы
                        <a class="butt_add" @click="addTag('country')" href="#">
                            <button class="butt_tag_click button_small">Добавить тег</button>
                        </a>
                    </h4>
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
                    <h4 class="mb_1">Тип ВВТ
                        <a class="butt_add" @click="addTag('vvttypes')" href="#">
                        <button class="butt_tag_click button_small">Добавить тег</button>
                    </a>
                    </h4>
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
                        <button @click="addTag('company')" >Добавить тег</button>
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
                    <h4 class="mb_1">Персоналии <a class="butt_add butt_tag_click butt_add_tag3 button_small" href="#">
                        <button @click="addTag('personalities')">Добавить тег</button>
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

        <!-- simple tag popup  -->
        <div class="popup_tag popup_tag_simple" style="display: none;">
            <div class="bg_popup_tag"></div>

                <div class="popup_tag_form">

                    <div class="close_tag">x</div>
                    <h4 class="mb30">Добавить <span>поисковую метку</span></h4>

                    <div class="popup_tag_form_box mb30">

                        <input v-if="name_tag=='country'" name="tag" placeholder="Введите название страны" v-model="addsimpletag"/>
                        <input v-if="name_tag=='vvttypes'" name="tag" placeholder="Введите название типа ВВТ" v-model="addsimpletag"/>
                    </div>
                    <a class="butt_save butt_add_tag" href="#">
                        <button @click="storesimpletag(name_tag)">Сохранить тег</button>
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

                    <template v-if="name_tag=='company'">
                        <input name="tag" placeholder="Введите название компании" v-model="addvalue"/>
                    </template>
                    <template v-else>
                        <input name="tag" placeholder="Введите название персоналии" v-model="addvalue"/>
                    </template>


                    <div class="select_wrap">
                        <select name="select_country" v-model="attachcountry" @change="pushcountry()" class="select_country">
                            <option value="" disabled selected>--Страна--</option>
                            <option v-for="country in countries" :value="country.id">{{country.title}}</option>
                        </select>
                    </div>

                    <div v-if="name_tag=='company'" class="select_wrap">
                        <select name="select_vvt" v-model="attachvvt" @change="pushvvt()" class="select_vvt">
                            <option value="" disabled selected>--Тип ВВТ--</option>
                            <option v-for="vvt_type in vvt_types" :value="vvt_type.id">{{vvt_type.title}}</option>
                        </select>
                    </div>

                    <div class="mb10 d-flex flex-column justify-content-center">
                        <span class="out_country_select pl20"></span>
                        <br>
                        <span v-if="name_tag=='company'" class="out_vvt_select pl20"></span>
                        <br>
                    </div>
                </div>
                <a class="butt_save butt_add_tag" href="#">
                    <template v-if="name_tag=='company'">
                        <button @click="storetag('company')">Сохранить тег</button>
                    </template>
                    <template v-else>
                        <button @click="storetag('personalities')">Сохранить тег</button>
                    </template>

                </a>
            </div>
        </div>

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
                <h4 class="mb30">Исправьте тег</h4>


                <div class="popup_tag_form_box">
                    <input class="title_tag" name="title_tag" value="">

                    <div v-if="name_tag == 'company' || name_tag == 'personalities'" class="select_wrap d-none">
                        <select name="company_select_country" v-model="tocountries" @change="pushtocountryupdate()" class="company_select_country_edit">
                            <option value="" disabled selected>--Страна--</option>
                            <option v-for="country in countries" :value="country.id">{{country.title}}</option>
                        </select>
                    </div>

                    <div v-if="name_tag == 'company'" class="select_wrap d-none">
                        <select name="company_select_vvt" v-model="tovvt" @change="pushtovvtupdate()" class="company_select_vvt_edit">
                            <option value="" disabled selected>--Тип ВВТ--</option>
                            <option v-for="vvt_type in vvt_types" :value="vvt_type.id">{{vvt_type.title}}</option>
                        </select>
                    </div>

                    <div class="mb10  flex-column justify-content-center d-none">
                        <!--d-flex-->
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
                addsimpletag: '',

                addvalue:'',


                countryarray: [],
                vvtarray: [],
                attachvvt: [],
                attachcountry: [],

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


            storesimpletag(tag) {
                //e.preventDefault();
                jQuery('.popup_tag_country .popup_tag_form_box .mess_er_tag').text('');

                var is_tag = 0;
                var title = this.addsimpletag;

                if(tag=='country')
                this.countries.forEach(function (country) {
                    if (country.title == title) {
                        is_tag++;
                    }
                });
                else
                    this.vvt_types.forEach(function (vvt) {
                    if (vvt.title == title) {
                        is_tag++;
                    }
                });



                if (is_tag == 0) {

                        axios.post('/'+tag, {title: this.addsimpletag}).then(response => {
                            this.checkboxfilter();
                    });


                    this.addsimpletag = '';
                    jQuery('.close_tag').click();
                } else {
                    if (jQuery('.popup_tag_country .popup_tag_form_box .mess_er_tag').length) {
                        jQuery('.popup_tag_country .popup_tag_form_box .mess_er_tag').text('Тег уже существует');
                    } else {
                        jQuery('.popup_tag_country .popup_tag_form_box').append('<p class="mess_er_tag mb30">Тег уже существует</p>');
                    }
                }
            },

//            storecountry(e) {
//                //e.preventDefault();
//                jQuery('.popup_tag_country .popup_tag_form_box .mess_er_tag').text('');
//
//                var is_tag = 0;
//                var title = this.addsimpletag;
//                this.countries.forEach(function (country) {
//                    if (country.title == title) {
//                        is_tag++;
//                    }
//                });
//
//                if (is_tag == 0) {
//                    axios.post('/country', {title: this.addcountry}).then(response => {
//
//                        this.checkboxfilter();
//                });
//                    this.addsimpletag = '';
//                    jQuery('.close_tag').click();
//                } else {
//                    if (jQuery('.popup_tag_country .popup_tag_form_box .mess_er_tag').length) {
//                        jQuery('.popup_tag_country .popup_tag_form_box .mess_er_tag').text('Тег уже существует');
//                    } else {
//                        jQuery('.popup_tag_country .popup_tag_form_box').append('<p class="mess_er_tag mb30">Тег уже существует</p>');
//                    }
//                }
//            },
//
//            storevvt(e) {
//
//                jQuery('.popup_tag_vvttype .popup_tag_form_box .mess_er_tag').text('');
//
//                var is_tag = 0;
//                var title = this.addsimpletag;
//                this.vvt_types.forEach(function (vvt) {
//                    if (vvt.title == title) {
//                        is_tag++;
//                    }
//                });
//
//                if (is_tag == 0) {
//                    axios.post('/vvttypes', {title: this.addvvt}).then(response => {
//                        console.log(response);
//                    this.checkboxfilter();
//                });
//                    this.addsimpletag = '';
//                    jQuery('.close_tag').click();
//                } else {
//                    if (jQuery('.popup_tag_vvttype .popup_tag_form_box .mess_er_tag').length) {
//                        jQuery('.popup_tag_vvttype .popup_tag_form_box .mess_er_tag').text('Тег уже существует');
//                    } else {
//                        jQuery('.popup_tag_vvttype .popup_tag_form_box').append('<p class="mess_er_tag mb30">Тег уже существует</p>');
//                    }
//
//                }
//
//            },

            storetag(tag) {
                jQuery('.popup_tag_company .popup_tag_form_box .mess_er_tag').text('');

                var is_tag = 0;
                var title = this.addvalue;

                if(tag=='company') {
                    this.companies.forEach(function (company) {
                        if (company.title == title) {
                            is_tag++;
                        }
                    });
                }

                else {
                    this.personalities.forEach(function (personality) {
                        if (personality.title == title) {
                            is_tag++;
                        }
                    });
                }



                if(this.selectedtags != undefined) {

                    var data = {
                        title: this.addvalue,
                        countries: this.countryarray,
                        vvt_tag: this.vvtarray,
                        article : this.selectedtags.article,
                    } ;

                }
                else {

                    var data = {
                        title: this.addvalue,
                        countries: this.countryarray,
                        vvt_tag: this.vvtarray,
                    } ;
                }

                console.log("Отправка на сервер:");
                console.log(data);

                if (is_tag == 0) {

                    if(tag=='company') {

                        axios.post('/company', data).then(response => {
                            this.selcompanies = response.data;
                        this.checkboxfilter();
                    });

                    }
                    else {
                        axios.post('/personalities', data).then(response => {
                            this.selpersonalities = response.data;
                        this.checkboxfilter();
                    });
                    }

                    this.addvalue = '';
                    this.attachcountry = [];
                    this.countryarray = [];
                    this.attachvvt = [];
                    this.vvtarray = [];
                    this.article = '';
                    this.report = '';

                    setTimeout(function () {
                        jQuery('.select_country option').removeAttr('selected');
                        jQuery('.select_country option:first-child').attr('selected', 'selected');
                        jQuery('.select_vvt option').removeAttr('selected');
                        jQuery('.select_vvt option:first-child').attr('selected', 'selected');
                    }, 100);


                    jQuery('.close_tag').click();
                } else {
                    if (jQuery('.popup_tag_company .popup_tag_form_box .mess_er_tag').length) {
                        jQuery('.popup_tag_company .popup_tag_form_box .mess_er_tag').text('Тег уже существует');
                    } else {
                        jQuery('.popup_tag_company .popup_tag_form_box').append('<p class="mess_er_tag mb30">Тег уже существует</p>');
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

            addTag(name_tag) {

                if(name_tag == 'country') {
                    jQuery('.popup_tag_simple').fadeIn(500);
                }
                else if(name_tag=='vvttypes') {
                    jQuery('.popup_tag_simple').fadeIn(500);
                }
                else {
                    setTimeout(function () {
                        jQuery('.select_country option').removeAttr('selected');
                        jQuery('.select_country option:first-child').attr('selected', 'selected');
                        jQuery('.select_vvt option').removeAttr('selected');
                        jQuery('.select_vvt option:first-child').attr('selected', 'selected');
                    }, 100);

                    this.vvtarray = [];
                    this.countryarray = [];
                    jQuery('.select_country option').removeClass('active');
                    jQuery('.select_vvt option').removeClass('active');
                }

                this.name_tag = name_tag;
            },

            pushvvt() {

                var index = -1;
                if (this.vvtarray.length) {
                    index = this.vvtarray.indexOf(this.attachvvt);
                }
                if (index >= 0) {
                    this.vvtarray.splice(index, 1);
                    jQuery('.select_vvt option[value='+this.attachvvt+']').removeClass('active');
                } else {
                    this.vvtarray.push(this.attachvvt);
                    jQuery('.select_vvt option[value='+this.attachvvt+']').addClass('active');
                }
                var vvt_name = [];

                for(var key in this.vvtarray) {
                    var vvt_id = this.vvtarray[key];
                    vvt_name.push(" "+this.vvt_id_array[vvt_id].title);
                }
                jQuery(".out_vvt_select").text(vvt_name);

                console.log("arrary vvt: " + this.vvtarray);
            },
            pushcountry() {

                var index = -1;
                if (this.countryarray.length) {
                    index = this.countryarray.indexOf(this.attachcountry);
                }
                if (index >= 0) {
                    this.countryarray.splice(index, 1);
                    jQuery('.select_country option[value='+this.attachcountry+']').removeClass('active');
                } else {
                    this.countryarray.push(this.attachcountry);
                    jQuery('.select_country option[value='+this.attachcountry+']').addClass('active');
                }

                var company_name = [];

                for(var key in this.countryarray) {
                    var country_id = this.countryarray[key];
                    company_name.push(" "+this.country_id_array[country_id].title);
                }

                jQuery(".out_country_select").text(company_name);

                console.log("arrary country: " + this.countryarray);

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

                setTimeout(function () {
                    jQuery('.company_select_country option').removeAttr('selected');
                    jQuery('.company_select_country option:first-child').attr('selected', 'selected');
                    jQuery('.company_select_vvt option').removeAttr('selected');
                    jQuery('.company_select_vvt option:first-child').attr('selected', 'selected');
                }, 100);

                this.countryarraytocompany = [];
                this.vvtarraytocompany = [];

//                console.log(name_tag);

                jQuery('.company_select_country_edit option').removeClass('active');
                jQuery('.company_select_vvt_edit option').removeClass('active');

                axios.post('/tags', {tag: value,name_tag:name_tag}).then(response => {


                    console.log("Edit response:");
                console.log(response);

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

                console.log("Отправка на сервер:");
                console.log(data);

                axios.put('/' + this.name_tag + '/' + this.value, data ).then(response => {
                    this.checkboxfilter();
                jQuery('.popup_alert').fadeIn(250);
                jQuery('.popup_alert .popup_form').show(500);
                jQuery('.alert_text_out').html('<p>Поисковая метка отредактирована!</p>');
            })

                setTimeout(function () {
                    jQuery('.company_select_country option').removeAttr('selected');
                    jQuery('.company_select_country option:first-child').attr('selected', 'selected');
                    jQuery('.company_select_vvt option').removeAttr('selected');
                    jQuery('.company_select_vvt option:first-child').attr('selected', 'selected');
                }, 100);

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