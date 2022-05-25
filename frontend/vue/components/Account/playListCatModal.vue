<template>
    <modal-form :hide-footer="true" ref="modal" size="md" centered :title="langTitle" :disable-save-button="true"
                 @hidden="onHidden">
      <div class="row">
        <div class="col-3">
            <div class="flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a @click="fetchSongs(cat.id)" v-for="cat in category" :key="cat.id" :data-id="cat.id" class="nav-link active" :id="'v-pills-'+cat.id+'tab'" data-toggle="pill" :href="'#v-'+cat.id" role="tab" :aria-controls="'v-'+cat.id" >{{cat.name}}</a>
            <!-- <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile</a>
            <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</a>
            <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a> -->
            </div>
        </div>
        <div class="col-9">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="songtab" role="tabpanel" :aria-labelledby="'v-pills-'+song.category_id+'tab'" v-for="(song, index) in songs" :key="song.id" :data-id="song.id"  :id="'v-'+song.category_id" >
                    <span class="song_name">
                       {{(index)+1}}.  <a href="#" @click="addToplaylist(song.file,song.id,song.category_id)"  :data-path="song.file" class="tab-pane fade show active"  ><span>{{song.title}}</span></a>
                    </span>
                    <span class="song_icon">
                       <a href="#" @click="addToplaylist(song.file,song.id,song.category_id)"  :data-path="song.file" class="tab-pane fade show active"  > <img style="width: 15px;height: 15px;" src="data:image/png;base64,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" /></a>
                    </span>
                </div>
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
    //emits: ['relist'],
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
        async addToplaylist(path,id,cat_id) {
            this.$root.$emit('fetch_file',path,id,cat_id);

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
