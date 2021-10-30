<template>
  <div>
    <h1>Form / Inputs</h1>
    <p>
      Well, I guess that explains itself.
    </p>
    <div class="grid ">
      <form>
        <div class="grid-6-6">
          <ui-input value="" required placeholder="First Name"/>
          <ui-input value="" required placeholder="Last Name"/>
        </div>
        <div>
          <ui-input type="email" required value="" placeholder="Email"/>
        </div>
        <p>
          <ui-input type="textarea" label="Bio"/>
        </p>
        <p>
          <ui-input type="select" label="Please select"
                    placeholder="make a choice" :options="[{id:1,title:'one'},{id:2, title:'two'}]">
          </ui-input>
        </p>
        <p>
          <ui-input  type="switch" v-model:value="switchToggle" label="Newsletter?"></ui-input>
        </p>

        <p>
          <ui-input required v-model:value="toggleAlt" type="checkbox" label="Accept terms and conditions"/>
        </p>
        <div class="grid m-t-2">
          <ui-button class="place-x-end" >
            <ui-icon>how_to_reg</ui-icon> Register
          </ui-button>
        </div>
      </form>
      <div class="p-2">
        <ui-code>
                  <pre>
                    &lt;form>
                      &lt;div class="grid-6-6">
                        &lt;ui-input v-model:value="user.firstName" required placeholder="First Name"/>
                        &lt;ui-input v-model:value="user.lastname" required placeholder="Last Name"/>
                      &lt;/div>
                      &lt;div>
                        &lt;ui-input type="email" required v-model:value="user.email" placeholder="Email"/>
                      &lt;/div>
                      &lt;div>
                        &lt;ui-input type="textarea" v-model:value="user.bio" label="Bio"/>
                      &lt;/div>
                      &lt;div>
                        &lt;ui-input type="select" label="Please select"
                                  placeholder="make a choice" :options="[{id:1,title:'one'},{id:2, title:'two'}]">
                        &lt;/ui-input>
                      &lt;/div>
                      &lt;div>
                        &lt;ui-input required v-model:value="user.tac" type="checkbox" label="Accept terms and conditions"/>
                      &lt;/div>
                      &lt;div class="grid m-t-2">
                        &lt;ui-button class="place-x-end" >
                          &lt;ui-icon>how_to_reg&lt;/ui-icon> Register
                        &lt;/ui-button>
                      &lt;/div>
                    &lt;/form>
                  </pre>

        </ui-code>
      </div>
    </div>
  </div>
</template>

<script>
import UiButton from '/vue/ui/lib/ui.button';
import UiInput from '/vue/ui/lib/ui.input';
import UiCode from '/vue/ui/lib/ui.code';
import UiIcon from '/vue/ui/lib/ui.icon';

export default {
  components: {
    UiCode,
    UiButton,
    UiInput,
    UiIcon
  },
  name: "viewForm",
  data:()=>({
    toggleAlt: false,
    switchToggle:false
  }),
  template: `{{template}}`
}
</script>

<style scoped>

</style>