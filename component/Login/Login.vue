<template>
  <div class="p-3">

    <form @submit.prevent="process" v-if="user.length<1||forceForm">
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
        An error occurred. Wrong credentials or email not confirmed?
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
  props:{
    forceForm:{
      type: Boolean,
      default: false
    }
  },
  components:{
    uiInput,
    uiButton,
    uiIcon,
    uiAlert
  },
  inject: ['neoanStore','API'],
  data:()=>({
    email: '',
    password: '',
    mode: 'login',
    user: [],
    code:'',
    loginError:false
  }),
  mounted(){
    this.neoanStore.subscribe('auth', this.hookUser)
  },
  methods:{

    hookUser(res){
      this.user = res;

    },
    logout(){
      this.API.delete('/auth').then(()=>{
        window.location.reload()
      })
    },
    async process(){
      try{
        await this.neoanStore.post('auth', this).then(res => {
          this.password = '';
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