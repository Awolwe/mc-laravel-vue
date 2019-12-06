<template>

    <div v-if="form">
        <b-modal v-if="data" ref="modal" size="lg" :title="title">


                <table class="table">
                    <thead>
                    <tr>
                        <th><span class="h5">checkbox</span></th>
                        <th><span class="h5">Имя</span></th>
                        <th><span class="h5">Фамилия</span></th>
                        <th><span class="h5">Номер</span></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody v-for="pupil in pupils" :key="pupil.id">
                    <tr>
                        <td><input type="checkbox" @click="toConsole()" :id="pupil.id" :value="pupil.id" v-model="checkedNames"></td>
                        <td><span class="h6">{{ pupil.firstName }}</span></td>
                        <td><span class="h6">{{ pupil.secondName }}</span></td>
                        <td><span class="h6">{{ pupil.phone }}</span></td>
                        <td> </td>
                    </tr>

                    </tbody>
                </table>




            <div slot="modal-footer">
                <button
                        type="button"
                        class="btn btn-primary"
                        @click="sendForm"
                        :disabled="(formSending? true : false)"
                >
                    <i v-show="formSending" class="fa fa-spinner fa-spin"></i> Сохранить
                </button>
            </div>

        </b-modal>
    </div>


</template>



<script>

    import { post,get } from '../../helpers/api'

    export default {

        props: ['data', '_form'],

        data() {
            return {
                errors: [],
                formSending: false,
                form: '',
                title: '',
                checkedNames: [],
                pupils: [],

            }
        },


        created() {
            this.form = this._form ? this._form : this.newGroup();
        },
        mounted() {
        },

        components: {

            //FormError : require('./../../components/FormError.vue'),
        },
        methods: {
            getListStudents() {
                this.load = true;
                let _this = this;
                get(_this, '/api/group/'+ _this.form.id+'/not', {}, function (response) {

                    let json = response.data;
                    _this.pupils = json;
                    console.log(_this.pupils);
                    _this.load = false;

                }, function () {
                    _this.load = false;
                    console.log('error');

                });


            },
            sendForm() {
                this.formSending = true;

                let _this = this;


                post(_this, '/api/group/'+_this.form.id+'/sync', {id: _this.form.id, names: this.checkedNames}, function (response) {

                    console.log(_this.form.id);
                    console.log(response.data);
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
                this.getListStudents();
                this.$refs.modal.show();
            },
            hideModal() {
                this.$refs.modal.hide();
            },
            toConsole() {
                console.log(this.checkedNames);
            },


        }



    }
</script>