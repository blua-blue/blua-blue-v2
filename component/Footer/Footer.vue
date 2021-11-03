<template>
  <section class="m-t-5">
    <div class="grid-4-4-4 bg-primary p-3 footer text-white ">
      <div>&copy; 2021 blua.blue</div>
      <div>
        <strong>For developers</strong> <br>
        <a @click.prevent="modal('notYet')" href="{{base}}" class="text-decoration-none text-white hover:text-white-75 m-r-3">API</a>
        <a @click.prevent="modal('notYet')" href="{{base}}" class="text-decoration-none text-white hover:text-white-75 m-r-3">SDKs</a>
        <a @click.prevent="modal('notYet')" href="{{base}}" class="text-decoration-none text-white hover:text-white-75 m-r-3">Opensource</a>
      </div>
      <div class="grid-6-6 place-x-end">
        <a @click.prevent="modal('terms')" href="{{base}}terms-conditions" class="text-decoration-none text-white hover:text-white-75 m-r-3">Terms & Conditions</a>
        <a @click.prevent="modal('privacy')" href="{{base}}privacy" class="text-decoration-none text-white hover:text-white-75 m-r-3">Privacy</a>
        <a @click.prevent="modal('ip')" href="{{base}}ip" class="text-decoration-none text-white hover:text-white-75 m-r-3">Intellectual Property Claims</a>
        <a href="{{base}}contact-us" class="text-decoration-none text-white hover:text-white-75">Contact us</a>
      </div>

    </div>
    <uiModal n-if="false" title="Contractual content" :show="modalOpen" @close="modalOpen=false">
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