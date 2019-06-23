<style scoped>
.find-box {
  border: 1px solid rgba(27,31,35,.15);
  box-shadow: 0 3px 12px rgba(27,31,35,.15);
  top: 63px;
  width: 500px;
  left: 0;
  right: 0;
  margin: 0 auto;
}

.notifications-box {
  border: 1px solid rgba(27,31,35,.15);
  box-shadow: 0 3px 12px rgba(27,31,35,.15);
  top: 63px;
  width: 500px;
  left: 0;
  right: 0;
  margin: 0 auto;
}

.bg-modal-find {
  position: fixed;
  z-index: 100;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: rgba(0, 0, 0, 0.3);
  display: flex;
  justify-content: center;
  align-items: center;
}

.ball-pulse {
  right: 8px;
  top: 10px;
  position: absolute;
}
</style>

<template>
  <div>
    <vue-snotify />

    <header class="bg-white dn db-m db-l mb3 relative">
      <div class="ph3 pt1 w-100">
        <div class="cf">
          <div class="fl w-20 pa2">
            <a class="relative header-logo" href="">
              <img src="/img/logo.svg" height="30" />
            </a>
          </div>
          <div class="fl w-60 tc">
            <div v-show="noMenu" class="dib w-100"></div>
            <ul v-show="!noMenu" class="mv2">
              <li class="di header-menu-item pa2 pointer mr2">
                <span class="fw5">
                  <img class="relative" src="/img/header/icon-home.svg" />
                  {{ $t('app.header_home') }}
                </span>
              </li>
              <li class="di header-menu-item pa2 pointer mr2" data-cy="header-find-link" @click="showFindModal">
                <span class="fw5">
                  <img class="relative" src="/img/header/icon-find.svg" />
                  {{ $t('app.header_find') }}
                </span>
              </li>
              <li class="di header-menu-item pa2 pointer" data-cy="header-notifications-link" @click="showNotifications">
                <span class="fw5">
                  <img class="relative" src="/img/header/icon-notification.svg" />
                  {{ $t('app.header_notifications') }}
                </span>
              </li>
            </ul>
          </div>
          <div class="fl w-20 pa2 tr relative header-menu-settings">
            <header-menu :user="user" />
          </div>
        </div>
      </div>

      <!-- FIND BOX -->
      <div v-show="modalFind" v-click-outside="hideFindModal" class="absolute z-max find-box">
        <div class="br2 bg-white tl pv3 ph3 bounceIn faster">
          <form @submit.prevent="search">
            <div class="relative">
              <input id="search" ref="search" v-model="form.terms" type="text" name="search"
                     :placeholder="$t('app.header_search_placeholder')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required
                     @keydown.esc="modalFind = false"
                     @keyup="search"
              />
              <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
            </div>
          </form>

          <!-- Search results -->
          <ul v-show="dataReturnedFromSearch" class="pl0 list ma0 mt3" data-cy="results">
            <li class="b mb3">
              <ul v-if="contacts.length > 0" class="list ma0 pl0">
                <li v-for="contact in contacts" :key="contact.id">
                  <a href="/">{{ contact.enc_first_name }}</a>
                </li>
              </ul>
              <div v-else class="silver">
                No contacts found
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- NOTIFICATIONS BOX -->
      <div v-if="showModalNotifications" v-click-outside="hideNotifications" class="absolute z-max notifications-box">
        <div class="br2 bg-white tl pv3 ph3 bounceIn faster">
          <div v-show="notifications.length == 0">
            <img class="db center mb2" srcset="/img/header/notification_blank.png,
                                        /img/header/notitication_blank@2x.png 2x"
            />
            <p class="tc">
              All is clear!
            </p>
          </div>

          <ul v-show="notifications.length > 0">
            <li v-for="notification in notifications" :key="notification.id">
              {{ notification.action }}
            </li>
          </ul>
        </div>
      </div>
    </header>

    <!-- MOBILE MENU -->
    <header class="bg-white mobile dn-ns mb3">
      <div class="ph2 pv2 w-100 relative">
        <div class="pv2 relative menu-toggle">
          <label for="menu-toggle" class="dib b relative">Menu</label>
          <input id="menu-toggle" type="checkbox" />
          <ul id="mobile-menu" class="list pa0 mt4 mb0">
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                Home
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                app.main_nav_people
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                app.main_nav_journal
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                app.main_nav_find
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                app.main_nav_changelog
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                app.main_nav_settings
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                app.main_nav_signout
              </a>
            </li>
          </ul>
        </div>
        <div class="absolute pa2 header-logo">
          <a href="">
            <img src="/img/logo.svg" width="30" height="27" />
          </a>
        </div>
      </div>
    </header>

    <div :class="[ modalFind ? 'bg-modal-find' : '' ]"></div>

    <slot></slot>
  </div>
</template>

<script>
import ClickOutside from 'vue-click-outside';
import 'vue-loaders/dist/vue-loaders.css';
import * as VueLoaders from 'vue-loaders';
Vue.use(VueLoaders);

export default {
  directives: {
    ClickOutside
  },

  props: {
    title: {
      type: String,
      default: '',
    },
    noMenu: {
      type: Boolean,
      default: false,
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

  data() {
    return {
      loadingState: '',
      modalFind: false,
      processingSearch: false,
      showModalNotifications: false,
      dataReturnedFromSearch: false,
      form: {
        terms: null,
        errors: [],
      },
      contacts: [],
    };
  },

  watch: {
    title(title) {
      this.updatePageTitle(title);
    }
  },

  mounted() {
    this.updatePageTitle(this.title);

    // prevent click outside event with popupItem.
    this.popupItem = this.$el;
  },


  methods: {
    updatePageTitle(title) {
      document.title = title ? `${title} | Example app` : 'Example app';
    },

    hideFindModal() {
      this.dataReturnedFromSearch = false;
      this.form.searchTerm = null;
      this.contacts = [];
      this.modalFind = false;
    },

    showFindModal() {
      this.dataReturnedFromSearch = false;
      this.form.searchTerm = null;
      this.contacts = [];
      this.modalFind = !this.modalFind;

      this.$nextTick(() => {
        this.$refs.search.focus();
      });
    },

    showNotifications() {
      this.showModalNotifications = !this.showModalNotifications;

      axios.post('/notifications/read')
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    hideNotifications() {
      this.showModalNotifications = false;
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post('/search/name', this.form)
            .then(response => {
              this.dataReturnedFromSearch = true;
              this.contacts = response.data.data;
              this.processingSearch = false;
            })
            .catch(error => {
              this.loadingState = null;
              this.form.errors = _.flatten(_.toArray(error.response.data));
              this.processingSearch = false;
            });
        }
      }, 500),
  },
};
</script>
