<template>
  <div class="grid position-relative" :style="{height}">
      <div
          class="code-tag bg-gray-dark text-white p-2 b-rounded b-1 b-primary position-absolute"
          style="overflow-x: auto; width: calc(100% - 1rem)">
        <pre v-html="slotContent"></pre>
        <slot v-if="!slotContent"></slot>
      </div>
  </div>

</template>

<script>
export default {
  props:{
    lang:{
      type: String,
      default: 'any'
    }
  },
  data:()=>({
    height: 0,
    slotContent: null
  }),
  mounted(){
    this.$nextTick(()=>{
      this.height = this.$el.querySelector('.code-tag').clientHeight + 'px';
      this.interpolate(this.$slots.default()[0].el)
      this.basicHighlight(this.$slots.default()[0].el);
    })

  },

  methods:{
    interpolate(el){
      if(el.tagName === 'PRE'){
        const countSpaces = (el.innerText.match(/\s*/)[0].length);
        this.slotContent = el.innerHTML.replace(/\n\n/,"<br>\n")
        const regEx = new RegExp('^\\s{0,'+countSpaces+'}','gm');
        this.slotContent = this.slotContent.replace(regEx,'')
      }
    },
    basicHighlight(el){
      if(el.tagName === 'PRE'){
        const applicableHighlighting =   highlightingPatterns.filter(x => x.lang === 'any' || x.lang === this.lang);
        applicableHighlighting.forEach(rule => {
          this.highlighting(rule.regExp, rule.color)
        })

        // final strip
        this.slotContent = this.slotContent.replace(/&amp;/g,'&').replace(/&lt;br&gt;/g,'<br>');
      }

    },
    highlighting(regEx, color){
      this.slotContent = this.slotContent.replace(regEx, match => highlightTemplate(match, color))
    }
  },
  template: `{{template}}`
}

const highlightingPatterns = [
  {
    name:'html tags',
    lang:'any',
    regExp:/&lt;\/*[a-z0-9\s\n=":/[{}\]',._-]+(&gt;|>)/gmi,
    color: 'warning-lighter'
  },
  {
    name: 'double quotes',
    lang: 'any',
    regExp:/(?!.*hl)"[a-z0-9\s\n=:/[{}\]',._-]+"/gmi,
    color:'success-light'
  },
  {
    name: 'keywords',
    lang: 'javascript',
    regExp:/(^|\s)(import|export|function|from|default)\s/g,
    color: 'accent-light'
  },
  {
    name: 'single quotes',
    lang: 'javascript',
    regExp: /(?!.*hl)'[a-z0-9\s\n=:/[{}\]",._-]+'/gmi,
    color:'success-light'
  },
  {
    name: 'variables',
    lang: 'php',
    regExp: /(\$|\))[a-z0-9&(;-]*/ig,
    color: 'success'
  },
  {
    name: 'comments single line',
    lang: 'php',
    regExp: /\/\/[^\n]/g,
    color: 'primary'
  }
];

const highlightTemplate = (match, color)=>{
  const newEl = document.createElement('span');
  newEl.className = 'hl text-'+ color;
  newEl.innerText = match;
  return newEl.outerHTML;
}
</script>
