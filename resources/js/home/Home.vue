<style scoped>
.home-box {
  color: #4d4d4f;
  height: 300px;
  width: 300px;
}

@media (max-width: 480px) {
  .home-box {
    width: 100%;
  }
}

.home-company {
  left: -20px;
  bottom: -20px;
}

@media (max-width: 480px) {
  .home-company img {
    bottom: 0;
  }
}

.home-join {
  left: 14px;
  bottom: 11px;
}

@media (max-width: 480px) {
  .home-join img {
    bottom: 0;
  }
}
</style>

<template>
  <layout title="Home" :no-menu="false" :user="user" :notifications="notifications">
    <div class="ph2 ph0-ns">
      skdjlfak
      <ul>
        <li v-for="contact in contacts">
          {{ contact.enc_first_name }}
        </li>
      </ul>
    </div>
  </layout>
</template>

<script>
export default {
  props: {
    contacts: {
      type: Array,
      default: null,
    },
    user: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },
};
</script>
