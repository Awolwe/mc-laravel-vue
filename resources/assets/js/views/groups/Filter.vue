<template>
    <!-- Поиск -->
    <div class="clearfix">
        <div ref="inputGroup" class="input-group w-100 mb-3" style="z-index: 3004;">
            <dropdown animation="ani-none" :position="['left', 'top', 'right', 'top']" :visible="dropdown.filters" >
                <button @click="dropdown.filters = !dropdown.filters" type="button" class="btn btn-primary" style="border-top-right-radius: 0;border-bottom-right-radius: 0;" data-toggle="dropdown" aria-haspopup="false" aria-expanded="true">
                    <span class="fa fa-filter mr-1"></span>
                    <span class="d-none d-md-inline-block">фильтр</span>
                </button>
                <div slot="dropdown" class="dialog" style="z-index: 1000; min-width: 10rem;padding: .5rem 0;margin: .5rem 0 0;font-size: 1rem;color: #292b2c;text-align: left;list-style: none;background-color: #fff;-webkit-background-clip: padding-box;background-clip: padding-box;border: 1px solid rgba(0,0,0,.15);border-radius: .25rem;">
                    <div class="p-3" style="width: 400px;max-height: 1000px;overflow-y: scroll;"  >
                        <div class="form-group">
                            <v-select :on-change="selectPupil" :value.sync="temp.pupil" :options="$common.data.pupilsFullName" placeholder="Статус"></v-select>
                        </div>
                        <div class="form_group">
                            <button @click="clearListLoad()" :disabled="load" class="btn btn-primary btn-block" >Применить</button>
                            <button @click="resetFilter()" :disabled="load" class="btn btn-secondary btn-block btn-sm">Сбросить фильтр</button>
                        </div>
                    </div>
                </div>
            </dropdown>

            <input
                    type="text"
                    class="form-control w-100"
                    v-model="filterData.search_text"
                    placeholder="наименование, адрес"
                    style="z-index:0;"
                    @keyup.enter="clearListLoad()"
            >
            <div class="input-group-append dropdown">
                <button class="btn dropbtn"><span class="fa fa-caret-down"></span></button>
                <div class="dropdown-content">
                    <a @click="setChooser(1)">названию группы</a>
                    <a @click="setChooser(2)">имени ученика</a>
                </div>
            </div>
            <div class="input-group-btn" v-if="filterData.search_text" style="z-index:1;">
                <button type="submit" class="btn btn-secondary" @click="resetText()"><span class="fa fa-times"></span></button>
            </div>
            <div class="input-group-btn"  style="z-index:1;">
                <button type="submit" class="btn btn-secondary" @click="clearListLoad()"><span class="fa fa-search mr-1"></span> поиск</button>
            </div>
        </div>
    </div>


</template>

<script>
    export default {

        props: ['load'],

        data() {

            return {

                dropdown: {
                    filters: false
                },
                filterData: {
                    search_text: '',
                    pupil_id: 0,
                    chooser: 0,
                },
                temp: {
                    pupil: 0
                },
                statuses: this.$common.data.pupilsFullName,
            }

        },

        mounted(){
            this.$nextTick(function () {
                this.setFiltered(this.$route.query);
            });
        },

        methods: {
            resetFilter(){
                this.filterData.pupil_id = 0;
                this.temp.pupil = 0;
                this.clearListLoad();
            },

            resetText() {
                this.filterData.search_text = '';
                this.filterData.chooser = 0;
                this.$nextTick(function() {
                    this.clearListLoad();
                });
            },
            clearListLoad(){


                this.$nextTick(function () {
                    this.$emit('filtered');
                });
                this.dropdown.filters = false;

            },
            setFiltered(query){

                for (let filterKey in this.filterData) {
                    for(let queryKey in query){
                        if(filterKey == queryKey) {
                            if(this.filterData[filterKey].constructor === Array) {
                                this.filterData[filterKey].push(query[queryKey]);
                            } else {
                                this.filterData[filterKey] = query[queryKey];

                            }

                        }
                    }
                }
                this.$nextTick(function () {
                    this.setSelect();
                });


            },
            setSelect()
            {
                if(this.filterData.pupil_id) {
                    var teach = this.filterData.pupil_id
                    teach = teach -1;
                    this.temp.pupil = this.statuses[teach];
                    /*console.log(this.statuses[this.filterData.pupil_id]);
                    console.log(1);*/

                }

                this.$nextTick(function(){
                    this.clearListLoad();
                })

            },
            selected(key, options, ids)
            {
                let comp = this;
                ids.forEach(function (id) {
                    options.forEach(function(value) {
                        if(value.id == id) comp.temp[key].push(value);
                    });
                });
            },
            selectPupil(val) {
                if (val)
                    this.filterData.pupil_id = val.value
                this.temp.pupil = val;
            },
            setChooser(val) {
                this.filterData.chooser = val
            }
        }

    }
</script>
<style>
    /* Style The Dropdown Button */
    .dropbtn {
        padding: 10px;
        font-size: 14px;
        border: none;
        cursor: pointer;
    }

    /* The container <div> - needed to position the dropdown content */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Dropdown Content (Hidden by Default) */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    /* Links inside the dropdown */
    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    /* Change color of dropdown links on hover */
    .dropdown-content a:hover {background-color: #f1f1f1}

    /* Show the dropdown menu on hover */
    .dropdown:hover .dropdown-content {
        display: block;
    }

    /* Change the background color of the dropdown button when the dropdown content is shown */
    .dropdown:hover .dropbtn {
        background-color: #3e8e41;
    }
</style>