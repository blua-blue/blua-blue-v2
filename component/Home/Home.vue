<template>
  <div class="container" >

    <div class="grid">
      <img class="place-x-center" src="{{base}}asset/img/blua-blue-logo.png" style="max-width: 400px" alt="logo">
      <h1 class="place-x-center">Content Enablement platform</h1>
      <article class="place-x-center">
        <ui-tag color="accent-filled" @click="scrollTo('hybrid')">Hybrid</ui-tag>
        <ui-tag color="success-filled" @click="scrollTo('headless')">Headless</ui-tag>
        <ui-tag color="primary-filled" @click="scrollTo('restful')">RESTful</ui-tag>
        <ui-tag color="warning-light-filled" @click="scrollTo('dev-friendly')">Developer friendly</ui-tag>
        <ui-tag color="gray-filled">CMS</ui-tag>
      </article>

    </div>
    <div class="grid md:grid-7-5 lg:grid-8-4 b-b-1 b-t-1 m-y-5 b-accent p-y-5 " style="min-height: 400px">
      <div >
        <h2 class="font-md text-primary m-l-2">Newest public articles</h2>
        <n-articles :inline="true"></n-articles>
      </div>
      <div class="bg-white-75 b-rounded-2 m-x-2 b-1 b-primary place-y-start">
        <h2 class="font-md text-primary m-l-2">Log in</h2>
        <n-login></n-login>
      </div>

    </div>

    <h2 class="font-lg text-primary text-uppercase">What is blua.blue?</h2>
    <p>
      Blua.blue is a solution for distributing content either to your own or 3rd party endpoints.
      Being open source, it can either be used as a headless CMS or a full-fledged CMS. Using vastN3 technology,
      it's an ideal solution for SEO-relevant content. <br>

    </p>
    <div class="grid md:grid-6-6">
      <div class="b-rounded b-1 b-primary md:m-r-1">
        <h2 class="text-primary text-uppercase p-3">For authors</h2>
        <p class="p-3">
          Write SEO conscious content regardless of intended placement. Your developers will set up connectivity, style and behavior once and you will be publishing to whatever platform you want. Try it out!
        </p>
        <router-link to="/register" class="text-decoration-none">
          <a class="text-decoration-none d-block text-center b-t-1 b-primary-50 text-primary p-y-2 hover:bg-primary hover:text-white" href="{{base}}register">sign up</a>
        </router-link>
      </div>
      <div class="b-rounded b-1 b-primary md:m-l-1">
        <h2 class="text-primary text-uppercase p-3">For developers</h2>
        <p class="p-3">
          Never worry about authentication and an authoring backend again. Offering dynamic content has never been so easy. Oh, and imagine new projects will have no more onboarding for marketers either.
        </p>
        <router-link to="/register" class="text-decoration-none">
          <a class="text-decoration-none d-block text-center b-t-1 b-primary-50 text-primary p-y-2 hover:bg-primary hover:text-white" href="{{base}}register">sign up</a>
        </router-link>
      </div>
    </div>
    <p>Key points:</p>



    <ui-alert class="d-flex m-t-3" color="accent">
      <ui-icon class="font-lg text-success-dark">swap_vert</ui-icon>
      <div class="m-l-2">
        <h2 id="hybrid">Hybrid</h2>
        <p>
          blua.blue uses VastN3, a technology enabling server-side rendered content while not giving up on the usability of a single page application.
          What does this mean for:
        </p>
        <ui-accordion title="developers" v-model:open="openAccordion.developer">
          <p class="text-black">
            Are you comfortable with writing VueJS? Then you are 90% there. VastN3 uses it's own vue3 compiler to generate
            content that renders to the DOM before your app takes over and runs an SPA.
            This is easiest to understand by examining the source of this page.
          </p>
        </ui-accordion>
        <ui-accordion title="content creators" v-model:open="openAccordion.contentCreator">
          <p class="text-black">
            As content creator, you can lean back and simply do what you do best: writing exciting content.
            While you will write on blua.blue, your content can be published to external outlets (one or multiple),
            apps and/or aggregator websites. So you have one single platform to worry about when it comes to curating and managing your content,
            while your readers could be on a variety of different sites or devices.
          </p>
        </ui-accordion>
        <ui-accordion title="marketing gurus" v-model:open="openAccordion.marketer">
          <p class="text-black">
            A new era begins! VastN3 was test-driven in the e-commerce field and has had outlandish results.
            blua.blue wants to bring those results to content and is designed to provide you an SEO-efficacy out of this world.
          </p>
        </ui-accordion>
      </div>

    </ui-alert>
    <ui-alert class="d-flex m-t-3" color="success">
      <ui-icon class="font-lg text-success-dark">backup</ui-icon>
      <div class="m-l-2">
        <h2 id="headless">Headless</h2>
        <p>
          Wait, blua.blue is obviously a CMS, isn't it? <br>
          Actually, what you see here is something like a demo-implementation of itself.
          blua.blue has layers and what you see here is a front-end on top of the actual application.
        </p>
        <ui-accordion title="developers" v-model:open="openAccordion.developer">
          <p class="text-black">
            You probably guessed it already: you don't have to use this front-end at all.
            Instead, every interaction with the backend can be achieved with a stateless REST API as well.
            We even offer SDKs.
          </p>
        </ui-accordion>
        <ui-accordion title="content creators" v-model:open="openAccordion.contentCreator">
          <p class="text-black">
            Your team can freely develop, adjust or simplify your authoring experience. Starting from templates to handling categories or tags,
            this system can be built around you - completely independent from where your content will appear.
          </p>
        </ui-accordion>
        <ui-accordion title="marketing gurus" v-model:open="openAccordion.marketer">
          <p class="text-black">
            Since content creators can have a unified writing & editing experience, changes to multiple outlets are faster and don't require
            a developer team (once set up, that is). This enables shorter turn-around times when you want to react to seasons, current events etc.
          </p>
        </ui-accordion>
      </div>

    </ui-alert>
    <ui-alert class="d-flex m-t-3" color="primary">
      <ui-icon class="font-lg text-success-dark">api</ui-icon>
      <div class="m-l-2">
        <h2 id="restful">RESTful</h2>
        <p>
          This might be clear by now, but just in case it's not: whoever is able to work with a good ol' REST JSON API can build with blua.blue
        </p>
        <ui-accordion title="developers" v-model:open="openAccordion.developer">
          <p class="text-black">
            You don't need to dive deep to make use of blua.blue. As soon as you have your installation running, you can query against the blua.blue API
            from any project you work on. So designing your own blog, portfolio or similar while writing and editing on blua.blue is all you need to have a completely unique experience.
          </p>
        </ui-accordion>
        <ui-accordion title="content creators" v-model:open="openAccordion.contentCreator">
          <p class="text-black">
            Not sure what to make of this topic? That's because with blua.blue, content distribution is nothing a writer needs to worry about.
          </p>
        </ui-accordion>
        <ui-accordion title="marketing gurus" v-model:open="openAccordion.marketer">
          <p class="text-black">
            The blua.blue API allows for fast roll-outs of applications even with a relatively inexperienced team. As with all projects,
            being able to fully utilize junior developers creates the possibility for low-cost development.
          </p>
        </ui-accordion>
      </div>

    </ui-alert>
    <ui-alert class="d-flex m-t-3" color="warning-light">
      <ui-icon class="font-lg text-success-dark">waving_hand</ui-icon>
      <div class="m-l-2">
        <h2 id="dev-friendly">Developer friendly</h2>
        <p>
          This might be clear by now, but just in case it's not: whoever is able to work with a good ol' REST JSON API can build with blua.blue
        </p>
        <ui-accordion title="developers" v-model:open="openAccordion.developer">
          <p class="text-black">
            You don't need to dive deep to make use of blua.blue. As soon as you have your installation running, you can query against the blua.blue API
            from any project you work on. So designing your own blog, portfolio or similar while writing and editing on blua.blue is all you need to have a completely unique experience.
          </p>
        </ui-accordion>
        <ui-accordion title="content creators" v-model:open="openAccordion.contentCreator">
          <p class="text-black">
            Not sure what to make of this topic? That's because with blua.blue, content distribution is nothing a writer needs to worry about.
          </p>
        </ui-accordion>
        <ui-accordion title="marketing gurus" v-model:open="openAccordion.marketer">
          <p class="text-black">
            The blua.blue API allows for fast roll-outs of applications even with a relatively inexperienced team. As with all projects,
            being able to fully utilize junior developers creates the possibility for low-cost development.
          </p>
        </ui-accordion>
      </div>

    </ui-alert>
    <div class="home-bg"></div>
  </div>
</template>
<style>
 .home-bg{
   top:0;
   left: 0;
   right: 0;
   height: 100vh;
   position: absolute;
   z-index: -1;
   opacity: .3;
   background-image: url('{{base}}asset/img/content-is-king-large.jpg');
   background-size: 60%;
   background-position: center center;
   background-repeat: no-repeat;
 }
</style>
<script>
import uiTag from "/vue/ui/lib/ui.tag"
import uiAlert from "/vue/ui/lib/ui.alert"
import uiIcon from "/vue/ui/lib/ui.icon"
import uiAccordion from "/vue/ui/lib/ui.accordion"
import Articles from "/vue/articles"
export default {
  components:{
    uiTag,
    uiIcon,
    uiAlert,
    uiAccordion,
    Articles
  },
  data:()=>({
    openAccordion: {
      developer:false,
      contentCreator:false,
      marketer:false
    }
  }),
  methods:{
    scrollTo(id){
      document.getElementById(id).scrollIntoView()
    }
  },
  template: `{{template}}`,
}
</script>