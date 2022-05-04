<template>
    <modal-form :hide-footer="true" ref="modal" size="md" centered :title="langTitle" :disable-save-button="true"
                 @hidden="onHidden">
      <div class="row">
        <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a @click="fetchSongs(cat.id)" v-for="cat in category" :key="cat.id" :data-id="cat.id" class="nav-link active" :id="'v-pills-'+cat.id+'tab'" data-toggle="pill" :href="'#v-'+cat.id" role="tab" :aria-controls="'v-'+cat.id" >{{cat.name}}</a>
            <!-- <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile</a>
            <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</a>
            <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a> -->
            </div>
        </div>
        <div class="col-9">
            <div class="tab-content" id="v-pills-tabContent">
                <a href="#" @click="addToplaylist(song.file,song.id)"  :data-path="song.file" class="tab-pane fade show active" v-for="song in songs" :key="song.id" :data-id="song.id"  :id="'v-'+song.category_id" role="tabpanel" :aria-labelledby="'v-pills-'+song.category_id+'tab'"><span>{{song.title}}</span></a>
            <!-- <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">...</div>
            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">...</div>
            <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">...</div>
            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...</div> -->
            </div>
        </div>
        </div>
    </modal-form>
</template>
<style>
#edit-modal___BV_modal_footer_{
    display: none !important;

}
</style>
<script>
import {validationMixin} from 'vuelidate';
import {required, sameAs} from 'vuelidate/dist/validators.min.js';
import BWrappedFormGroup from "~/components/Form/BWrappedFormGroup";
import ModalForm from "~/components/Common/ModalForm";
import BFormFieldset from "~/components/Form/BFormFieldset";
import validatePassword from "~/functions/validatePassword";

export default {
    name: 'playListCatModal',
    components: {ModalForm, BWrappedFormGroup, BFormFieldset},
    emits: ['relist'],
    mixins: [validationMixin],
    props: {
        changePasswordUrl: String
    },
    data() {
        return {
            category:[],
            songs:[],
            form: {
                current_password: null,
                new_password: null,
                new_password2: null
            }
        };
    },
    validations: {
        form: {
            current_password: {required},
            new_password: {required, validatePassword},
            new_password2: {
                required,
                sameAs: sameAs('new_password')
            }
        }
    },
    computed: {
        langTitle() {
            return this.$gettext('Category Playlist');
        }
    },
    methods: {
        async open() {
            var config = {
                method: 'get',
                responseType: 'json',
                url: 'https://hgcradio.org/api/library/categories',
                // headers: { 
                //     'Authorization': `Bearer ${this.getToken()}`
                // },
                // data : obj
            };
          
            await this.axios(config)
                .then((response)=>{
                    this.category = response.data.data;
                console.log(response.data.data);
                
               
            }).catch( (error) => {
               
            }).finally(()=>{
                //this.processing = false
            })
            this.$refs.modal.show();
            
        },
         async fetchSongs(id) {
            
            var config = {
                method: 'get',
                responseType: 'json',
                url: `https://hgcradio.org/api/library/category/${id}`,
                // headers: { 
                //     'Authorization': `Bearer ${this.getToken()}`
                // },
                //  data : obj
            };
          
            await this.axios(config)
                .then((response)=>{
                    this.songs = response.data.data.data;
                    console.log(response.data.data.data);
                
               
            }).catch( (error) => {
               
            }).finally(()=>{
                //this.processing = false
            })
            this.$refs.modal.show();
            
        },
        async addToplaylist(path,id) {
            this.$root.$emit('fetch_file',path);

        },
        close() {
            this.$refs.modal.hide();
        },
        onHidden() {
            this.$v.form.$reset();
        },
        onSubmit() {
            this.$v.form.$touch();
            if (this.$v.form.$anyError) {
                return;
            }

            this.$wrapWithLoading(
                this.axios.put(this.changePasswordUrl, this.form)
            ).finally(() => {
                this.$refs.modal.hide();
                this.$emit('relist');
            });
        }
    },mounted(){
        //alert()
    }
};
</script>
