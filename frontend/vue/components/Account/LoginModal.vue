<template>
    <modal-form :hide-footer="true" ref="modal" :id="id" size="md" centered :title="langTitle" :disable-save-button="true"
                 @hidden="onHidden">
     <div class="public-page">
    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm">
                    <h2 class="card-title mb-0 text-center">
                        Welcome
                    </h2>
                    <h3 class="text-center">
                            <small class="text-muted">Radio</small>
                    </h3>
                </div>
            </div>

            <div id="login-form" action="" method="post">
                <div class="form-group">
                    <label for="username" class="mb-2">
                        <i class="material-icons mr-1" aria-hidden="true">email</i>
                        <strong>E-mail Address</strong>
                    </label>
                    <input type="email" v-model="email" id="username" name="username" class="form-control" placeholder="name@example.com" aria-label="E-mail Address'" required autofocus>
                </div>
                <div class="form-group mt-2">
                    <label for="password" class="mb-2">
                        <i class="material-icons mr-1" aria-hidden="true">vpn_key</i>
                        <strong>Password</strong>
                    </label>
                    <input v-model="password" type="password" id="password" name="password" class="form-control" placeholder="Enter your password"
                     aria-label="Password" required>
                </div>
                <div class="form-group mt-2">
                    <div class="custom-control">
                        <!-- <input type="checkbox" name="remember" id="frm_remember_me" value="1" class="toggle-switch custom-control-input"> -->
                        <label for="frm_remember_me" class="custom-control-label">
                            <a href="">Forgot Password</a>
                        </label>
                    </div>
                </div>
                <button @click="Login" role="button" title="Sign in" class="btn btn-login btn-primary btn-block mt-2 mb-3">
                    {{"Sign in"}}
                </button>
            </div>

            
        </div>
    </div>
</div>

    </modal-form>
</template>
<style>
#LoginModal___BV_modal_footer_{
    display: none !important;

}
#LoginModal{
    overflow: hidden;
    background:linear-gradient(90deg,#6a3470 20%,#be3acd 27%,#3bb742 100%);
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
    name: 'LoginModal',
    components: {ModalForm, BWrappedFormGroup, BFormFieldset},
    //emits: ['relist'],
    mixins: [validationMixin],
    props: {
        changePasswordUrl: String,
        id:'loginModal'
    },
    data() {
        return {
            category:[],
            songs:[],
            id:"LoginModal",
            email:'',
            password:'',
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
            // var config = {
            //     method: 'get',
            //     responseType: 'json',
            //     url: 'https://hgcradio.org/api/library/categories',
            //     // headers: { 
            //     //     'Authorization': `Bearer ${this.getToken()}`
            //     // },
            //     // data : obj
            // };
          
            // await this.axios(config)
            //     .then((response)=>{
            //         this.category = response.data.data;
            //     console.log(response.data.data);
                
               
            // }).catch( (error) => {
               
            // }).finally(()=>{
            //     //this.processing = false
            // })
            this.$refs.modal.show();
            
        },
         async Login(e) {
             e.preventDefault();
         
            var obj = {
                email:this.email,
                password:this.password
            }
            var config = {
                method: 'post',
                responseType: 'json',
                url: `https://hgcradio.org/api/auth/dj/login`,
                // headers: { 
                //     'Authorization': `Bearer ${this.getToken()}`
                // },
                  data : obj
            };
          
            await this.axios(config)
                .then((response)=>{
                   
                    console.log(response);
                if(response.data.status =="Success"){
                    localStorage.setItem('Login',true)
                    localStorage.setItem('UserData', JSON.stringify(response.data.data))
                    var usrname = response.data.data.user.dj_account.dj_account;
                    var pass = response.data.data.user.dj_account.password;
                    localStorage.setItem('usernamedj',usrname)
                    localStorage.setItem('passworddj',pass)
                    location.reload();
                }
               
            }).catch( (error) => {
                localStorage.removeItem('Login')
                localStorage.removeItem('UserData')
                localStorage.removeItem('usernamedj')
                localStorage.removeItem('passworddj')
            }).finally(()=>{
                //this.processing = false
            })
           // this.$refs.modal.show();
            
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
