<template lang="html">
  <div>
    <v-fade-transition>
      <div 
        class='new-requests' 
        @click='$refs.DisplayData.reloadData(); newRequestsAppeared = false'
        v-if='newRequestsAppeared'>есть новые заявки</div>
    </v-fade-transition>
    <DisplayData :api-url='API_URL' :filters='FILTERS' :paginate='15' ref='DisplayData'>
      <template slot='buttons'>
        <AddBtn animated label='добавить заявку' @click.native='$refs.RequestDialog.open(null)' />
      </template>
      <template slot='items' slot-scope='{ items }'>
        <v-layout row wrap class='relative'>
          <v-flex xs12 v-for='item in items' :key='item.id'>
            <RequestItem 
              :item='item' 
              @openDialog='$refs.RequestDialog.open' 
              @openClientDialog='$refs.ClientDialog.open' 
            />
          </v-flex>
          <NoData
            :add='() => $refs.RequestDialog.open(null)'
            v-if='items.length === 0'
            fullscreen
          />
        </v-layout>
      </template>
    </DisplayData>
    <RequestDialog ref='RequestDialog' 
      @updated='(payload) => $refs.DisplayData.updateItem(payload)'
    />
    <ClientDialog ref='ClientDialog' />
  </div>
</template>

<script>

import { API_URL, FILTERS } from '@/components/Request'
import RequestItem from '@/components/Request/Item'
import RequestDialog from '@/components/Request/Dialog'
import { DisplayData } from '@/components/UI'
import { ClientDialog } from '@/components/Client'

export default {
  components: { RequestItem, RequestDialog, DisplayData, ClientDialog },

  data() {
    return {
      API_URL,
      FILTERS,
      newRequestsAppeared: false,
    }
  },

  created() {
    this.pusher.on('NewRequest', (response) => {
      if (response.request.created_email_id !== this.$store.state.user.email_id) {
        this.newRequestsAppeared = true
      }
    })
  },

  methods: {
    add() {
      this.$refs.RequestList.$refs.RequestDialog.open(null)
    },
  }
}
</script>

<style>
  .new-requests {
    position: fixed;
    background: #e06f4a;
    box-shadow: 0 0 10px #e06f4a;
    padding: 5px 10px;
    border-radius: 32px;
    color: white;
    left: 50%;
    z-index: 5;
    cursor: pointer;
  }
</style>