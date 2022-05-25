<template>

    <section id="content" role="main" style="height: 100vh;">
        <header class="navbar bg-primary-dark shadow-sm fixed-top">
    
    <a class="navbar-brand ml-0 mr-auto" href="#"><img style="height: 52px;" :src="logo" /></a>

    <div style="display: contents;"><div><!----></div> <!----></div>
    <p style="margin-bottom: 0;" class="username">Hi , {{username}}</p>
    <div class="dropdown ml-3">
        <button aria-expanded="false" aria-haspopup="true" class="navbar-toggler" aria-label="Toggle Menu" data-toggle="dropdown" type="button">
            <i class="material-icons" aria-hidden="true">more_vert</i>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
            <!-- <li>
                <a class="dropdown-item" href="/dashboard">
                    <i class="material-icons" aria-hidden="true">home</i>
                    Dashboard                </a>
            </li>
            <li class="dropdown-divider">&nbsp;</li>
                            <li>
                    <a class="dropdown-item" href="/admin">
                        <i class="material-icons" aria-hidden="true">settings</i>
                        System Administration                    </a>
                </li>
                        <li>
                <a class="dropdown-item" href="/profile">
                    <i class="material-icons" aria-hidden="true">account_circle</i>
                    My Account                </a>
            </li>
            <li>
                <a class="dropdown-item" href="/profile/theme">
                    <i class="material-icons" aria-hidden="true">invert_colors</i>
                    Switch Theme                </a>
            </li>
            <li>
                <a class="dropdown-item" href="https://docs.azuracast.com/en/user-guide/troubleshooting" target="_blank">
                    <i class="material-icons" aria-hidden="true">help</i>
                    Help                </a>
            </li> -->
            <li class="dropdown-divider">&nbsp;</li>
             <li>
                <a class="dropdown-item" @click="Logout" href="#">
                <i class="material-icons" aria-hidden="true">exit_to_app</i>
                Sign Out</a></li>
            </ul>
    </div>
</header>
            <login-modal ref="LoginModal" :id="id" :login="LoginModal"
                                       ></login-modal>
      
        <div class="container pt-5">
            <div class="form-row">
                <div class="col-md-4 mb-sm-4">
                    <settings-panel v-bind="{ stationName, baseUri, libUrls }"></settings-panel>
                </div>

                <div class="col-md-8">
                    <div class="form-row mb-3">
                        <div class="col-md-12">
                            <microphone-panel></microphone-panel>
                        </div>
                    </div>
                    <div class="form-row mb-3">
                        <div class="col-md-12">
                            <mixer-panel></mixer-panel>
                        </div>
                    </div>
                    <div class="form-row mb-4">
                        <div class="col-md-6 mb-sm-4">
                            <playlist-panel id="playlist_1"></playlist-panel>
                        </div>

                        <div class="col-md-6">
                            <playlist-panel id="playlist_2"></playlist-panel>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<style>
.pt-5 {
    padding-top: 5rem!important;
}
</style>
<script>

import MixerPanel from './WebDJ/MixerPanel.vue';
import MicrophonePanel from './WebDJ/MicrophonePanel.vue';
import PlaylistPanel from './WebDJ/PlaylistPanel.vue';
import SettingsPanel from './WebDJ/SettingsPanel.vue';
//import playListCatModal from "../Account/playListCatModal.vue";
import Stream from './WebDJ/Stream.js';
import LoginModal from "../Account/LoginModal.vue";


export default {
    data: function () {
        return {
            'stream': Stream,
            LoginModal:null,
            username:'',
             "id":'LoginModal'
        };
    },
    components: {
        MixerPanel,
        MicrophonePanel,
        PlaylistPanel,
        SettingsPanel,
         LoginModal
    },
    props: {
        stationName: String,
        libUrls: Array,
        baseUri: String,
        changePasswordUrl: String,

    },
    provide: function () {
        return {
            getStream: this.getStream,
            resumeStream: this.resumeStream
        };
    },
    methods: {
        getStream: function () {
            this.stream.init();

            return this.stream;
        },
        resumeStream: function () {
            this.stream.resumeContext();
        },
        doChangePassword() {
            //this.$refs.playListCatModal.open();
        },
        Logout(){
            localStorage.removeItem('Login');
            localStorage.removeItem('UserData');

            location.reload();
        }
    },
     mounted() {
         if(localStorage.getItem('Login') == 'true') {
             let user = JSON.parse(localStorage.getItem('UserData'));
             this.username = user.user.name;
             this.logo = user.user.avatar_url;
             console.log(user.user.dj_account);
             let us = user.user.dj_account.username;
             let pp = user.user.dj_account.password;
              localStorage.setItem('usernamedj',us)
              localStorage.setItem('passworddj',pp)
              
            //   setTimeout(()=>{
               
            //    // $('#usernamedj').val(localStorage.getItem('usernamedj'));
            //    // $('#passworddj').val(localStorage.getItem('passworddj'));
            //     $('.djclick').click();
            //  },3000)
         } else {
             this.$refs.LoginModal.open();
         }
        
    },
    
};
</script>
