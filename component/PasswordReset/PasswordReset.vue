<template>
  <div class="container" n-if="false">
    <h1>Reset your password</h1>
    <form @submit.prevent="reset" v-if="step===0">
      <p class="font-md text-accent">Step 1/2</p>
      <div class="d-flex">
        <ui-input class="f-1" v-if="mode==='user_name'" v-model:value="user_name" label="Username" required></ui-input>
        <ui-input class="f-1" v-if="mode==='slug'" v-model:value="slug" label="Link to one of your articles" required></ui-input>
        <div class="m-2 place-y-end place-x-end">
          <ui-button v-if="mode==='user_name'" @click="mode='slug'">forgot username?</ui-button>
          <ui-button v-if="mode==='slug'" @click="mode='user_name'">switch back</ui-button>
          <ui-button class="m-l-1" color="accent-filled" v-if="mode==='slug'" @click="showHelp=true">help</ui-button>
        </div>
      </div>
      <div class="m-y-2" v-if="showHelp">
        <p>
          In order to reset your password you need to know either your username or the association between your email and an article.
          Both the full link or the slug (e.g. my-awesome-article) will suffice.
          If you cannot obtain either of these, please write an email <strong>using your registered email account</strong> to contact@blua.blue.
        </p>
      </div>
      <div class="m-y-2">
        <ui-input v-model:value="email" label="Email" required></ui-input>
      </div>
      <div class="d-flex">
        <ui-input class="f-1" v-model:value="password" :type="showPassword?'text':'password'" label="New Password" required />
        <div class="font-lg text-primary place-y-end cursor-pointer" @click="showPassword=!showPassword">
          <ui-icon v-if="showPassword">visibility</ui-icon>
          <ui-icon v-if="!showPassword">visibility_off</ui-icon>
        </div>

      </div>
      <div :class="{'text-success':passwordStrength>=50, 'text-warning':passwordStrength<50}" class="m-y-2">
        <ui-progress :value="passwordStrength" :color="passwordStrength<50?'warning-filled':'success-filled'"></ui-progress>
        Password strength: {{passwordStrength}}% (min 50%)
      </div>
      <ui-alert color="warn-filled" class="m-b-5">
        <strong>NOTE:</strong> You will not be able to log in with your new password prior to visiting a confirmation link you will receive by email!
      </ui-alert>
      <n-verification style="min-height: 120px" v-model:code="code"/>
      <div class="grid">
        <div class="place-x-end">
          <ui-button v-if="formIsValid" color="primary-filled" class="font-md" type="submit" >Reset Password</ui-button>
        </div>
      </div>
    </form>
    <form @submit.prevent="manualVerify" v-if="step===1">
      <p class="font-md text-accent">Step 2/2</p>
      <p>If the entered data was recognized, we sent you an email with a verification-link. Make sure to check your spam-folder if you can't find the email within a few minutes.</p>
      <div class="m-y-2">
        <ui-input label="Confirmation code" class="font-md" v-model:value="confirm_code" />
      </div>
      <div class="grid">
        <div class="place-x-end">
          <ui-button color="primary-filled" class="font-md" type="submit" >Activate new Password</ui-button>
        </div>
      </div>
    </form>
    <ui-alert color="warning" v-if="step===1&&finalError!==''">
      {{finalError}}
    </ui-alert>
    <ui-alert color="success-filled" v-if="step===2">
      Your password was successfully reset! Please feel free to log in. <br>
      <router-link to="/" class="text-decoration-none text-white font-md hover:raise-1-black p-1 m-1">- Return home -</router-link>
    </ui-alert>
    <h3 class="m-t-5">Did you know?</h3>
    <p>The annual cost of lost passwords is measured on two sides:
      the cost of users/workers losing time to reset passwords and not being able to be productive as well as the cost covered by services to send out verification attempts.
      <br>
      From our perspective, this means that users loosing their passwords on a regular basis are expensive.
      From your perspective, it could mean that you spend hours a year resetting passwords. <br>
      Are we scolding you? No: Resetting your password will not be restricted nor trigger any other measures,
      but being aware of these facts might help us to keep this service free for a wider tier of users.
    </p>
  </div>
</template>

<script>
import uiInput from "/vue/ui/lib/ui.input";
import uiButton from "/vue/ui/lib/ui.button";
import uiIcon from "/vue/ui/lib/ui.icon";
import uiProgress from "/vue/ui/lib/ui.progress";
import uiAlert from "/vue/ui/lib/ui.alert";
export default {
  components:{
    uiInput,
    uiButton,
    uiIcon,
    uiProgress,
    uiAlert
  },
  computed:{
    passwordStrength(){
      let strength = Math.floor(this.password.length / 15 * 100);
      if(this.password.match(/\d/g)){
        strength += 8;
      } else {
        strength -= (5*this.password.length);
      }
      if(this.password.match(/[^A-Za-z0-9]/g)){
        strength += 4;
      }
      if(this.password.match(/^[0-9]*$/g)){
        strength -= (5*this.password.length);
      }
      return strength>100 ? 100 : strength;
    },
    formIsValid(){

      return this.code&&this.code.includes(')')&&this.email.length>0&&(this.user_name.length>0||this.slug.length>0)&&this.passwordStrength>50
    }
  },
  data:()=>({
    user_name: '',
    slug: '',
    code:'',
    password:'',
    showHelp:false,
    showPassword:false,
    mode:'user_name',
    confirm_code:''
  }),
  setup(){
    const API = Vue.inject('API');
    const route = VueRouter.useRoute()
    const step = Vue.ref(0);
    const email = Vue.ref('');
    const finalError = Vue.ref('')
    if(route.query.email){
      email.value = route.query.email
    }

    if(route.params.confirm_code){
      confirming();
    }

    Vue.watch(()=>route.params, ()=>{
      if(route.params.confirm_code){
        confirming();
      }
    })
    function confirming(){
      step.value = 1;
      API.put('/password-reset',{email:email.value,confirm_code:route.params.confirm_code.toString()}).then(res => {
        if(!res.data.error){
          step.value=2;
        } else {
          finalError.value = 'This link might have expired or was never valid.'
        }
      })
    }
    return {API,step,email,finalError}
  },
  methods:{
    manualVerify(){
      this.$router.push('/password-reset/'+this.confirm_code +'?email='+this.email)
    },
    reset(){
      this.step++;
      this.API.post('/password-reset',this).then(res => {
        if(res.data.error && res.data.limit){
          alert('While your request seems legitimate, we seem to have hit a snag. Please try again later.')
        }
      })
    }
  },
  template: `{{template}}`,
}
</script>