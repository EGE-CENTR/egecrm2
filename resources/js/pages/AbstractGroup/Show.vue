<template>
  <div>
    <div class='headline mb-4'>
      Болото
    </div>

    <v-card class='mb-4' :class='config.elevationClass'>
      <v-card-text class='relative card-with-loader'>
        <Loader v-if='loading'></Loader>
        <v-layout wrap v-else>
          <v-flex md12>
            <div class='flex-items'>
              <div class='mr-5 pr-5'>
                <div class='item-label'>Предмет</div>
                <span>
                  {{ getData('subjects', $route.params.subject_id).name }}
                </span>
              </div>
              <div class='mr-5 pr-5'>
                <div class='item-label'>Учебный год</div>
                <span>{{ getData('years', $route.params.year).title }}</span>
              </div>
            </div>
          </v-flex>
          <v-flex md12 class='mt-5' v-if='clients.length'>
            <v-data-table
              class="full-width"
              hide-actions
              hide-headers
              :items='clients'
            >
              <template slot='items' slot-scope="props">
                <td width='200'>
                  <router-link :to="{name: 'ClientShow', params: { id: props.item.id }}">
                    {{ props.item.default_name }}
                  </router-link>
                </td>
                <td width='200'>
                  30%
                </td>
                <td width='200'>
                  смс отправлено
                </td>
                <td width='200'>
                  ТУР
                </td>
                <td class='text-md-right' style='padding-right: 16px'>
                    <v-btn 
                      @click='() => $refs.MoveClientDialog.open(abstractGroup, props.item.id)' 
                      flat icon color="black" class='ma-0'>
                      <v-icon>more_horiz</v-icon>
                    </v-btn>
                </td>
              </template>
            </v-data-table>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>
    <MoveClientDialog ref='MoveClientDialog' @moved='removeClientFromGroup' />
  </div>
</template>

<script>

import { API_URL } from '@/components/AbstractGroup'
import MoveClientDialog from '@/components/Group/MoveClientDialog'

export default {
  components: { MoveClientDialog },
  
  data() {
    return {
      loading: true,
      clients: null,
      tabs: null,
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      axios.get(apiUrl(
        API_URL, 
        this.abstractGroup.year, 
        this.abstractGroup.subject_id
      )).then(r => {
        this.clients = r.data
        this.loading = false
      })
    },

    removeClientFromGroup(client) {
      this.clients.splice(this.clients.findIndex(e => e.id === client.id), 1)
    },
  },

  computed: {
    abstractGroup() {
      return {
        year: parseInt(this.$route.params.year),
        subject_id: parseInt(this.$route.params.subject_id),
      }
    }
  }
}
</script>