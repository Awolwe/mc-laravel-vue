<template>
    <div>
        <!-- Результаты -->
        <div class="col-12 col-md-10 offset-md-2">
            <teacher-filter v-if="$common.data.roles" ref="filter" :load="load"
                            v-on:filtered="filtered"></teacher-filter>
            Найдено <b>{{ total }}</b> преподователей
            <button type="button" class="btn btn-primary btn-sm ml-2" @click="$refs.newTeacher.showModal()">добавить
            </button>

            <table class="table mt-4">
                <thead class="thead-default">
                <tr>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="teacher in teachers" :key="teacher.id">
                    <td>{{ teacher.firstName }}</td>
                    <td>{{ teacher.secondName }}</td>
                    <td>
                        <div class="pull-right">
                            <b-tooltip title="Открыть профиль">
                                <router-link :to="{name:'teacher', params:{id: teacher.id}}"
                                             class="btn btn-outline-primary btn-sm">
                                    <span class="fa fa-user"></span>
                                </router-link>
                            </b-tooltip>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <teacher-form v-if="$common.data && $common.data.roles" ref="newTeacher" :data="$common.data"
                      :_form="newTeacher" v-on:formSending="filtered"></teacher-form>

    </div>
</template>


<script>

    import {get} from '../../helpers/api'

    export default {

        data() {
            return {
                load: false,
                scrollLoad: false,
                newTeacher: '',
                teachers: [],
                total: 0,
                resource_url: '/api/teachers',
                next_url: '',
                default_url: '/api/teachers'
            }
        },
        components: {
            'teacher-form': require('./Form.vue'),
            'teacher-filter': require('./Filter.vue')
        },
        methods: {
            getList() {


                this.resource_url = this.scrollLoad ? this.next_url : this.resource_url;

                if (!this.resource_url) {
                    this.scrollLoad = false;
                    return false;
                }

                this.load = true;

                let _this = this;


                get(_this, this.resource_url, {params: this.filterData}, function (response) {

                    let json = response.data;


                    _this.next_url = json.next_page_url;

                    _this.teachers = _this.teachers.concat(json.data);

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
                this.teachers = [];
                this.total = 0;
                this.filterData = this.$refs.filter.filterData;

                console.log(this.filterData);
                this.$nextTick(function () {
                    this.$router.push({path: '/control/teachers', query: this.filterData});
                    this.getList();
                });

            },
            handleScroll() {
                /* eslint-disable-next-line no-undef */
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 100 && !this.scrollLoad) {
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