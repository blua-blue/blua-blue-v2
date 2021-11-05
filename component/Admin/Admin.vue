<template>
  <div class="container" n-if="false">
    <ui-tabs class="m-b-3" :tabs="entities" v-model:selected="activeTab"></ui-tabs>
    <ui-input type="switch" v-model:value="showDeleted" label="Show deleted & banned"></ui-input>
    <section v-if="activeTab===0">
      <div>
        <ui-button :color="sortOrder==='insert_date_st'?'primary-filled':'primary'" @click="sortOrder='insert_date_st'">sort by date</ui-button>
        <ui-button :color="sortOrder==='user_name'?'primary-filled':'primary'" @click="sortOrder='user_name'">sort by name</ui-button>
      </div>
      <div class="grid-4-4-4 p-y-3 p-x-2 b-1 b-rounded b-gray-75 m-y-1" v-for="entry in filteredEntries"
           :class="{'bg-warn-50':entry.delete_date}"
           v-show="!entry.delete_date||showDeleted">
        <router-link :to="'/profile/'+entry.user_name">{{ entry.user_name }} <br> {{entry.insert_date}}</router-link>
        <div>{{ entry.user_email[0].email }} <br> {{entry.user_email[0].confirm_date??'unconfirmed'}}</div>
        <div class="place-x-end">
          <ui-button @click="activationToggle('/auth',entry)" color="warn-filled">
            <span v-if="entry.delete_date">Reactivate</span>
            <span v-else>Ban user</span>
          </ui-button>
          <ui-button @click="deleteUser(entry)" class="m-l-2" color="warning-filled">delete user</ui-button>
        </div>
      </div>

    </section>
    <section v-if="activeTab===1">
      <form class="d-flex" @submit.prevent="addCategory">
        <ui-input v-model:value="categoryName" placeholder="add category" type="text" class="f-1"/>
        <div class="place-y-center">
          <ui-button type="submit">+</ui-button>
        </div>
      </form>
      <div class="grid-6-6 p-y-3 p-x-2 b-1 b-rounded b-gray-75 m-y-1"
           v-show="!entry.delete_date||showDeleted"
           :class="{'bg-warning-50':entry.delete_date}" v-for="entry in entries">
        <div>{{ entry.name }}</div>
        <div class="place-x-end">
          <ui-button @click="activationToggle('/category',entry)" color="warning">
            <span v-if="entry.delete_date">Reactivate</span>
            <span v-else>Delete</span>
          </ui-button>
        </div>
      </div>
    </section>
    <section v-if="activeTab===2">

      <div class="grid-4-4-4 p-y-3 p-x-2 b-1 b-rounded b-gray-75 m-y-1"
           v-show="!entry.delete_date||showDeleted"
           :class="{'bg-warning-50':entry.delete_date}" v-for="entry in entries">
        <div>{{ entry.subject }}: {{entry.sent_from}}</div>
        <div v-html="entry.content">{{entry.content}}</div>
        <div class="place-x-end">
          <ui-button @click="deleteMessage(entry)" color="warning">
            <span v-if="entry.delete_date">Reactivate</span>
            <span v-else>Delete</span>
          </ui-button>
        </div>
      </div>
    </section>
    <button v-if="entries.length === (page+1) * 30" @click="page++;getEntity()">load more</button>
  </div>
</template>

<script>
import uiTabs from '/vue/ui/lib/ui.tabs';
import uiButton from '/vue/ui/lib/ui.button';
import uiInput from '/vue/ui/lib/ui.input';

export default {
  components: {uiTabs, uiButton, uiInput},
  data: () => ({
    activeTab: 1,
    entries: [],
    entities: ['user', 'category','message'],
    page: 0,
    categoryName: '',
    showDeleted: true,
    sortOrder: 'user_name'
  }),
  computed:{
    filteredEntries(){
      return this.entries.sort((a,b)=>{
        if(this.sortOrder === 'insert_date_st'){
          return b[this.sortOrder]-a[this.sortOrder]
        } else {
          return b[this.sortOrder]>a[this.sortOrder] ? -1 : 1
        }

      })
    }
  },
  watch: {
    activeTab() {
      this.page = 0;
      this.entries = [];
      this.getEntity()
    }
  },
  setup(){
    const store = Vue.inject('neoanStore')
    const router = VueRouter.useRouter()
    store.subscribe('auth', authArray => {
      if(authArray.length<1 || authArray[0].user.user_type !== 'admin'){
        router.push('/')
      }
    })
  },
  mounted() {
    this.getEntity()
  },
  methods: {
    getEntity() {
      API.get('/admin/' + this.entities[this.activeTab] + '?limit[0]=' + (this.page * 30) + '&limit[1]=30').then(res => {
        if(this.page === 0){
          this.entries = res.data;
        } else {
          this.entries = [...this.entries,...res.data]
        }

      })
    },
    addCategory() {
      API.post('/category', {name: this.categoryName}).then(res => {
        this.entries = [res.data, ...this.entries];
      })
    },
    activationToggle(endpoint, entity) {
      const deletion = !entity.delete_date
      entity.delete_date = deletion ? '.' : null;
      API.put(endpoint, entity);
      this.entries = this.entries.map(x => {
        if (x.id === entity.id) {
          x.delete_date = deletion ? '.' : null;
        }
        return x;
      })
    },
    deleteMessage(message){
      API.delete('/admin/message?id='+message.id).then(()=>{
        this.getEntity();
      })
    },
    deleteUser(user){
      const warning = 'The deletion of a user is very extreme process involving file & database cleanup.' +
          ' Deleting a user will delete all traces.'
      const c = confirm(warning);
      if(c){
        API.delete('/admin/user?id='+user.id).then(()=>{
          this.getEntity();
        })
      }
    }

  },
  template: `{{template}}`,
}
</script>