<template>
  <div class="container">
    <h3>Webhooks</h3>
    <div class="grid-3-9">
      <div class="p-r-2 m-r-2 b-r-1 b-primary">
        <p>Webhooks are triggered on the entity "article" with the following events:</p>
        <ul>
          <li>created</li>
          <li>updated</li>
          <li>deleted</li>
        </ul>
      </div>
      <div class="p-2">


        <h3>Registered webhooks</h3>
        <div n-if="false" v-for="(webhook,i) in webhooks" class="grid md:grid-2-4-4-2 m-y-2 b-rounded b-1 b-primary p-2">
          <div class="place-y-end m-b-2 font-strong">{{webhook.name}}</div>
          <div class="m-x-1"><ui-input disabled label="Endpoint" :value="webhook.target_url"></ui-input></div>
          <div class="m-x-1"><ui-input disabled label="Token" :value="webhook.token"></ui-input></div>
          <div class="place-x-end place-y-end m-b-2">
            <ui-button color="warning-filled">remove</ui-button>
          </div>
        </div>
        <ui-button @click="showNewWebhook=!showNewWebhook">add webhook</ui-button>
        <div class="m-y-3" v-if="showNewWebhook">
          <h3>New webhook</h3>
          <form @submit.prevent="addWebhook">
            <div class="m-y-2">
              <ui-input required v-model:value="newWebhook.name" label="Reference name"></ui-input>
            </div>
            <div class="m-y-2">
              <ui-input required v-model:value="newWebhook.target_url" label="Endpoint" type="url"></ui-input>
            </div>
            <div class="m-y-2">
              <ui-input v-model:value="newWebhook.token" label="Token/Apikey"></ui-input>
            </div>
            <div class="grid m-y-2">
              <ui-button color="primary-filled" class="place-x-end" type="submit">create</ui-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import uiInput from '/vue/ui/lib/ui.input';
import uiButton from '/vue/ui/lib/ui.button';
export default {
  components:{uiInput,uiButton},
  setup(){
    const API = Vue.inject('API')
    const showNewWebhook = Vue.ref(false)
    const webhooks = Vue.ref([])
    const newWebhook = Vue.ref({
      name:'',
      token:'',
      target_url:''
    })
    const addWebhook = () => {
      API.post('/webhook', newWebhook.value).then(res => {
        webhooks.value.push(res.data);
        showNewWebhook.value = false;
      })
    }
    API.get('/webhook').then(res => {
      webhooks.value = res.data;
    })
    return {webhooks, addWebhook, newWebhook, showNewWebhook}
  },
  template: `{{template}}`,
}
</script>