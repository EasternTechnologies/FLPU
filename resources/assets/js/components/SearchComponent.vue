<template>
    <div>
        <div class="right-box">
            <div>
                <label class="search-box"> <a href="/search/form">Расширенный поиск</a>
                    <input class="search" type="text" v-on:keyup.13="search_result" v-model="q" @keyup="search" placeholder="Введите слово"/>
                    <span @click="search_result" class="butt_search"></span></label>
            </div>
        </div>
        <!--div v-if="result" class="search_result">
            <p class="row_search_title_close">Результаты поиска<span class="close_res_search" @click="close">x</span></p>
            <button v-if="result!=false" class="all_res_link" @click="search_result">Все результаты</button>
            <ul>
                <div v-for="(items, index) in result">
                    <li v-for="item in items" class="out_list_title">
                        <a target="_blank" :href="'/'+index+'/article/'+item.id">{{item.title}}</a>
                    </li>
                </div>
            </ul>
        </div-->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                q: '',
                result: false
            }
        },
        methods: {
            search() {
                if (this.q.length >= 2) {
                document.cookie = "pdfitems=; path=/;"
                    axios.post('/simply_search', {q: this.q}).then(response => {
                        this.result = response.data;
                        //console.log(response.data)
                    })
                }

            },
            close() {
            	this.result = false;
            },
            search_result() {
                if (this.q.length >= 1) {

                    window.location.href = "/simply_search?q=" + this.q;
                }
            }


        },
    }
</script>

<style scoped>
.search_result {
  position: relative;
  z-index: 100;
  padding-bottom: 30px;
  background-color: white;
  border: 1px solid #0d004c;
  box-shadow: 0 0 21px 0 rgba(0, 0, 1, 0.4);
}

.all_res_link {
  position: absolute;
  bottom: 10px;
  left: 50%;
  z-index: 200;
  margin-bottom: 0;
  font-size: 14px;
  transform: translateX(-50%);
}

.search_result ul {
  max-height: 250px;
  overflow: auto;
}

</style>