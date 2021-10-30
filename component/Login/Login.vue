<template>
  <div class="p-3">

    <form @submit.prevent="process" v-if="user.length<1">
      <div class="m-y-2">
        <ui-input v-model:value="email" type="email" label="Email" required />
      </div>
      <div class="m-y-2">
        <ui-input v-model:value="password" type="password" label="Password" required />
      </div>
      <n-verification style="min-height: 165px;" v-model:code="code"></n-verification>
      <div class="grid-6-6 m-t-2">
        <router-link to="/register">
          <ui-button @click="$emit('loggedIn')">
            <ui-icon>person_add</ui-icon> Register
          </ui-button>
        </router-link>
        <ui-button color="primary-filled" type="submit" class="place-x-end" >
          <ui-icon>how_to_reg</ui-icon> Login
        </ui-button>
      </div>
      <ui-alert class="m-y-2" v-if="loginError" color="warning-filled">
        An error occurred. Wrong credentials?
      </ui-alert>
      <router-link  @click="$emit('loggedIn')" to="/password-reset" class="font-sm cursor-pointer m-y-2">forgot password</router-link>
    </form>
    <div v-if="user.length>0">
      <p>Welcome, {{user[0].user.user_name}}</p>
      <ui-button color="warning" @click="logout">logout</ui-button>
    </div>
  </div>
</template>

<script>
import uiInput from "/vue/ui/lib/ui.input";
import uiButton from "/vue/ui/lib/ui.button";
import uiIcon from "/vue/ui/lib/ui.icon";
import uiAlert from "/vue/ui/lib/ui.alert";

export default {
  components:{
    uiInput,
    uiButton,
    uiIcon,
    uiAlert
  },
  inject: ['neoanStore'],
  data:()=>({
    email: '',
    password: '',
    mode: 'login',
    user: [],
    code:'',
    sessionInterval:null,
    intervalTime:60000,
    loginError:false
  }),
  mounted(){
    // this.neoanStore.getAll('auth').then(this.hookUser)
    this.neoanStore.subscribe('auth', this.hookUser)
    this.sessionInterval = setInterval(this.reactiveSession,this.intervalTime);
  },
  methods:{
    reactiveSession(){
      API.get('/auth').then(res => {
        if(res.data.length<1){
          clearInterval(this.sessionInterval)
        }
      })
    },
    hookUser(res){
      this.user = res;
      if(res.length>0){
        clearInterval(this.sessionInterval)
        this.sessionInterval = setInterval(this.reactiveSession,this.intervalTime);
      }
    },
    logout(){
      API.delete('/auth').then(()=>{
        window.location.reload()
      })
    },
    async process(){
      try{
        await this.neoanStore.post('auth', this).then(res => {
          this.$emit('loggedIn')
        })
      } catch (e) {
        this.loginError = true;
      }


    }
  },
  template: `{{template}}`,
}
</script>