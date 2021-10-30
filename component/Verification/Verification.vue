<template>
  <section n-if="false">
    <div class="grid lg:grid-4-4-4">
      <div>
        <label>
          Verification step 1/2
          <input @focus="pollute" @change="$emit('update:code', $event.target.value)" class="w-50px text-transparent b-transparent bg-transparent"
                 ref="verify" type="text" name="website">
        </label>
      </div>
      <div class="grid">
        <ui-button @mouseenter="prefill" class="place-y-start" @click="checkHuman">
          <ui-icon class="font-md">ads_click</ui-icon>
          <br>Click exactly {{clickAmount}} time(s) if you are human
        </ui-button>
      </div>

      <div class="place-x-center place-y-center p-l-1">
        <ui-icon v-if="userClick===this.clickAmount" class="font-lg text-success">check</ui-icon>
        <ui-icon v-else class="font-lg text-warning">block</ui-icon>
        <ui-input @update:value="tryReset" label="Please type 'reset'" v-if="userClick>this.clickAmount"/>
        <div v-if="userClick>this.clickAmount" class="font-sm">Ups! Please reset and try again</div>
      </div>
    </div>
    <div class="grid lg:grid-4-4-4" v-show="userClick===this.clickAmount">
      <div>Verification step 2/2</div>
      <div class="position-relative">

        <div class="text-center m-t-3">Click on the indicated color</div>
        <div class="grid-4-4-4 cursor-pointer">
          <div class="b-l-1 b-t-1 b-b-1 h-25px" ref="one" @click="step2"></div>
          <div class="b-t-1 b-b-1 h-25px" ref="two" @click="step2"></div>
          <div class="b-r-1 b-t-1 b-b-1 h-25px" ref="three" @click="step2"></div>
        </div>
        <div class="position-relative">
          <div class="position-absolute move animate-position" ref="indicator" style="left: 0; top: -5px">
            <ui-icon class="font-xl text-primary">keyboard_double_arrow_up</ui-icon>
          </div>
        </div>

      </div>
      <div class="place-x-center place-y-center text-center">
        <ui-icon v-if="userClick===this.clickAmount&&selectedColor" class="font-lg text-success">check</ui-icon>
        <ui-icon v-else class="font-lg text-warning">block</ui-icon>
        <div class="font-sm">This interface can only guess if you are correct</div>
      </div>
    </div>


  </section>

</template>

<script>
import uiButton from '/vue/ui/lib/ui.button';
import uiIcon from '/vue/ui/lib/ui.icon';
import uiInput from '/vue/ui/lib/ui.input';

export default {
  components: {uiButton, uiIcon, uiInput},
  data: () => ({
    userClick: 0,
    verification: JSON.parse('{{verification}}'),
    colors: [],
    selectedColor: null,
    partLength:Math.floor(Math.random() * 11),
    clickAmount: Math.ceil(Math.random() * 3),
    pageTimeOut:null
  }),
  mounted() {
    const t = new Date();
    this.pageTimeOut = t.getHours();
  },
  methods: {
    generateStep2(){
      this.colors = [
        {name: 'red', shade:this.randomShade('r')},
        {name: 'blue', shade:this.randomShade('b')},
        {name: 'green', shade:this.randomShade('g')}
      ]
      this.colors = this.colors.sort(() => Math.random() - .5)
      const indicator = ((this.colors.findIndex(x => x.name === this.verification.color)) * 33) + Math.ceil(Math.random() * 32)
      this.$refs.indicator.style.left = '50%';
      setTimeout(()=>{
        this.$refs.indicator.style.left = 'calc('+indicator + '% - 14px)';
      },Math.random()*1000)
      this.$refs.one.style.backgroundColor = this.colors[0].shade
      this.$refs.two.style.backgroundColor = this.colors[1].shade
      this.$refs.three.style.backgroundColor = this.colors[2].shade
    },
    step2(ev){
      this.selectedColor = ev.target.style.backgroundColor;
      this.$emit('update:code', this.$refs.verify.value+'-'+this.selectedColor)
    },
    randomShade(channel){
      const values = [Math.floor(Math.random() * 20),Math.floor(Math.random() * 20),Math.floor(Math.random() * 20)];
      const channels = ['r','g','b'];
      values[channels.findIndex(x => x === channel)] = (Math.floor(Math.random() * 230)+25);
      return "rgb(" + values.join(',') + ")"
    },
    tryReset(val) {
      if (val === 'reset') {
        this.userClick = 0;
        this.$refs.verify.value = '    ';
        this.selectedColor = null;
      }
    },
    prefill(){
      if(this.$refs.verify.value.trim() === ''){
        this.$refs.verify.value = this.verification.code.substr(0,this.partLength)
      }
    },
    pollute(){
      this.$refs.verify.value = Math.random().toString().substr(2, 12);
    },
    checkHuman() {
      this.userClick++;
      const now = new Date();
      if(now.getHours() !== this.pageTimeOut){
        alert('Verification timed out, the page will be reloaded');
        window.location.reload();
      }
      if (this.userClick < this.clickAmount && this.$refs.verify.value.trim().length !== this.partLength) {
        this.userClick = 0;
        return;
      }
      if (this.userClick === this.clickAmount) {
        this.$refs.verify.value += this.verification.code.substr(this.partLength);
        this.generateStep2();
      } else if(this.userClick > this.clickAmount) {
        this.$refs.verify.value = Math.random().toString().substr(2, 12);
      }
      this.$emit('update:code', this.$refs.verify.value+'-'+this.selectedColor)
    }
  },
  template: `{{template}}`,
}
</script>