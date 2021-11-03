<template>
<div class="container m-y-5">
  <h1>Login with blua.blue</h1>
  <ui-alert color="warning-filled" v-if="!from">
    WARNING! The public key did not match our records. System can't proceed.
  </ui-alert>
  <div v-else>
    <p>Requester: {{from.user_name}}</p>
    <p>Scope: <ui-tag v-for="permission in scope">{{permission}}</ui-tag></p>
    <div v-if="!user">
      <n-login></n-login>
    </div>
    <div n-if="false" v-else>
      <ui-alert color="warn" v-if="$route.query.endpoint">
        Developers are working hard to secure this login. However, we are not done yet. Please be patient. <br>
        <strong>{{$route.query.endpoint}}</strong> <br>
        Only proceed if you trust this domain. <br>
        <ui-button class="font-md" color="primary-filled" @click="process">proceed</ui-button>
      </ui-alert>
      <ui-alert color="warning-filled" v-else>
        Missing <strong>endpoint</strong> parameter! <br>
        It is not recommended to proceed: your token will be directly attached as get-parameter.
        If you do not own <strong>{{$route.query.redirect}}</strong> and are in active development state,
        be <span class="text-uppercase text-warn">really</span> sure what you are doing. <br>
        <ui-button class="font-md" color="primary-filled" @click="process">proceed</ui-button>
      </ui-alert>
    </div>
  </div>

  </div>

</div>
</template>

<script>
import uiAlert from '/vue/ui/lib/ui.alert';
import uiButton from '/vue/ui/lib/ui.button';
import uiTag from '/vue/ui/lib/ui.tag';
export default {
  components:{uiAlert,uiButton,uiTag},
  inject:['API'],
  setup(){
    const from = JSON.parse('{{from}}')
    const scope = '{{scope}}'.split(',')
    const user = Vue.ref(null);
    const store = Vue.inject('neoanStore')
    store.subscribe('auth', obs => {
      if(obs && obs.length>0){
        user.value = obs[0]
      } else {
        user.value = null
      }
    })
    return {user,from,scope}
  },
  methods: {
    process(){
      this.API.get('/auth/'+this.scope.join(',')).then(res=> {
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