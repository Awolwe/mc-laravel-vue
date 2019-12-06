<template>
    <div>
        <!-- Результаты -->
        <div class="col-12 col-md-10 offset-md-2">
            <group-filter v-if="$common.data.roles" ref="filter" :load="load" v-on:filtered="filtered"></group-filter>
            Найдено <b>{{ total }}</b> Ученика
            <button type="button" class="btn btn-primary btn-sm ml-2" @click="$refs.newGroup.showModal()">добавить</button>

            <table class="table mt-4">
                <thead class="thead-default">
                <tr>
                    <th>Название</th>
                    <th>Имя</th>
                    <th>Тип</th>
                    <th>Начало курса</th>
                    <th>Конец курса</th>
                    <th>Статус</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="group in groups" :key="group.id">
                    <td>{{ group.name }}</td>
                    <td>{{ group.teachers.firstName }}</td>
                    <td>{{ group.type_id }}</td>
                    <td>{{ group.startDate }}</td>
                    <td>{{ group.endDate }}</td>
                    <td>{{ group.status }}</td>
                    <td></td>
                    <td>
                        <div class="pull-right">
                            <b-tooltip title="Открыть профиль">
                                <router-link :to="{name:'group', params:{id: group.id}}" class="btn btn-outline-primary btn-sm"><span class="fa fa-user"></span></router-link>
                            </b-tooltip>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <group-form v-if="$common.data && $common.data.roles" ref="newGroup" :data="$common.data" :_form="newGroup" v-on:formSending="filtered"></group-form>

    </div>
</template>


<script>

    import { get } from '../../helpers/api'

    export default {

        data() {
            return {
                load: false,
                scrollLoad: false,
                newGroup: '',
                groups: [],
                total: 0,
                resource_url: '/api/groups',
                next_url: '',
                default_url: '/api/groups'
            }
        },
        components: {
            'group-form': require('./Form.vue'),
            'group-filter': require('./Filter.vue')
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

                    _this.groups = _this.groups.concat(json.data);

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
                this.groups = [];
                this.total = 0;
                this.filterData = this.$refs.filter.filterData;

                console.log(this.filterData);
                this.$nextTick(function () {
                    this.$router.push({ path: '/control/groups', query: this.filterData });
                    this.getList();
                });

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