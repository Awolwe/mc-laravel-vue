<template>

    <div v-if="group">

        <div class="col-12 col-md-10 offset-md-2 pr-5">
            <div class="mb-5 mt-1">
                <img :src="group.photo" class="rounded-circle float-left mr-2" style="margin-top: -5px; width: 40px;">
                <span class="h4">{{ group.name }}</span>
                <b-tooltip class="d-inline-block" triggers="hover" content="редактировать">
                    <button type="button" class="btn btn-secondary text-left btn-sm " @click="edit()"><span
                            class="fa fa-pencil"></span></button>
                </b-tooltip>
                <b-tooltip class="d-inline-block" triggers="hover" content="удалить">
                    <button type="button" class="btn btn-danger text-left btn-sm" @click="$refs.modalDelete.show()">
                        <span class="fa fa-trash"></span></button>
                </b-tooltip>
            </div>

            <div class="h5">Контактная информация</div>
            <table class="table table-sm table-responsive">
                <tbody>
                <tr>
                    <td>Название</td>
                    <td>{{ group.name }}</td>
                </tr>
                <tr>
                    <td>Тип</td>
                    <td>{{ group.type_id }}</td>
                </tr>
                <tr>
                    <td>Начало курса</td>
                    <td>{{ group.startDate }}</td>
                </tr>
                <tr>
                    <td>Конец курса</td>
                    <td>{{ group.endDate }}</td>
                </tr>
                <tr>
                    <td>Статус</td>
                    <td>{{ group.status }}</td>
                </tr>
                <tr>
                    <td colspan="2"><h5>Преподователь</h5></td>
                </tr>
                <tr>
                    <td>Имя преподователя</td>
                    <td>{{ group.teachers.firstName }}</td>
                </tr>
                <tr>
                    <td>Фамилия преподователя</td>
                    <td>{{ group.teachers.secondName }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-12 col-md-10 offset-md-2 pr-5">
            <div class="d-inline h5">Обучающиеся студенты</div>
            <b-tooltip class="ml-2 d-inline-block" triggers="hover" content="Добавить студентов">
                <button type="button" class="btn btn-info text-left btn-sm" @click="addStudents()">
                    Добавить студентов</button>
            </b-tooltip>


            <table class="table table-sm table-responsive">
                <thead>
                <tr>
                    <th><span class="h5">Имя</span></th>
                    <th><span class="h5">Фамилия</span></th>
                    <th><span class="h5">Номер</span></th>
                    <th></th>
                </tr>
                </thead>
                <tbody v-for="pupil in group.pupils" :key="pupil.id">
                <tr>
                    <td><span class="h6">{{ pupil.firstName }}</span></td>
                    <td><span class="h6">{{ pupil.secondName }}</span></td>
                    <td><span class="h6">{{ pupil.phone }}</span></td>
                    <td> <b-tooltip title="Открыть Группу">
                        <router-link :to="{name:'pupil', params:{id: pupil.id}}" class="btn btn-outline-primary btn-sm"><span class="fa fa-group"></span></router-link>
                    </b-tooltip></td>
                </tr>

                </tbody>
            </table>
        </div>
        <group-form v-if="form" ref="editGroup" :data="$common.data" :_form="form"
                    v-on:formSending="getItem"></group-form>
        <student-form v-if="form" ref="addPupil" :data="$common.data" :_form="form"
                    v-on:formSending="getItem"></student-form>

        <b-modal ref="modalDelete" title="Подтвердите удаление">
            Вы действительно хотите удалить пользователя? Данное действие нельзя отменить.
            <div slot="modal-footer">
                <button type="button" class="btn btn-secondary" @click="$refs.modalDelete.hide()">Отменить</button>
                <button type="button" class="btn btn-danger" @click="remove()">Удалить</button>
            </div>
        </b-modal>


    </div>

</template>

<script>

    import {del, get} from '../../helpers/api'

    export default {

        props: ['id', 'group_id'],

        data() {

            return {
                errors: [],
                group: '',
                courses: '',
                form: '',
            }

        },

        components: {
            'group-form': require('./Form.vue'),
            'student-form': require('./Student.vue'),

        },

        methods: {
            remove() {

                let _this = this;

                del(_this, '/api/group-delete/' + this.id, {}, function () {
                    _this.$router.push('/control/groups');
                });

            },
            edit() {
                this.form = JSON.parse(JSON.stringify(this.group));

                this.$nextTick(function () {
                    this.$refs.editGroup.showModal();
                });

            },
            addStudents() {
                this.form = JSON.parse(JSON.stringify(this.group));

                this.$nextTick(function () {
                    this.$refs.addPupil.showModal();
                });

            },
            getItem() {

                let _this = this;

                get(_this, '/api/group/' + this.id, {}, function (response) {
                    _this.group = response.data;
                });
            },

        },

        created() {

            this.getItem();

        }

    }


</script>