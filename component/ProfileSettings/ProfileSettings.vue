<template>
  <div class="grid-3-9 m-y-4">
    <div class="grid b-r-1 b-primary p-r-2 m-r-2 ">
      <div class="grid place-y-start">
        <ui-button @click="content=0" class="m-b-1">General</ui-button>
        <ui-button @click="content=1" class="m-b-1">Storage</ui-button>
        <ui-button @click="content=2" class="m-b-1">API</ui-button>
        <ui-button @click="content=3" color="warning">Danger zone</ui-button>
      </div>

    </div>
    <div>
      <div v-if="content===0">
        <p>Personal data</p>
        <h3>User</h3>
        <div class="grid-4-4-4">
          <p>{{ auth.user_name }}</p>
          <p>created <br> {{ auth.insert_date }}</p>
          <p>user type <br> {{ auth.user_type }}</p>
        </div>
        <h3>Emails</h3>
        <div class="grid-4-3-3-2" v-for="email in auth.user_email">
          <p>{{ email.email }}</p>
          <p>created <br>{{ email.insert_date }}</p>
          <p>confirmed <br>{{ email.confirm_date ? email.confirm_date : 'unconfirmed' }}</p>
          <p>status <br>{{ email.delete_date ? 'deleted' : 'active' }}*</p>
        </div>
        <form class="d-flex">
          <ui-input class="f-1" placeholder="Change email" v-model:value="newEmail"></ui-input>
          <div class="place-y-end m-2">
            <ui-button type="submit">change email</ui-button>
          </div>
        </form>

        <p class="font-sm">*unless requested by you, we keep formerly active emails in our records to prevent others
          from creating accounts with them.</p>
      </div>
      <div v-if="content===1">
        <h3>Image storage</h3>
        <ui-progress :value="imageStorage"></ui-progress>
        Used: {{ imageStorage }}% of {{ maxImageStorage }}MB | # of images: {{ storage.images.count }}
        <p>Tips to save storage</p>
        <ul>
          <li>Reduce file-sizes before uploading or</li>
          <li>Use external urls or</li>
          <li>Host blua.blue yourself</li>
        </ul>
      </div>
      <div v-if="content===2">
        <h3>API</h3>
        <ui-alert class="m-y-2" color="warn">Important! Copy your API-key after generation! We cannot display it again.</ui-alert>
        <div v-for="api in APIkeys">
          <h3 class="text-primary">Active API key: {{ api.name }}</h3>
          <div>
            Permission: {{ api.scope }}
          </div>
          <div class="grid-10-2">
            <div>
              <ui-input :type="showKeys?'text':'password'" label="Public Key" :value="api.id"/>
            </div>
            <ui-button color="warn" @click="showKeys=!showKeys" class="place-y-center place-x-end font-lg text-warn">
              <ui-icon v-if="!showKeys">visibility</ui-icon>
              <ui-icon v-if="showKeys">visibility_off</ui-icon>
            </ui-button>
          </div>
          <div  v-if="api.api_key">
            <div class="grid-10-2">
              <div>
                <ui-input :type="showKeys?'text':'password'" label="API Key" :value="api.api_key"/>
              </div>
              <ui-button color="warn" @click="showKeys=!showKeys" class="place-y-center place-x-end font-lg text-warn">
                <ui-icon v-if="!showKeys">visibility</ui-icon>
                <ui-icon v-if="showKeys">visibility_off</ui-icon>
              </ui-button>
            </div>

          </div>

          <div>
            Origin restrictions: {{ api.remote || 'no restrictions' }}
          </div>
          <div class="m-y-2">
            <ui-button @click="revokeApi(api.id)" color="warning-filled">revoke keys for "{{ api.name }}"</ui-button>
          </div>
        </div>
        <form v-if="APIkeys.length<1" class="p-3" @submit.prevent="generateApi">
          <p>Generate API key</p>
          <div class="m-y-2">
            <ui-input required label="Name" v-model:value="newApi.name"/>
          </div>
          <div class="m-y-2">
            <ui-input required type="select" :options="options" option-value="scope"
                      label="Permission" v-model:value="newApi.scope"/>
          </div>
          <div class="m-y-2">
            <ui-input type="url" label="Allowed origin" placeholder="optional" v-model:value="newApi.remote"/>
          </div>
          <div class="m-y-2 grid">
            <ui-button type="submit">generate</ui-button>
          </div>
        </form>
      </div>
      <div v-if="content===3">
        <h3>Danger zone</h3>
        <p>We have an automated <strong>"wipe myself off the ground of the earth"</strong> mechanism. This will destroy your account and all files, articles and interactions you ever made.</p>
        <p>It is not always possible to automate a "wipe" due to legal restrictions, open claims or ongoing investigations.</p>
        <div class="b-rounded b-2 b-warning p-3">
          <form action="">
            <div class="m-y-2">
              <ui-input required v-model:value="wipe.user_name" label="Your username"/>
            </div>
            <div class="m-y-2">
              <ui-input required type="password" v-model:value="wipe.password" label="Your password"/>
            </div>
            <div class="m-y-2">
              <ui-input required v-model:value="wipe.random" :label="'Type '+random"/>
            </div>
            <div class="m-y-2">
              <n-verification v-model:code="wipe.code"></n-verification>
            </div>

            <div class="m-y-2">
              <ui-alert color="warning-filled">
                <p>After this operation, there is nothing we can do to retrieve any lost data.</p>
                <ui-input required type="checkbox" v-model:value="wipe.accepted" label="I am aware of what this means"/>
              </ui-alert>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import uiButton from '/vue/ui/lib/ui.button'
import uiInput from '/vue/ui/lib/ui.input'
import uiIcon from '/vue/ui/lib/ui.icon'
import uiAlert from '/vue/ui/lib/ui.alert'
import uiProgress from '/vue/ui/lib/ui.progress'

export default {
  components: {uiButton, uiInput, uiProgress, uiIcon,uiAlert},
  props: {
    auth: Object
  },
  data: () => ({
    content: 0,
    newEmail: '',
    newApi: {},
    showKeys: false,
    random: Math.random().toString().substr(2, 5),
    options:[
      {scope:'all,read,read.profile,read.articles,read.articles.owned',title:'all - unrestricted read & write access'},
      {scope:'read,read.profile,read.articles,read.articles.owned',title:'read - unrestricted read access'},
      {scope:'read.profile,read.articles,read.articles.owned',title:'read.profile - read access to non-public articles and profile information'},
      {scope:'read.articles,read.articles.owned', title:'read.articles - read access to articles'},
      {scope:'read.articles.owned', title:'read.articles.owned - read access to articles owned by API key holder only'},
    ],
    wipe:{
      random: '',
      password:'',
      accepted:false,
      code:'',
      user_name:''
    }
  }),
  computed: {
    imageStorage() {
      const usedInKB = this.storage.images.storage / 1024;
      const max = this.maxImageStorage * 1024; // currently 10 MB in KB
      return Math.round(usedInKB / max * 100);
    }
  },
  setup({auth}) {
    const API = Vue.inject('API');
    const APIkeys = Vue.ref([])
    const storage = Vue.ref({})
    const maxImageStorage = Vue.ref(10)
    if (auth) {
      API.get('/profile-settings').then(res => {
        storage.value = res.data.storage
        APIkeys.value = res.data.api
      })
    }
    return {storage, maxImageStorage, APIkeys, API}
  },
  methods: {
    revokeApi(id) {
      const c = confirm('Are you sure?')
      if (c) {
        this.API.delete('/profile-settings/api/' + id).then(() => {
          this.APIkeys = this.APIkeys.filter(keys => keys.id !== id);
        })
      }
    },
    generateApi() {

      this.API.post('/profile-settings/api', this.newApi).then(res => {
        this.APIkeys.push(res.data)
      })
    }
  },
  mounted() {
    console.log('attached: profileSettings')
  },
  template: `{{template}}`,
}
</script>