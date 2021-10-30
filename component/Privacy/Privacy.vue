<template>
  <div class="container">
    <h1>Privacy Policy</h1>
    <p class="font-sm font-light">Last revised and updated 10/29/2021</p>
    <p>The technology we use allows us to go far beyond regulatory minimums and offer you a service that truly respects
      your privacy!</p>
    <h2>1. Basis of this service</h2>
    <p>
      Please note that blua.blue is open-source and the following can only be understood in the context of
      https://blua.blue, a privately maintained webspace.
      Our data is stored on own servers rented through our hosting provider and secured with state-of-the-art encryption
      technology.
    </p>
    <h2>2. Two types of data</h2>
    <p>
      We will distinguish between personal and non-personal data.
    </p>
    <p><strong>Person data</strong> includes, but is not limited to, information like your email address, billing
      information and alike.
      <br>We limit the collection of such data intentionally to a minimum and only add to it if necessary.</p>
    <p><strong>Non-personal data</strong> helps us to generate statistics and improve user experience, but holds no
      connection to - or has been removed from - any data that could identify an individual.</p>
    <p>In addition to the data we collect, any data users provide (public articles, profile picture, username, etc) is
      processable by us (and the world).
      Although the responsibility of such content is governed by our terms and conditions, a mindful approach when
      publishing anything online is wise.</p>
    <h2>3. Third parties and data security</h2>
    <p>We use as little "outside help" as possible to avoid handing off personal data. From captcha to data-warehousing, our services are internal and data isn't shared. However, we can't do it all. In particular, we use</p>
    <div class="grid-4-4-4">
      <p class="font-strong text-primary b-b-1 b-primary p-1">Service</p>
      <p class="font-strong text-primary b-b-1 b-primary p-1">Purpose</p>
      <p class="font-strong text-primary b-b-1 b-primary p-1">
        Controlled / processed data
        <a href="https://ec.europa.eu/info/law/law-topic/data-protection/reform/rules-business-and-organisations/obligations/controller-processor/what-data-controller-or-data-processor_en"><ui-icon>info</ui-icon></a>
      </p>
      <p class="p-1"><a href="https://mailjet.com">Mailjet</a>, a GDPR* and CCPA* compliant mailing service.</p>
      <p class="p-1">User communication, authentication, service facilitation and marketing</p>
      <p class="p-1">email, username, statistical behavior on email interaction</p>
      <p class="p-1"><a href="https://piwik.pro/">Piwik.pro</a>, a GDPR* and CCPA* compliant analytics tool</p>
      <p class="p-1">Create statistics on (unique) readership, improve service & appearance</p>
      <p class="p-1">browser cookies, browsing behavior, device information, location, IP address</p>
    </div>
    <p>We are intentionally not in bed with extensive data collectors including famous search engines or social media platforms.
      This means that services like "sign up with your X-account" are not possible and advertisement might not be as targeted as you are used to. However, we believe that's in your best interest.</p>
    <p class="font-sm">* CCPA - California Consumer Privacy Act of 2018 and GDPR - General Data Protection Regulation (EU) regulate better control over citizens' data.
      We extend the gist of this approach to all our users.</p>
    <h2>4. Data persistence protection and security</h2>
    <p>We do not give any guarantees regarding the lifespan of this service nor data persistence.
      Our due diligence cannot exceed regular backups in case of catastrophic events. Instead, we introduce the concept of the opposite:
      Our servers are set up to permanently erase all data in case of termination or malfunction of the system, making data-leaks improbable.
      We secure data on the same physical hardware our server side code runs, effectively eliminating network communication between stored data and processing.
      Client communication is secured via SSL encryption and additional measurements against certain attacks.
    </p>
    <h2>5. Dear Californians & EU citizens and all others</h2>
    <p>The following disclaimer is a legal requirement of CCPA:</p>
    <ui-alert color="white-filled">
      Subject to the limitations under California Civil Code § 1798.83, if you are a California resident and have an established business relationship with us, you may ask us to provide you with a list of certain categories of personal information that we have disclosed to third parties for their direct marketing purposes during the immediately preceding calendar year. You may also request the identity of certain third parties that received your personal information from us for their direct marketing purposes during the immediately preceding calendar year. You may make a request as described hereinabove once a year.
    </ui-alert>
    <p>The following rights extend to EU citizens according to GDPR:</p>
    <ui-alert color="white-filled">
      <ul>
        <li>Request access to your personal data</li>
        <li>Request correction of the personal data</li>
        <li>Request erasure of your personal data (right to forget)</li>
        <li>Withdraw your consent and opt-out from our communications</li>
        <li>Object to processing of your personal data</li>
        <li>Request the restriction of processing of your personal data</li>
        <li>Request the transfer of your personal data</li>
      </ul>
    </ui-alert>
    <p>Our reality: <strong>ALL users</strong> can make such requests <strong>as often as needed</strong>.
      The processes of deleting all traces of your account and collecting all information ever obtained are automated and can be processed fast if requested.
      Should you not have an account with us, we will not collect or share personal data unless you interact with the platform without being a member (e.g. DCMA reporting).
      However, to verify that (and fulfill this legal requirement), you can send us an inquiry with "California Shine the Light" at contact@blua.blue and we will respond within 30 days.</p>
    <h2>6. About changes to this policy</h2>
    <p>Changes to this policy are communicated via banner on this website and/or email communication. All changes take effect </p>
    <ul>
      <li>immediately for new users</li>
      <li>after 30 days for users who agreed to a previous version of this policy</li>
    </ul>
  </div>

</template>

<script>
import uiIcon from '/vue/ui/lib/ui.icon'
import uiAlert from '/vue/ui/lib/ui.alert'
export default {
  components:{
    uiIcon,
    uiAlert
  },
  mounted() {
    console.log('attached: privacy')
  },
  template: `{{template}}`,
}
</script>