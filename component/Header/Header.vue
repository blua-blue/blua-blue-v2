<template>
  <header class="p-x-3 b-b-accent b-b-1 b-primary-dark bg-primary">
    <div class="grid md:grid-3-7-2 m-y-3 md:m-y-0">
      <figure class="position-absolute md:position-relative md:place-x-start " style="top:1px;right:1px">
        <a class="cursor-pointer bg-white-50 d-block b-1 b-rounded hover:raise-1-primary-50 b-transparent hover:b-primary-light-75" href="{{base}}" @click.prevent="navigate('/')" >
          <img style="max-width: 50px" class="" src="{{base}}asset/img/blua-blue-icon-96x96.png" alt="logo">
        </a>
      </figure>
      <div class="place-y-center d-flex">
        <search class="f-1 d-hidden md:d-block p-r-3"/>

        <section>

          <ui-button @click.prevent="navigate('/articles')"  class="m-r-2" color="primary-filled">
            <ui-icon n-if="false">library_books</ui-icon>
            <a class="text-decoration-none text-white" href="{{base}}articles"> read</a>
          </ui-button>
          <ui-button @click.prevent="navigate('/write/new', true)"  class="m-r-2" color="primary-filled">
            <ui-icon n-if="false">library_add</ui-icon>
            <a class="text-decoration-none text-white" href="{{base}}articles"> write</a>
          </ui-button>
          <ui-button v-if="user" @click.prevent="navigate('/profile/'+user.user_name, true)"  class="m-r-2" color="primary-filled">
            <ui-icon n-if="false">account_box</ui-icon>
            <a class="text-decoration-none text-white" href="{{base}}articles"> profile</a>
          </ui-button>
        </section>


      </div>
      <div class="place-x-end place-y-center position-relative text-white" v-if="false">Loading...</div>
      <div class="md:place-x-end place-y-center position-relative" n-if="false">
        <router-link :to="'/profile/'+user.user_name" title="user" class="cursor-pointer " v-if="user" @click="showUserMenu=!showUserMenu">
          <div class="bg-white-50 b-round w-50px h-50px" :style="userIconStyle"></div>

        </router-link>
        <ui-button color="primary-filled" @click="login" v-else >
          Login
        </ui-button>
        <transition name="swipe">
          <div class="position-absolute bg-white-90 p-5 b-rounded" style="right: 0" v-if="showUserMenu">
            <ui-icon class="position-absolute cursor-pointer" style="right: 5px; top:5px" @click="showUserMenu=false">close</ui-icon>
            <a class="text-center text-decoration-none text-black" href="{{base}}admin" v-if="user.user_type==='admin'">administration</a>
            <div n-if="false" class="text-center">{{user.user_name}}</div>
            <div class="menu-button cursor-pointer" @click="logout">Logout</div>
          </div>
        </transition>
        <teleport to="body">
          <ui-modal title="Log in or create account" :show="showLoginModal" @close="showLoginModal = false">
            <login @logged-in="loggedIn" :force-form="true"></login>
          </ui-modal>
        </teleport>

      </div>
    </div>


  </header>
</template>
<style>


</style>
<script>
import uiButton from '/vue/ui/lib/ui.button';
import uiIcon from '/vue/ui/lib/ui.icon';
import uiInput from '/vue/ui/lib/ui.input';
import uiModal from '/vue/ui/lib/ui.modal';
import login from '/vue/login';

import search from '/vue/search'
@import({
  "routes":[
    {"admin":"/admin"},
    {"home":"/"},{"articles":"/articles/:keyword*"},
    {"contactUs":"/contact-us"},
    {"passwordReset":"/password-reset/:confirm_code*"},
    {"article":"/article/:slug"},{"register":"/register"},
    {"termsConditions":"/terms-conditions"},{"profile":"/profile/:userName"},
    {"write":"/write/:id"},
    {"writeInterface":"/write-interface"},
    {"devTo":"/dev-to"},
    {"medium":"/medium"},
    {"sdk":"/sdk"}
  ],
  "store":{
    "auth":{"get":"/auth,preload", "post":"/auth", "delete":"/auth", "put":"/auth"},
    "article":{"get":"/article,preload","post":"/article","put":"/article","delete":"/article"},
    "profile":{"get":"/profile"},
    "category":{"category":"/category,preload"},
    "image":{"get":"/image","post":"/image","delete":"/image"}}
})



export default {
  components: {
    uiButton,
    uiIcon,
    uiInput,
    uiModal,
    login,
    search
  },
  data:()=>({
    user: null,
    showUserMenu: false,
    showLoginModal:false,
    afterNavigation:null
  }),
  inject:['neoanStore','API'],
  watch:{
    showUserMenu(newV){
      let timeout;
      if(newV){
        timeout = setTimeout(()=>{
          this.showUserMenu = false;
        },5000)
      } else {
        clearTimeout(timeout)
      }
    }
  },
  computed:{
    userIconStyle(){
      if(this.user && typeof this.user.image !== 'undefined'){
        return {
          backgroundImage: this.user.image.path ? 'url(' + this.user.image.path +')' : null,
          backgroundSize:'cover'
        }
      }
      return {}
    }
  },
  async mounted() {
    let sessionInterval;
    this.neoanStore.subscribe('auth', u => {
      clearInterval(sessionInterval);
      console.log('user-update')
      this.user = null;
      if(u.length>0){
        this.user = u[0].user;
        sessionInterval = setInterval(()=>{
          this.API.get('/auth').then(res => {
            if(res.data.length<1){
              clearInterval(sessionInterval);
              this.neoanStore.delete('auth')
              this.login();
            }
          })
        },60000)
      }

    })
  },
  methods: {
    navigate(to, requiresAuth){
      if(requiresAuth && !this.user){
        this.afterNavigation = to;
        this.login();
        return;
      }
      this.$router.push(to)
    },
    async logout(){
      await API.delete('/auth')
      this.afterNavigation = null;
      window.location.reload();
    },
    loggedIn(){
      this.showLoginModal = false;
      this.navigate(this.afterNavigation);
    },
    login(){
      this.showLoginModal = true
    }
  },
  template: `{{template}}`,
}
</script>