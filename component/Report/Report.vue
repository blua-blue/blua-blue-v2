<template>
  <div class="grid" n-if="false">
    <div class="grid-6-6">
      <div>
        <div v-if="existing && existing.length>0" class="text-warning">
          <ui-icon>warning</ui-icon>
          There are {{existing.length}} open reports on this content
        </div>

      </div>
      <ui-button @click="reporting=!reporting" class="place-x-end" color="warning"><ui-icon>flag</ui-icon> report</ui-button>
    </div>
    <form @submit.prevent="fileClaim" v-if="reporting&&!reported" class="p-3 b-1 b-warning b-rounded m-y-3">
      <h3 class="text-warning">Reporting improper or illegal article</h3>
      <p class="text-warning">SLUG: {{slug}}</p>
      <div class="d-flex">
        <ui-input class="f-1" v-model:value="reportData.report_type" label="Type" placeholder="select one" required type="select" :options="options" option-value="title"/>
        <div class="place-y-end m-2 place-x-end" v-if="reportData.report_type==='DMCA / copyright'" @click="">
          <ui-button @click="showIp=true"><ui-icon>info</ui-icon></ui-button>
        </div>
      </div>

      <ui-input required v-model:value="reportData.reporter_email" type="email" label="Your email"/>
      <ui-input v-model:value="reportData.claim" label="Claim (please be specific)" type="textarea" placeholder="Describe the reason for this report" required/>
      <ui-input v-model:value="reportData.accept" required type="checkbox" label="I understand that reporting is meant for legal or contractual violations and isn't suitable for matters of distaste, quality or opinion"/>
      <n-verification v-model:code="reportData.code"/>
      <div class="grid">
        <ui-button type="submit" class="place-x-end" color="accent-filled">Submit</ui-button>
      </div>

    </form>
    <ui-alert v-if="reported">
      <div class="p-3">We received your complaint and will evaluate it.</div>
    </ui-alert>
    <ui-modal :show="showIp" @close="showIp=!showIp">
      <div class="p-3">
        <Ip/>
      </div>
    </ui-modal>
  </div>
</template>

<script>
import uiButton from '/vue/ui/lib/ui.button';
import uiIcon from '/vue/ui/lib/ui.icon';
import uiInput from '/vue/ui/lib/ui.input';
import uiAlert from '/vue/ui/lib/ui.alert';
import uiModal from '/vue/ui/lib/ui.modal';
import Ip from "/vue/ip";
export default {
  components:{uiButton,uiIcon,uiInput,uiAlert,uiModal,Ip},
  props:{
    slug: String
  },
  data:()=>({
    showIp:false,
    existing:[],
    reporting:false,
    reported:false,
    options:[{title:'DMCA / copyright'},{title:'Violates Terms'},{title:'Illegal on a national level (USA)'},{title:'Other'}],
    reportData:{code:'',accept:false},

  }),
  inject:['API'],

  methods:{
    async fileClaim(){
      try{
        this.reportData.slug = this.slug;
        await this.API.post('/report', this.reportData);
        this.reported = true;
        this.existing.push(this.reportData)
      } catch (e) {
        alert('Something went wrong. You might be offline or hit our bot filter. Please reload the page and try again.')
      }

    }
  },
  watch:{
    async slug(){
      if(this.slug){
        const {data} = await this.API.get('/report/'+this.slug);
        this.existing = data;
      }
    }
  },

  template: `{{template}}`,
}
</script>