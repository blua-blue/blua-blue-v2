<template>
  <div class="container">
    <h1>Dev-to plugin</h1>
    <p>This plugin works as a proxy to facilitate blua.blue <-> dev.to communication.</p>
    <div v-if="process.step===-1">
      You need to be logged in...
    </div>
    <div v-if="process.step===0">
      <h2>1. Setup</h2>
      <div class="grid-7-5">
        <form @submit.prevent="testLinking">
          <div class="m-y-2">
            <ui-input required label="Dev.to API key" v-model:value="form.apiKey"/>
          </div>
          <div class="grid m-y-2">
            <ui-button type="submit">test</ui-button>
          </div>
          <div class="m-y-2 p-3 bg-warning text-white b-rounded" v-if="process.error" n-if="false">
            This API key does not seem to work. Please check
          </div>
        </form>
        <div class="m-l-3">
          <p>You can find or generate your api key for dev.to at https://dev.to/settings/account</p>
        </div>
      </div>
    </div>
    <div v-if="process.step>0">
      <h2>2. blua.blue-Webhook</h2>
      <div class="grid-7-5">
        <section>
          <div class="m-y-2">
            <ui-input required label="Reference name (can be anything you want)" value="dev.to"/>
          </div>
          <div class="m-y-2">
            <ui-input required label="Endpoint" value="Plugin::DevTo"/>
          </div>
          <div class="m-y-2">
            <ui-input required label="Token" :value="process.token"/>
          </div>
        </section>
        <div class="m-l-3">
          Lastly, set up a webhook at <a :href="profileLink" target="_blank"
                                         rel="noopener noreferrer">{{ profileLink }}</a> with these values.
          <p>
            <ui-button @click="createWebhook">do it for me</ui-button>
          </p>
        </div>
      </div>

    </div>


  </div>
</template>

<script>
import uiInput from '/vue/ui/lib/ui.input'
import uiButton from '/vue/ui/lib/ui.button'

export default {
  components: {uiInput, uiButton},
  setup() {
    const API = Vue.inject('API')
    const profileLink = Vue.ref(null)
    const store = Vue.inject('neoanStore')
    const form = Vue.ref({
      apiKey: '',
    })
    const process = Vue.ref({
      step: -1,
      error: false,
      token: ''
    })
    store.subscribe('auth', auth => {
      if (auth.length > 0) {
        profileLink.value = '{{base}}profile/' + auth[0].user.user_name + '#webhooks';
        process.value.step = 0
      }
    })

    const testLinking = () => {
      API.get(`/dev-to/${form.value.apiKey}`).then(res => {
        process.value.error = !!res.data.test.error;
        if (!res.data.test.error) {
          process.value.step = 1;
          process.value.token = res.data.token
        }
      })
    }
    const createWebhook = () => {
      API.post('/webhook', {
        token: process.value.token,
        name: process.value.name,
        target_url: 'Plugin::Medium'
      }).then(() => {
        router.push('/' + profileLink.value.substr('{{base}}'.length))
      })
    }
    return {form, testLinking, process, profileLink, createWebhook}
  },
  template: `{{template}}`,
}
</script>