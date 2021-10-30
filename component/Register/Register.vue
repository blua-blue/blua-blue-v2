<template>
  <div class="container">
    <ui-modal n-if="false" title="Problem" :show="showError" @close="showError=!showError">
      <p class="p-3">{{errorMsg}}</p>
    </ui-modal>
    <div class="grid md:grid-4-8">
      <div class="b-r-1 b-primary p-r-3 m-r-3 bg-primary text-white p-2 raise-1-accent" style="border-radius: 0 0 0 30px">
        <p>Let's humanize the rules:</p>
        <ol>
          <li>Free to join!</li>
          <li>You are responsible for what you write/post</li>
          <li>We are not liable for - well - basically anything</li>
          <li>We do not share ANY user-credentials & store them securely</li>
          <li>You need to be an adult (18) for premium services and a responsible teen (13) to join.</li>
          <li>You cannot plagiarize</li>
          <li>You cannot incite physical violence</li>
          <li>We are legally bound by US law but comply with GDPR</li>
        </ol>
      </div>
      <form @submit.prevent="register" n-if="false" v-if="step===0">
        <h1>Register</h1>
        <div class="m-y-2">
          <ui-input v-model:value="user.user_name"  label="Username" required />
        </div>
        <div class="m-y-2">
          <ui-input v-model:value="user.email" type="email" label="Email" required />
        </div>
        <div class="m-y-2">
          <ui-input  v-model:value="user.password" type="password" label="Password" required />
        </div>
        <div :class="{'text-success':passwordStrength>=50, 'text-warning':passwordStrength<50}" >
          <ui-progress :value="passwordStrength" :color="passwordStrength<50?'warning-filled':'success-filled'"></ui-progress>
          Password strength: {{passwordStrength}}% (min 50%)
        </div>
        <div class="m-y-2">
          <ui-input v-model:value="user.passwordRepeat" type="password" label="Repeat password" required />
        </div>
        <p class="text-warning font-md" v-if="user.passwordRepeat.length>0 && user.passwordRepeat !== user.password">
          Passwords do not match
        </p>
        <div class="m-y-2">
          <ui-input type="checkbox" v-model:value="user.toc">
            <template #label>
              I have read the <router-link to="/terms-conditions">terms and conditions</router-link>
            </template>
          </ui-input>
        </div>
        <n-verification v-model:code="user.code"/>
        <div class="grid m-t-5">
          <div class="place-x-end">
            <ui-button :disabled="!user.code||blocked" type="submit" class="font-md">Register</ui-button>
          </div>
        </div>
      </form>
      <form @submit.prevent="verify" v-if="step===1">
        <h1>You are almost there!</h1>
        <p>We sent you an email with a verification code. Please enter it here:</p>
        <div class="d-flex">
          <ui-input class="f-1" type="number" label="Verification code" v-model:value="verificationCode"/>
          <div class="grid">
            <ui-button :disabled="verificationCode.length<3" type="submit" class="font-md place-y-end m-l-3">Verify</ui-button>
          </div>

        </div>

        <p class="m-t-4">
          No email? It can take a couple of minutes in some cases. Also, make sure you look in your spam folder.
          Just to be on the save side: your email is <strong>{{user.email}}</strong>?
        </p>
      </form>
      <div n-if="false" v-if="step===2">
        <h1>You are good to go - let's log in</h1>
        <n-login/>
      </div>
    </div>

  </div>
</template>

<script>
import uiInput from "/vue/ui/lib/ui.input";
import uiButton from "/vue/ui/lib/ui.button";
import uiIcon from "/vue/ui/lib/ui.icon";
import uiModal from "/vue/ui/lib/ui.modal";
import uiProgress from "/vue/ui/lib/ui.progress";
export default {
  components:{
    uiInput,
    uiButton,
    uiIcon,
    uiProgress,
    uiModal
  },
  computed:{
    passwordStrength(){
      let strength = Math.floor(this.user.password.length / 15 * 100);
      if(this.user.password.match(/\d/g)){
        strength += 8;
      } else {
        strength -= (5*this.user.password.length);
      }
      if(this.user.password.match(/[^A-Za-z0-9]/g)){
        strength += 4;
      }
      if(this.user.password.match(/^[0-9]*$/g)){
        strength -= (5*this.user.password.length);
      }
      return strength>100 ? 100 : strength;
    }
  },
  data:()=>({
    step:0,
    showError:false,
    errorMsg:'',
    blocked:false,
    user:{
      user_name:'',
      password:'',
      passwordRepeat:'',
      email:'',
      toc:'',
      code:''
    },
    verificationCode:''
  }),
  mounted(){
    if(this.globalEmail){
      this.step=1;
      this.user.email = this.globalEmail;
    }
  },
  setup(){
    const route = VueRouter.useRoute();
    const globalEmail = Vue.ref(route.query.email||'');
    return {globalEmail}
  },
  methods:{
    register(){
      this.blocked = true;
      API.post('/register',this.user).then(res => {
        this.blocked = false;
        if(res.data.error){
          this.errorMsg = res.data.error;
          this.showError = true;
        } else {
          this.step++;
        }
      })
    },
    verify(){
      this.blocked = true;
      API.put('/register', {confirm_code:this.verificationCode,email:this.user.email}).then(res => {
        if(res.data.verified){
          this.step++;
        } else {
          this.errorMsg = 'Something did not work. Wrong code?';
          this.showError = true;
        }
      })
    }

  },
  template: `{{template}}`,
}
</script>