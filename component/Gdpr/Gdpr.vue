<template>
  <div n-if="false">
    <teleport to="body">
      <ui-modal position="bottom" title="Compliance" :show="needPermission&&!exception" @close="warning">
        <form @submit.prevent="process" class="p-3">
          <p class="font-strong">
            We ask for your help in order to stay compliant with regulations while creating the least annoying and intruding experience for you.
          </p>
          <div class="grid-6-6">
            <ui-button class="m-x-1 font-md" color="primary-filled" @click="toc=true;allowCookies=true;allowAnalytics=true; process()">Allow all</ui-button>
            <div class="font-strong">or make selection:</div>
          </div>

          <ui-input type="switch" label="Allow analytics" v-model:value="allowAnalytics"></ui-input>
          <ui-input type="switch" label="Allow functional cookies*"  v-model:value="allowCookies"></ui-input>
          <ui-input type="checkbox"  v-model:value="toc">
            <template #label>
              I have read and agree to the <router-link to="/terms-conditions">terms and conditions</router-link>*
            </template>
          </ui-input>
          <p>*strictly necessary</p>
          <div class="grid-6-6">
            <ui-button class="m-x-1 font-md" color="warn-filled" @click="warning">Deny all</ui-button>
            <ui-button class="m-x-1 font-md" color="primary-filled" type="submit">Allow selection</ui-button>
          </div>
          <ui-alert class="m-t-3" color="warn-filled" v-if="warningAlert">
            Please allow the required settings.
          </ui-alert>
        </form>
        <div class="p-3">
          <h3 class="text-primary text-uppercase">To whom it may concern... - our statement <ui-icon class="cursor-pointer" @click="showStatement=!showStatement">unfold_more</ui-icon></h3>
          <div v-if="showStatement">
            <h4>Power to privacy</h4>
            <p>Currently implemented "cookie-laws" fail to address the trend of unrestricted and unsecured data-collection.
              Since cookies are a technical necessity to operate nearly any web presence,
              being concerned with how collected data is treated and secured both through the platform and 3rd party providers has little to do with what kind of cookies are set,
              but rather what kind of data can be derived from them and how such data is processed.
              We take great pride in focussing on the most minimal impact when it comes to data of our users and will continue to prioritize privacy over "marketing opportunity"
            </p>

            <h4>Power to free speech</h4>
            <p>Other than plagiarism and calling for violence,
              we pretty much allow everything within the legal framework of free speech.
              This means that this platform might not be suitable for minors
              or people trying to avoid being offended. However, we <strong>are inclusive</strong> to all non-violent walks of life.</p>
            <h4>Power to small business</h4>
            <p>This platform is supporting small businesses.
              This starts with our offering itself as well as our commitment to using 3rd party software and libraries outside the realm of "big tech".
              This also provides us with greater independence. This means:
            </p>
            <div class="grid-3-9">
              <p>Analytics</p>
              <div>
                <img style="width: 150px" src="https://piwik.pro/wp-content/assets/img/pp-logo_dark.svgz?ver=1632741113" alt="piwik.pro">
              </div>
              <p>Mailing</p>
              <div>
                <img style="width: 150px" src="https://marketplace.inescrm.com/wp-content/uploads/logo-product-mailjet-750x430.jpg" alt="mailjet">
              </div>

            </div>
          </div>

        </div>
      </ui-modal>
    </teleport>

  </div>
</template>

<script>
import uiModal from '/vue/ui/lib/ui.modal';
import uiAlert from '/vue/ui/lib/ui.alert';
import uiInput from '/vue/ui/lib/ui.input';
import uiIcon from '/vue/ui/lib/ui.icon';
import uiButton from '/vue/ui/lib/ui.button';
export default {
  components:{uiModal,uiInput,uiButton,uiAlert,uiIcon},
  data:()=>({
    needPermission:true,
    allowAnalytics:false,
    allowCookies:false,
    toc:false,
    warningAlert:false,
    showStatement:false
  }),
  mounted(){
    const items =  ['allowAnalytics','allowCookies','toc'];
    items.forEach(item => {
      if(localStorage.getItem(item)){
        this[item] = localStorage.getItem(item)
      }
    })
    this.check()
  },
  methods:{
    warning(){
      const c = confirm('I understand that not giving consent to basic functionality will require me to leave this site.');
    },
    check(){
      if(this.allowCookies && this.toc){
        this.needPermission = false;
      }
      if(this.allowAnalytics && !window.location.href.includes('http://localhost')){
        const script = document.createElement('script');
        script.src = '{{base}}asset/lib/piwik-container.js'
        document.body.insertBefore(script,document.body.firstChild)
      }
    },
    process(){
      const items =  ['allowAnalytics','allowCookies','toc'];
      items.forEach(item => {
        localStorage.setItem(item, this[item])
      })
      this.check()
      this.warningAlert = this.needPermission

    }
  },
  setup(){
    const router = VueRouter.useRoute();
    const exception = Vue.ref(router.path === '/terms-conditions' || router.path === '/privacy');
    Vue.watch(()=> router.path, () => {
      exception.value = router.path === '/terms-conditions'
    })
    return {exception}
  },
  template: `{{template}}`,
}
</script>