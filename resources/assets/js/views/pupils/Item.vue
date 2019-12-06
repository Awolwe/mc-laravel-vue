<template>

    <div v-if="pupil">

        <div class="col-12 col-md-10 offset-md-2 pr-5">
            <div class="mb-5 mt-1">
                <img :src="pupil.photo" class="rounded-circle float-left mr-2" style="margin-top: -5px; width: 40px;">
                <span class="h4">{{ pupil.firstName }} {{ pupil.secondName }}</span>
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
                    <td>{{ pupil.phone }}</td>
                </tr>
                <tr>
                    <td><span class="h6">Дата рождения</span></td>
                    <td>{{ pupil.birthDate }}</td>
                </tr>
                <tr>
                    <td><span class="h6">Дата создания</span></td>
                    <td>{{ pupil.created_at }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <pupil-form v-if="form" ref="editPupil" :data="$common.data" :_form="form" v-on:formSending="getItem"></pupil-form>

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

        props: ['id','pupil_id'],

        data() {

            return {
                errors: [],
                pupil: '',
                form: '',
            }

        },

        components: {
            'pupil-form': require('./Form.vue'),
        },

        methods: {
            remove() {

                let _this = this;

                del(_this, '/api/pupil-delete/' + this.id, {}, function () {
                    _this.$router.push('/control/pupils');
                });

            },
            edit() {
                this.form = JSON.parse(JSON.stringify(this.pupil));

                this.$nextTick(function () {
                    this.$refs.editPupil.showModal();
                });

            },
            getItem() {

                let _this = this;

                get(_this, '/api/pupil/' + this.id, {}, function (response) {
                    _this.pupil = response.data;
                });
            },

        },

        created() {

            this.getItem();

        }

    }


</script>