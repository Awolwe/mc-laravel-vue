<template>
    <div v-if="form">
        <b-modal v-if="data" ref="modal" size="lg" :title="title">

            <div v-bind:class="{ 'has-error': errors && errors.firstName }" class="form-group row">
                <label class="col-3 col-form-label">Имя</label>
                <div class="col-9">
                    <input v-model="form.firstName" class="form-control" type="text" placeholder="Имя">
                    <form-error v-if="errors && errors.firstName" :errors="errors">
                        {{ errors.firstName[0] }}
                    </form-error>
                </div>
            </div>
            <div v-bind:class="{ 'has-error': errors && errors.secondName }" class="form-group row">
                <label class="col-3 col-form-label">Фамилия</label>
                <div class="col-9">
                    <input v-model="form.secondName" class="form-control" type="text" placeholder="Фамилия">
                    <form-error v-if="errors && errors.secondName" :errors="errors">
                        {{ errors.secondName[0] }}
                    </form-error>
                </div>
            </div>

            <div v-bind:class="{ 'has-error': errors && errors.phone }" class="form-group row">
                <label class="col-3 col-form-label">Телефон</label>
                <div class="col-9">
                    <masked-input v-model="form.phone" class="form-control" mask="1 (111) 111 11 11" placeholder="8 (707) 465 48 12"/>
                    <form-error v-if="errors && errors.phone" :errors="errors">
                        {{ errors.phone[0] }}
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
                title: ''
            }
        },
        computed: {
            roles() {
                var arr=[];
                if(this.$common.data) {
                    this.$common.data.roles.forEach(function(role) {
                        arr.push(role);
                    });
                }
                return arr;
            }
        },
        created() {
            this.form = this._form ? this._form : this.newTeacher();
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


                post(_this, '/api/teacher-save', this.form, function () {

                    _this.formSending = false;
                    _this.errors = '';
                    _this.hideModal();
                    _this.form = _this.form.id ? _this.form : _this.newTeacher();
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
            selectRole(val) {
                if (val)
                    this.form.role_id = val.id;
                this.form.role = val;
            },

            newTeacher() {

                return {

                    id: '',
                    photo: '',
                    name: '',
                    phone: '',

                }

            },


        }



    }
</script>