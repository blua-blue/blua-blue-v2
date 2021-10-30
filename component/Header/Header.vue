<template>
  <header class="p-x-3 b-b-accent b-b-1 b-primary-dark bg-primary">
    <div class="grid grid-2-8-2">
      <figure>
        <a class="cursor-pointer" href="{{base}}" @click.prevent="navigate('/')" >
          <img style="max-width: 50px" class="b-transparent hover:b-primary-light-75 b-1 b-rounded hover:raise-1-primary-50" src="{{base}}asset/img/blua-blue-icon-96x96.png" alt="logo">
        </a>
      </figure>
      <div class="place-y-center d-flex">
        <search class="f-1 d-hidden md:d-block"/>

        <section class="p-l-3">
          <ui-button @click.prevent="navigate('/ui')" class="m-r-2" color="primary-filled">
            <a class="text-decoration-none text-white" href="{{base}}ui">UI</a>
          </ui-button>
          <ui-button @click.prevent="navigate('/articles')"  class="m-r-2" color="primary-filled">
            <ui-icon n-if="false">library_books</ui-icon>
            <a class="text-decoration-none text-white" href="{{base}}articles"> read</a>
          </ui-button>
          <ui-button @click.prevent="navigate('/write/new', true)"  class="m-r-2" color="primary-filled">
            <ui-icon n-if="false">library_add</ui-icon>
            <a class="text-decoration-none text-white" href="{{base}}articles"> write</a>
          </ui-button>
        </section>


      </div>
      <div class="place-x-end place-y-center position-relative text-white" v-if="false">Loading...</div>
      <div class="place-x-end place-y-center position-relative" n-if="false">
        <div title="user" class="cursor-pointer " v-if="user" @click="showUserMenu=!showUserMenu">
          <div class="bg-white-50 b-round w-50px h-50px" :style="userIconStyle"></div>

        </div>
        <ui-button color="primary-filled" @click="login" v-else >
          Login
        </ui-button>
        <transition name="swipe">
          <div class="position-absolute bg-white-90 p-5 b-rounded" style="right: 0" v-if="showUserMenu">
            <div n-if="false" class="text-center">{{user.user_name}}</div>
            <div class="menu-button cursor-pointer" @click="navigate('/profile/'+user.user_name); showUserMenu=false;">Profile</div>
            <div class="menu-button cursor-pointer" @click="logout">Logout</div>
          </div>
        </transition>
        <teleport to="body">
          <ui-modal title="Log in or create account" :show="showLoginModal" @close="showLoginModal = false">
            <login @logged-in="showLoginModal = false"></login>
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
    {"home":"/"},{"ui":"/ui"},{"articles":"/articles/:keyword*"},
    {"passwordReset":"/password-reset/:confirm_code*"},
    {"article":"/article/:slug"},{"register":"/register"},
    {"termsConditions":"/terms-conditions"},{"profile":"/profile/:userName"},{"write":"/write/:id"}
  ],
  "store":{
    "auth":{"get":"/auth,preload", "post":"/auth", "delete":"/auth", "put":"/auth"},
    "article":{"get":"/article,preload","post":"/article","put":"/article"},
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
  }),
  inject:['neoanStore'],
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

    this.neoanStore.subscribe('auth', u => {

      this.user = null;
      if(u.length>0){
        this.user = u[0].user;
      }

    })
  },
  methods: {
    navigate(to, requiresAuth){
      if(requiresAuth && !this.user){
        this.login();
        return;
      }
      this.$router.push(to)
    },
    async logout(){
      await API.delete('/auth')
      window.location.reload();
    },
    login(){
      this.showLoginModal = true
    }
  },
  template: `{{template}}`,
}
</script>