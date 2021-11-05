<template>
  <div class="container m-y-5">
    <h1>Making live easier</h1>
    <p>We are currently developing SDKs for various languages. Yours isn't here yet? Feel free to contribute @ <a
        href="https://github.com/blua-blue">github.com</a></p>
    <section>
      <div class="grid-6-6">
        <div n-for="sdks as sdk" v-for="sdk in sdks" class="m-1 b-1 b-primary b-rounded p-2">
          <h2>{{sdk.lang}}</h2>
          <p>{{sdk.description}}</p>
          <a class="text-primary text-decoration-none" href="{{sdk.repo}}" :href="sdk.repo" target="_blank" rel="noreferrer noopener">REPOSITORY</a>
        </div>
      </div>
    </section>

  </div>
</template>

<script>
export default {
  data:()=>({
    sdks: [
      {lang: 'PHP', repo: 'https://github.com/blua-blue/blua-blue-php-sdk#readme', description:'Easy to use Guzzle-based API wrapper'},
      {lang: 'JavaScript', repo: 'https://github.com/blua-blue/blua-blue-js#readme', description:'Universal SDK for node, deno and the browser'}
    ]
  }),
  template: `{{template}}`,
}
</script>