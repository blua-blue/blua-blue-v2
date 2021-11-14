<template>
  <section class="m-t-5">
    <div class="grid md:grid-2-3-3-4 bg-primary p-3 footer text-white ">
      <div>&copy; 2021 blua.blue</div>
      <div>
        <strong>For developers</strong> <br>
        <a href="{{base}}api" class="text-decoration-none text-white hover:text-white-75 m-r-3">API</a>
        <router-link to="/sdk" class="text-decoration-none text-white hover:text-white-75 m-r-3">SDKs</router-link>
        <a target="_blank" rel="noopener noreferrer" href="https://github.com/blua-blue/blua-blue-v2" class="text-decoration-none text-white hover:text-white-75 m-r-3">Opensource</a>
        <br><router-link to="/dev-to" class="text-decoration-none text-white hover:text-white-75 m-r-3">Dev.to Plugin</router-link>
        <router-link to="/medium" class="text-decoration-none text-white hover:text-white-75 m-r-3">Medium.com Plugin</router-link>
      </div>
      <div>
        <strong>Writing on blua.blue</strong> <br>
        <router-link to="/write-interface" class="text-decoration-none text-white hover:text-white-75 m-r-3">The write-interface</router-link>
      </div>
      <div>
        <strong>About blua.blue</strong>
        <div class="grid-6-6 ">
          <a @click.prevent="modal('terms')" href="{{base}}terms-conditions" class="text-decoration-none text-white hover:text-white-75 m-r-3">Terms & Conditions</a>
          <a @click.prevent="modal('privacy')" href="{{base}}privacy" class="text-decoration-none text-white hover:text-white-75 m-r-3">Privacy</a>
          <a @click.prevent="modal('ip')" href="{{base}}ip" class="text-decoration-none text-white hover:text-white-75 m-r-3">Intellectual Property Claims</a>
          <a href="{{base}}contact-us" @click.prevent="$router.push('/contact-us')" class="text-decoration-none text-white hover:text-white-75">Contact us</a>
        </div>
      </div>


    </div>
    <uiModal n-if="false" title="blua.blue Information" :show="modalOpen" @close="modalOpen=false">
      <div class="p-3">
        <nTermsConditions v-if="show.terms"/>
        <n-privacy v-if="show.privacy"/>
        <n-ip v-if="show.ip"/>
        <p v-if="show.notYet">Sorry! We are currently updating this section</p>
      </div>
    </uiModal>
  </section>

</template>


<script>
import uiModal from '/vue/ui/ui.modal';
export default {
  components:{uiModal},
  data:()=>({
    modalOpen: false,
    show:{
      terms: false,
      privacy:false,
      ip:false,
      notYet:false
    }
  }),
  methods:{
    modal(which){
      Object.keys(this.show).forEach((v,i)=>{
        this.show[v] = false;
      })
      this.show[which] = true;
      this.modalOpen = true;
    }
  },
  template: `{{template}}`,
}
</script>