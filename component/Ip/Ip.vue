<template>
  <div class="container">
    <h1>Intellectual Property</h1>
    <p>blua.blue is never responsible for content posted by its users as outlined in our terms & conditions.</p>
    <p>Although this text is purely informative, we understand that your expectations towards handling any claims are based on the following.</p>
    <p>We do investigate copyright and intellectual property claims in the following way</p>
    <h2>1. On https://blua.blue</h2>
    <h3>A. How</h3>
    <p>
      Below every article is a report button ( <ui-button color="warning"><ui-icon>flag</ui-icon> report</ui-button> )
      which allows filing complaints without having to sign up. If we find claims to be justified, we will process them accordingly.
      In this process, we might reach out using the provided email address in order to obtain more information (e.g. proof of ownership).
      Possible results are
    </p>
    <ul>
      <li>Claim unjustified</li>
      <li>Content removal</li>
      <li>Banning of offending author from our platform</li>
    </ul>
    <h3>B. Provided data</h3>
    <p>
      In order to process a claim successfully, your claim should include information regarding reasonable proof of ownership and cooperation
      might be necessary when we reach out to further clarify.
      Please note that other than the email of the reporting individual, authors are provided with the content of the original claim made against them
      (Further correspondence is private).
    </p>
    <h3>C. Processing obligation</h3>
    <p>
      <strong>We do not grant a right to an answer.</strong> Due to the nature of complaints in general,
      not all complaints will be taken seriously and we do not promise a reaction regardless of outcome of the claim.
      Please be as specific as possible when reporting content in order to not be confused with spam or otherwise fraudulent claims.
    </p>
    <h3>D. Language</h3>
    <p>
      Regardless of the content in question, claims have to be made in English.
    </p>
    <h3>F. Automated claims & spam</h3>
    <p>
      We do not process any automated claims and reserve the right to block certain report-behavior <strong>without any feedback to the user interface.</strong>
    </p>
    <h2>2. Content delivered by https://blua.blue to external domains</h2>
    <p>
      Please note that when content is removed it does not necessarily mean it has been removed from the internet. blua.blue delivers content through webhooks and API and
      we have no control over content outside our domain.
    </p>
    <h2>3. Content available through the use of self-hosted blua.blue installations</h2>
    <p>
      If you see this text outside of the blua.blue domain (https://blua.blue), you are on a self-hosted version. blua.blue is available as open source and might therefore
      be hosted by individuals or entities. blua.blue has no possibility to investigate any claims outside our domain.
    </p>
  </div>
</template>

<script>
import uiButton from '/vue/ui/lib/ui.button';
import uiIcon from '/vue/ui/lib/ui.icon';
export default {
  components:{
    uiButton, uiIcon
  },
  mounted(){
    console.log('attached: ip')
  },
  template: `{{template}}`,
}
</script>