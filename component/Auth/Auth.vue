<template>
<div class="container m-y-5">
  <div v-if="!user">
    <n-login></n-login>
  </div>
  <div n-if="false" v-else>
<!--    endpoint-->
    <ui-alert color="warn" v-if="$route.query.endpoint">
      You are in the process of sending your authentication-token to <br>
      <strong>{{$route.query.endpoint}}</strong> <br>
      Only proceed if you trust this domain.
    </ui-alert>
    <ui-alert color="warning-filled" v-else>
      Missing <strong>endpoint</strong> parameter! <br>
      You are currently logged in with full permissions on your authorization-token. It is not recommended
      to proceed: your token will be directly attached as get-parameter.
      If you do not own <strong>{{$route.query.redirect}}</strong> and are in active development state,
      be <span class="text-uppercase text-warn">really</span> sure what you are doing. <br>
      <ui-button class="font-md" color="primary-filled" @click="process">proceed</ui-button>
    </ui-alert>
  </div>

</div>
</template>

<script>
import uiAlert from '/vue/ui/lib/ui.alert';
import uiButton from '/vue/ui/lib/ui.button';
export default {
  components:{uiAlert,uiButton},
  inject:['API'],
  setup(){
    const user = Vue.ref(null);
    const store = Vue.inject('neoanStore')
    store.subscribe('auth', obs => {
      if(obs && obs.length>0){
        user.value = obs[0]
      } else {
        user.value = null
      }
    })
    return {user}
  },
  methods: {
    process(){
      this.API.get('/auth/all').then(res=> {
        const operator = this.$route.query.redirect.includes('?') ? '&' : '?';
        window.location.href = this.$route.query.redirect + operator+ 'token=' + res.data[0].token
      })

    }
  },
  template:`{{template}}`
}
</script>

<style scoped>

</style>