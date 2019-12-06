<template>

    <div v-if="teacher">

        <div class="col-12 col-md-10 offset-md-2 pr-5">
        <div class="mb-5 mt-1">
            <img :src="teacher.photo" class="rounded-circle float-left mr-2" style="margin-top: -5px; width: 40px;">
            <span class="h4">{{ teacher.firstName }} {{ teacher.secondName }}</span>
            <b-tooltip class="d-inline-block" triggers="hover" content="редактировать">
                <button type="button" class="btn btn-secondary text-left btn-sm " @click="edit()" ><span class="fa fa-pencil"></span></button>
            </b-tooltip>
            <b-tooltip class="d-inline-block" triggers="hover" content="удалить">
                <button type="button" class="btn btn-danger text-left btn-sm" @click="$refs.modalDelete.show()"><span class="fa fa-trash"></span></button>
            </b-tooltip>
        </div>

        <div class="h5">Контактная информация</div>
        <table class="table table-sm table-responsive">
            <tbody>
            <tr>
                <td><span class="h6">Телефон</span></td>
                <td>{{ teacher.phone }}</td>
            </tr>
            <tr>
                <td><span class="h6">Дата рождения</span></td>
                <td>{{ teacher.birthDate }}</td>
            </tr>
            <tr>
                <td><span class="h6">Дата создания</span></td>
                <td>{{ teacher.created_at }}</td>
            </tr>
            </tbody>
        </table>
    </div>
        <div class="col-12 col-md-10 offset-md-2 pr-5">

            <div class="h5">Группы преподования</div>
            <table class="table table-sm table-responsive">
                <thead>
                <tr>
                    <th><span class="h5">Название</span></th>
                    <th><span class="h5">Начало курса</span></th>
                    <th><span class="h5">Конец курса</span></th>
                    <th></th>
                </tr>
                </thead>
                <tbody v-for="course in teacher.groups" :key="course.id">
                <tr>
                    <td><span class="h6">{{ course.name }}</span></td>
                    <td><span class="h6">{{ course.startDate }}</span></td>
                    <td><span class="h6">{{ course.endDate }}</span></td>
                    <td> <b-tooltip title="Открыть Группу">
                        <router-link :to="{name:'group', params:{id: course.id}}" class="btn btn-outline-primary btn-sm"><span class="fa fa-group"></span></router-link>
                    </b-tooltip></td>
                </tr>

                </tbody>
            </table>
        </div>

        <teacher-form v-if="form" ref="editPupil" :data="$common.data" :_form="form" v-on:formSending="getItem"></teacher-form>

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

    import { del, get } from '../../helpers/api'

    export default {

        props: ['id','teacher_id'],

        data() {

            return {
                errors: [],
                teacher: '',
                courses: '',
                form: '',
            }

        },

        components: {
            'teacher-form': require('./Form.vue'),
        },

        methods: {
            remove() {

                let _this = this;

                del(_this, '/api/teacher-delete/' + this.id, {}, function () {
                    _this.$router.push('/control/teachers');
                });

            },
            edit() {
                this.form = JSON.parse(JSON.stringify(this.teacher));

                this.$nextTick(function () {
                    this.$refs.editPupil.showModal();
                });

            },
            getItem() {

                let _this = this;

                get(_this, '/api/teacher/' + this.id, {}, function (response) {
                    _this.teacher = response.data;
                });
            },

        },

        created() {

            this.getItem();

        }

    }


</script>