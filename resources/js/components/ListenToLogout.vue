<template>
  <div>
    <v-dialog persistent v-model="dialog" width="500">
      <v-card>
        <v-card-title class='justify-center'>
          <span class="headline">Сессия завершится через {{ secondsUntilLogout }}...</span>
        </v-card-title>
        <v-card-text>
        <v-layout wrap align-center>
        </v-layout>
        <v-card-actions>
         <v-spacer></v-spacer>
         <v-btn color="primary" @click.native="continueSession">Продолжить сессию</v-btn>
         <v-spacer></v-spacer>
       </v-card-actions>
      </v-card-text>
    </v-card>
   </v-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dialog: false,
      secondsUntilLogout: null,
      interval: null
    }
  },

  created() {
    this.listenToLogout()
  },

  methods: {
    listenToLogout() {
      const pusher = new Pusher(process.env.MIX_SSO_PUSHER_APP_KEY, {
        cluster: 'eu'
      })
      const channel = pusher.subscribe('session.' + this.$store.state.user.id)
      channel.bind("App\\Events\\LogoutSignal", (data) => {
        switch (data.action) {
          case 'notify':
            return this.logoutCountdown()
          // не выбрасываеися так, теперь вкладки выкидываются на onfocus
          // case 'destroy':
          //   location.reload()
          //   break
        }
      })
    },

    logoutCountdown() {
      this.dialog = true
      this.secondsUntilLogout = 59
      this.interval = setInterval(() => {
        if (this.secondsUntilLogout <= 0) {
          this.$store.dispatch('logout')
          this.interval = null
          this.dialog = false
        } else {
          this.secondsUntilLogout--
        }
      }, 1000)
    },

    continueSession() {
      axios.get(apiUrl('continue-session'))
      this.interval = null
      this.dialog = false
    }
  }
}
</script>
