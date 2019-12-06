<template>

    <div v-if="form">
        <b-modal v-if="data" ref="modal" size="lg" :title="title">

            <div  v-bind:class="{ 'has-error': errors && errors.teachers_id }" class="form-group row">
                <label class="col-3 col-form-label">Преподователь</label>
                <div class="col-9">
                    <!-- eslint-disable-next-line vue/no-duplicate-attributes -->
                    <select class="form-control" v-model="form.teacher_id">
                        <option disabled value="">не указано</option>
                        <option :key="teacher.id" v-for="teacher in $common.data.teachers" :value="teacher.id"> {{teacher.firstName}} {{teacher.secondName}}</option>
                    </select>
                    <form-error v-if="errors && errors.teacher_id" :errors="errors">
                        {{ errors.teacher_id[0] }}
                    </form-error>
                </div>
            </div>
            <div v-bind:class="{ 'has-error': errors && errors.type_id }" class="form-group row">
                <label class="col-3 col-form-label">Тип</label>
                <div class="col-9">
                    <input v-model="form.type_id" class="form-control" type="text" placeholder="Имя">
                    <form-error v-if="errors && errors.type_id" :errors="errors">
                        {{ errors.type_id[0] }}
                    </form-error>
                </div>
            </div>
            <div v-bind:class="{ 'has-error': errors && errors.name }" class="form-group row">
                <label class="col-3 col-form-label">Название</label>
                <div class="col-9">
                    <input v-model="form.name" class="form-control" type="text" placeholder="Фамилия">
                    <form-error v-if="errors && errors.name" :errors="errors">
                        {{ errors.name[0] }}
                    </form-error>
                </div>
            </div>

            <div v-bind:class="{ 'has-error': errors && errors.status }" class="form-group row">
                <label class="col-3 col-form-label">Статус</label>
                <div class="col-9">
                    <input v-model="form.status" class="form-control" type="text" placeholder="Фамилия">
                    <form-error v-if="errors && errors.status" :errors="errors">
                        {{ errors.status[0] }}
                    </form-error>
                </div>
            </div>
            <!--<div v-bind:class="{ 'has-error': errors && errors.birthDate }" class="form-group row">
                <label class="col-3 col-form-label">Дата рождения</label>
                <div class="col-9">
                    <input v-model="form.birthDate" class="form-control" type="text">
                    <form-error v-if="errors && errors.birthDate" :errors="errors">
                        {{ errors.birthDate[0] }}
                    </form-error>
                </div>
            </div>-->





            <div slot="modal-footer">
                <button
                        type="button"
                        class="btn btn-primary"
                        @click="sendForm"
                        :disabled="(formSending? true : false)"
                >
                    <i v-show="formSending" class="fa fa-spinner fa-spin"></i> {{ form.id ? 'Сохранить' : 'Добавить' }}
                </button>
            </div>

        </b-modal>
    </div>


</template>



<script>

    import { post } from '../../helpers/api'

    export default {

        props: ['data', '_form'],

        data() {
            return {
                errors: [],
                formSending: false,
                form: '',
                addForm: [],
                teachers: '',
                title: ''
            }
        },


        created() {
            this.form = this._form ? this._form : this.newGroup();
            },
        mounted() {
            this.title = this.form.id ? 'Редактировать пользователя' : 'Добавить пользователя';
        },
        components: {
            FormError : require('./../../components/FormError.vue'),
        },
        methods: {
            sendForm() {
                this.formSending = true;

                let _this = this;


                post(_this, '/api/group-save', this.form, function () {

                    _this.formSending = false;
                    _this.errors = '';
                    _this.hideModal();
                    _this.form = _this.form.id ? _this.form : _this.newGroup();
                    _this.$emit('formSending');


                }, function (error) {

                    _this.formSending = false;
                    _this.errors = error.response.data.errors;

                });


            },

            showModal() {
                this.$refs.modal.show();
            },
            hideModal() {
                this.$refs.modal.hide();
            },
            newGroup() {

                return {

                    'teacher_id': '',
                    'type_id': '',
                    'name': '',
                    'startDate': '',
                    'endDate': '',
                    'status': '',

                }

            },
            selectTeacher(val) {
                if (val)
                    this.form.teacher_id = val.id;
                this.form.teacher = val;
            },


        }



    }
</script>