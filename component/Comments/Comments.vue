<template>
  <div class="b-t-1 m-y-4 p-t-3">
    <h3>Comments</h3>
    <p v-if="comments && comments.length<1">There aren't any comments yet.</p>
    <div class="b-2 b-rounded b-gray-50 p-3" v-for="comment in comments">
      {{comment}}
    </div>

  </div>
</template>

<script>
export default {
  props: {
    comments:Array
  },
  template: `{{template}}`,
}
</script>