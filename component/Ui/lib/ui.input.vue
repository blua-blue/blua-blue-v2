<template>
  <label class="grid m-y-2" :class="{'grid-10-2':type === 'checkbox'||type === 'switch'}">
    <span class="text-accent m-y-1 font-default  bg-transparent ">
      <slot name="label">{{label}} <span v-if="label&&required">*</span></slot>
    </span>
    <textarea :disabled="disabled" :required="required" @keyup="$emit('update:value', $event.target.value)" @change="$emit('update:value', $event.target.value)" class="font-default bg-transparent b-0 b-b-1 b-gray focus:b-primary p-b-2 w-100p" rows="3" v-if="type === 'textarea'">{{value}}</textarea>
    <select :disabled="disabled" :multiple="multiple" v-else-if="type === 'select'" :required="required" class="font-default bg-transparent b-0 b-b-1 b-gray focus:b-primary p-b-2  w-100p" @change="$emit('update:value', $event.target.value)">
      <option disabled :selected="!value" v-if="placeholder">{{placeholder}}</option>
      <option v-for="option in options" :selected="value===option[optionValue]" :value="option[optionValue]">{{option[optionTitle]}}</option>
    </select>
    <span class="switch place-x-end place-y-end position-relative" v-else-if="type === 'switch'">
      <input :disabled="disabled" style="opacity: 0; height: 0; width: 0; position: absolute" type="checkbox" :required="required" :checked="value" >
      <span @click="$emit('update:value', !value)">
          <span style="height: 1em; width:35px" :class="{'bg-success-50':value}" class="d-inline-block b-rounded-3 b-1 b-primary position-relative animate-bg">
            <span class="b-round d-inline-block bg-primary position-absolute animate-position" :style="{left: value ? 'calc(100% - 1em)': 0, top:'0px', height: '1em', width:'1em'}"></span>
          </span>
      </span>

    </span>
    <span v-else-if="type === 'checkbox'" class="b-1 b-gray b-rounded position-relative m-t-1 place-x-end d-block" style="width: 1rem; height: 1rem">
      <input :disabled="disabled" ref="checkbox" @change="$emit('update:value', !value)" style="opacity: 0; top: 0; position: absolute" type="checkbox" :required="required" >
      <span
          class="d-block position-absolute"
          style="left: 0; top: 0; right: 0; bottom: 0"
          >
        <span class="material-icons position-absolute" style="top:-.3rem; left:-.1rem" v-show="value">done</span>

      </span>
    </span>

    <input :disabled="disabled" :required="required" v-else :type="type" :placeholder="placeholder" class="font-default bg-transparent b-0 b-b-1 b-gray focus:b-primary p-b-2  w-100p" :value="value" @keyup="$emit('update:value', $event.target.value)" />
  </label>

</template>


<script>
export default {
  props: {
    required: {
      type:Boolean,
      default: false
    },
    value: {
      type:[String, Number, Boolean],
      default: ''
    },
    label: String,
    options: Array,
    optionValue: {
      type: String,
      default: 'id'
    },
    optionTitle: {
      type: String,
      default: 'title'
    },
    placeholder: String,
    multiple: {
      type: Boolean,
      default: false
    },
    disabled: {
      type:Boolean,
      default: false
    },
    type: {
      type: String,
      default: 'text'
    }
  },
  data:()=>({
    internalValue:''
  }),
  methods:{
    slider(){
      return {
        background: 'black'
      }
    }
  },
  template: `{{template}}`
}
</script>

<style scoped>

</style>