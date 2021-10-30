<template>
  <ui-layout v-model:menu-expanded="menuOpen">
    <template #menu>
      <div class="menu" v-for="item in menu" :key="item.name">
        <h3 class="text-gray-light m-t-3">{{ item.name }}</h3>
        <button class="menu-button" v-for="sub in item.links" @click="transition(sub,300)">{{ sub }}</button>
      </div>

    </template>
    <div class="m-x-auto lg:m-l-5 w-75p lg:w-50p bg-white b-rounded p-5 m-t-5" >
      <div class="m-5 grid-11-1">
        <transition name="slide-fade">
          <view-introduction v-if="activeView === 'Introduction'"/>
        </transition>
        <transition name="slide-fade">
          <view-gaudiamus v-if="activeView === 'Gaudiamus SCSS'"/>
        </transition>
        <transition name="slide-fade">
          <view-theming v-if="activeView === 'Theming / Color'"/>
        </transition>
        <transition name="slide-fade">
          <view-alert v-if="activeView === 'Alert'"/>
        </transition>
        <transition name="slide-fade">
          <view-accordion v-if="activeView === 'Accordion'"/>
        </transition>
        <transition name="slide-fade">
          <view-button v-if="activeView === 'Button'"/>
        </transition>
        <transition name="slide-fade">
          <view-code v-if="activeView === 'Code'"/>
        </transition>
        <transition name="slide-fade">
          <view-icon v-if="activeView === 'Icon'"/>
        </transition>
        <transition name="slide-fade">
          <view-form v-if="activeView === 'Form'"/>
        </transition>
        <transition name="slide-fade">
          <view-modal v-if="activeView === 'Modal'"/>
        </transition>
        <transition name="slide-fade">
          <view-progress v-if="activeView === 'Progress'"/>
        </transition>
        <transition name="slide-fade">
          <view-tab v-if="activeView === 'Tab'"/>
        </transition>
        <transition name="slide-fade">
          <view-tag v-if="activeView === 'Tag'"/>
        </transition>
      </div>

    </div>

  </ui-layout>

</template>

<script>
import uiLayout from '/vue/ui/lib/ui.layout';


// views
import ViewAlert from '/vue/ui/views/view.alert';
import ViewIntroduction from '/vue/ui/views/view.introduction';
import ViewAccordion from '/vue/ui/views/view.accordion';
import ViewButton from '/vue/ui/views/view.button';
import ViewCode from "/vue/ui/views/view.code";
import ViewIcon from "/vue/ui/views/view.icon";
import ViewForm from "/vue/ui/views/view.form";
import ViewModal from "/vue/ui/views/view.modal";
import ViewProgress from "/vue/ui/views/view.progress";
import ViewTab from "/vue/ui/views/view.tab";
import ViewTag from "/vue/ui/views/view.tag";
import ViewGaudiamus from "/vue/ui/views/view.gaudiamus";
import ViewTheming from "/vue/ui/views/view.theming";

const menu = [
  {
    name: 'General',
    active: false,
    links: ['Introduction']
  },
  {
    name: 'CSS',
    active: false,
    links: [
      'Gaudiamus SCSS', 'Theming / Color'
    ]
  },
  {
    name: 'Components',
    active: false,
    links: [
      'Accordion',
      'Alert',
      'Button',
      'Code',
      'Form',
      'Icon',
      'Modal',
      'Progress',
      'Tab',
      'Tag',
    ]
  }
];

export default {
  name: 'nUi',
  components: {
    ViewGaudiamus,
    ViewAlert,
    ViewModal,
    ViewForm,
    ViewIcon,
    ViewCode,
    ViewAccordion,
    ViewButton,
    ViewIntroduction,
    ViewProgress,
    ViewTab,
    ViewTag,
    ViewTheming,
    uiLayout
  },
  data() {
    return {
      menu,
      activeView: 'Introduction',
      showModal: false,
      value: 33,
      number: 0,
      string: '',
      activeTab: 0,
      toggle: false,
      toggleAlt: false,
      menuOpen: false
    }
  },
  methods: {
    transition(target, delay) {
      this.activeView = '';
      this.menuOpen = false;
      setTimeout(() => this.activeView = target, delay)
    },
    route() {
      this.$router.push('/foo')
    }
  },
  template: `{{template}}`,
}


</script>

<style>

</style>