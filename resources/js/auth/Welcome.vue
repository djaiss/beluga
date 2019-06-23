<template>
  <div class="ph2 ph0-ns">
    <div class="cf mt4 mw7 center br3 mb3 bg-white box">
      <p>Beluga encrypts your account and your data so no one, including us, can read them – except you.</p>
      <p>Everything starts with a Secret Key. It’s used to encrypt your data, so no one but you can read it. We also use it to decrypt your data when you need it. This Secret Key is not shared with anyone, even us.</p>
      <p>Below is your own unique Secret Key. Save it somewhere safe, as it will never be displayed ever again, and we will not save it on our servers. Without this key, your data can not be read. If you lose this key, your account is basically lost, and we won't be able to recover any data at all, forever.</p>
      <p>This is why we insist: save it somewhere safe. It WILL be gone forever once you leave this screen.</p>
      <p>We will ask for this key every time you access your account from a new browser, or if you log out from your account on this browser.</p>
      <p>This your secret key:</p>
      <p>{{ secret }}</p>

      <p><a href="/home">I have saved this key on my computer and I can continue</a></p>
    </div>
  </div>
</template>

<script>

export default {

  props: {
    user: {
      type: Object,
      default: null,
    },
    secret: {
      type: String,
      default: null,
    },
  },
};
</script>
