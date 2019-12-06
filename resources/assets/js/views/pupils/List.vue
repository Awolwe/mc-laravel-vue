<template>
    <div>
        <!-- Результаты -->
        <div class="col-12 col-md-10 offset-md-2">
            <pupil-filter v-if="$common.data.roles" ref="filter" :load="load" v-on:filtered="filtered"></pupil-filter>
            Найдено <b>{{ total }}</b> Ученика
            <button type="button" class="btn btn-primary btn-sm ml-2" @click="$refs.newPupil.showModal()">добавить</button>
            <button type="button" class="btn btn-secondary btn-sm ml-2" @click="export_xlsx()">Экспорт</button>
            <table class="table mt-4">
                <thead class="thead-default">
                <tr>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="pupil in pupils" :key="pupil.id">
                    <td>{{ pupil.firstName }}</td>
                    <td>{{ pupil.secondName }}</td>
                    <td>
                        <div class="pull-right">
                            <b-tooltip title="Открыть профиль">
                                <router-link :to="{name:'pupil', params:{id: pupil.id}}" class="btn btn-outline-primary btn-sm"><span class="fa fa-user"></span></router-link>
                            </b-tooltip>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <pupil-form v-if="$common.data && $common.data.roles" ref="newPupil" :data="$common.data" :_form="newPupil" v-on:formSending="filtered"></pupil-form>

    </div>
</template>


<script>

    import { get } from '../../helpers/api'

    export default {

        data() {
            return {
                load: false,
                scrollLoad: false,
                newPupil: '',
                pupils: [],
                total: 0,
                resource_url: '/api/pupils',
                next_url: '',
                default_url: '/api/pupils'
            }
        },
        components: {
            'pupil-form': require('./Form.vue'),
            'pupil-filter': require('./Filter.vue')
        },
        methods: {
            getList() {


                this.resource_url = this.scrollLoad ? this.next_url : this.resource_url;

                if (!this.resource_url){
                    this.scrollLoad = false;
                    return false;
                }

                this.load = true;

                let _this = this;


                get(_this, this.resource_url, {params: this.filterData}, function (response) {

                    let json = response.data;



                    _this.next_url = json.next_page_url;

                    _this.pupils = _this.pupils.concat(json.data);

                    _this.total = json.total;
                    _this.scrollLoad = false;
                    _this.load = false;

                }, function () {

                    _this.scrollLoad = false;
                    _this.load = false;

                });

            },
            filtered() {
                this.resource_url = this.default_url;
                this.pupils = [];
                this.total = 0;
                this.filterData = this.$refs.filter.filterData;

                console.log(this.filterData);
                this.$nextTick(function () {
                    this.$router.push({ path: '/control/pupils', query: this.filterData });
                    this.getList();
                });

            },
            export_xlsx() {
                //.$router.push({ path: '/control/pupils/export'});
                window.location.replace("/control/pupils/export")
            },
            handleScroll(){
                /* eslint-disable-next-line no-undef */
                if($(window).scrollTop() + $(window).height() > $(document).height() - 100 && !this.scrollLoad) {
                    this.scrollLoad = true;
                    this.$nextTick(function () {
                        this.getList();
                    })
                }

            }

        },

        created() {

            window.document.body.onscroll = this.handleScroll;
        }

    }
</script>