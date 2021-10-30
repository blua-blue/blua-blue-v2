<template>
  <textarea class="bg-white" ref="jodit"></textarea>
</template>

<script>
export default {
  props:{
    content: Object
  },
  data:()=>({
    joditEditor:null
  }),
  watch:{
    content(newV){
      if(newV.content_type === 'html'){
        this.joditEditor.value = newV.content
      }
    }
  },
  mounted(){
    this.joditEditor = new Jodit(this.$refs.jodit, {
      removeButtons: ['image','video','file'],
      editorCssClass:"bg-white",
      filebrowser: {
        ajax: {url: '{{base}}api.v1/image',method:"GET"},
        "createNewFolder": false,
        "deleteFolder": false,
        "renameFolder": false,
        "moveFolder": false,
        "moveFile": false,
        // process: "function(e){console.log(e); return e}"
      }
    });
    this.joditEditor.value = this.content.content
    this.joditEditor.events.on('change', update => this.content.content = update)
  },
  template: `{{template}}`,
}
</script>