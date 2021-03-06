<template>
  <div>
    <GroupDialog ref='GroupDialog' @updated='loadData' />
    <GroupActDialog v-if='$refs.GroupActPage' ref='GroupActDialog' @updated='$refs.GroupActPage.loadData()' />
    <MoveClientDialog ref='MoveClientDialog' @moved='removeClientFromGroup' />
    
    <div class='headline mb-4 flex-items align-center'>
      Группа {{ $route.params.id }}
      <v-chip 
        readonly
        class='ml-3 no-pointer-events'
        small
        outline 
        :color="item.is_archived ? 'grey' : 'success'" 
        v-if='this.item !== null'>
        {{ item.is_archived ? 'заархивирована' : 'активна' }}</v-chip>
    </div>

    <v-card class='mb-4' :class='config.elevationClass'>
      <v-card-text class='relative card-with-loader'>
        <Loader v-if='loading'></Loader>
        <v-layout wrap v-if='item !== null'>
          <v-flex md12>
            <GroupInfo :item='item' @open='openGroup' />
          </v-flex>
          <v-flex md12 class='mt-5' v-if='item.clients.length'>
            <v-data-table
              class="full-width"
              hide-actions
              hide-headers
              :items='item.clients'
            >
              <template slot='items' slot-scope="props">
                <td width='200'>
                  <router-link :to="{name: 'ClientShow', params: { id: props.item.id }}" 
                    :class="getSubjectStatusClass(props.item.subject_status)"
                  >
                    <PersonName :item='props.item'/>
                  </router-link>
                </td>
                <td width='200'>
                  30%
                </td>
                <td width='200'>
                  смс отправлено
                </td>
                <td width='200'>
                  <BranchList :items='props.item.branches' />
                </td>
                <td class='flex-items align-center' width='500'>
                  <TimelineWeek :items='props.item.schedule' regular />
                  <!-- <Bars :group-bars='item.schedule.bars' :client-bars='props.item.bars' /> -->
                </td>
                <td class='text-md-right' style='padding-right: 16px'>
                  <v-menu>
                    <v-btn slot='activator' flat icon color="black" class='ma-0'>
                      <v-icon>more_horiz</v-icon>
                    </v-btn>
                     <v-list dense>
                        <v-list-tile @click='removeClientFromGroup(props.item.id)'>
                          <v-list-tile-action>
                            <v-icon>close</v-icon>
                          </v-list-tile-action>
                          <v-list-tile-title>Удалить из группы</v-list-tile-title>
                        </v-list-tile>
                        <v-list-tile @click='moveClient(props.item.id)'>
                          <v-list-tile-action>
                            <v-icon>compare_arrows</v-icon>
                          </v-list-tile-action>
                          <v-list-tile-title>Переместить в группу</v-list-tile-title>
                        </v-list-tile>
                     </v-list>
                  </v-menu>
                </td>
              </template>
            </v-data-table>
          </v-flex>
          <NoData :height='160' box class='mt-3' v-else />
        </v-layout>
      </v-card-text>
    </v-card>
    <div v-if='item !== null'>
      <v-tabs fixed-tabs v-model='tabs' class='mb-4'>
        <v-tab>
          Расписание
        </v-tab>
        <v-tab>
          Посещаемость
        </v-tab>
         <v-tab>
          Акты
        </v-tab>
         <v-tab>
          Комментарии
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tabs">
        <v-tab-item>
          <v-card :class='config.elevationClass'>
            <v-card-text class='relative'>
              <GroupSchedule v-if='item !== null' :group='item' />
            </v-card-text>
          </v-card>
        </v-tab-item>
        <v-tab-item>
          <Visits :group='item' />
        </v-tab-item>
        <v-tab-item>
          <DisplayData 
            :api-url='GROUP_ACTS_API_URL' 
            :invisible-filters='{group_id: item.id}' 
            ref='GroupActPage' 
            container-class='py-0 mt-4'
          >
            <template slot='items' slot-scope='{ items }'>
              <GroupActList :items='items' :group-id='item.id' @updated='$refs.GroupActPage.loadData()' />
            </template>
            <template slot='buttons-bottom'>
            </template>
          </DisplayData>
          <!-- <GroupActList :group-id='item.id' /> -->
        </v-tab-item>
        <v-tab-item>
          <v-card :class='config.elevationClass'>
            <v-card-text>
              <Comments :class-name='CLASS_NAME' :entity-id='item.id' />
            </v-card-text>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </div>
  </div>
</template>

<script>

import { 
  API_URL, 
  GROUP_CLIENTS_API_URL, 
  LEVELS, 
  CLASS_NAME,
} from '@/components/Group'

import GroupSchedule from '@/components/Group/Schedule/Schedule'
import MoveClientDialog from '@/components/Group/MoveClientDialog'
import GroupDialog from '@/components/Group/Dialog'

import Bars from '@/components/Group/Bars'
import Visits from '@/components/Group/Visits'
import { DisplayData } from '@/components/UI'
import { API_URL as GROUP_ACTS_API_URL } from '@/components/Group/Act'
import GroupActDialog from '@/components/Group/Act/Dialog'
import GroupActList from '@/components/Group/Act/List'
import Comments from '@/components/Comments'
import { getSubjectStatusClass } from '@/components/Contract'
import BranchList from '@/components/UI/BranchList'
import GroupInfo from '@/components/Group/Info'
import TimelineWeek from '@/components/Timeline/Week'


export default {
  components: { 
    DisplayData, GroupSchedule, Bars, Visits, GroupDialog, MoveClientDialog, GroupActList, 
    GroupActDialog, Comments, BranchList, GroupInfo, TimelineWeek
  },

  data() {
    return {
      LEVELS,
      CLASS_NAME,
      GROUP_ACTS_API_URL,
      loading: true,
      item: null,
      tabs: null,
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    getSubjectStatusClass,

    loadData() {
      axios.get(apiUrl(`${API_URL}/${this.$route.params.id}`)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },

    openGroup(id) {
      this.$refs.GroupDialog.open(id)
    },

    removeClientFromGroup(clientId) {
      this.item.clients.splice(this.item.clients.findIndex(e => e.id === clientId), 1)
      axios.delete(apiUrl(GROUP_CLIENTS_API_URL + `?group_id=${this.item.id}&client_id=${clientId}`))
    },

    moveClient(clientId) {
      this.$refs.MoveClientDialog.open(this.item, clientId)
    },
  },
}
</script>