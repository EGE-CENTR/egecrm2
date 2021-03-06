<template>
  <div :key='$route.params.id'>
    <div class='headline mb-4 flex-items align-center'>
      Клиент {{ clientId || $route.params.id }}

      <v-chip 
        readonly
        class='ml-3 no-pointer-events'
        small
        outline 
        :color="client.is_banned ? 'grey' : 'success'" 
        v-if='this.client !== null'>
        {{ client.is_banned ? 'архив' : 'активен' }}</v-chip>
    </div>
    <v-card class='mb-4' :class='config.elevationClass'>
      <v-card-text class='relative card-with-loader'>
        <Loader v-if='loading'></Loader>
        <v-layout wrap v-if='client !== null'>
          <v-flex style='width: 650px'>
            <div class='flex-items'>
              <AvatarLoader 
                class='mr-4'
                :entity-type='CLASS_NAME' 
                :item='client' 
              />
              <div>
                <div class='grey--text text--darken-2 font-weight-medium caption'>Ученик</div>
                <div class='font-weight-bold'>
                  {{ client.last_name }}
                  {{ client.first_name }}
                  {{ client.middle_name }}
                  <span v-if='client.grade_id !== null'>({{ getData('grades', client.grade_id).title }})</span>
                </div>
                <div class='mt-4'>
                  <PhoneList :items='client.phones' :block='true' />
                </div>
                <div v-if='client.email'>
                  <EmailShow :item='client.email' />
                </div>
                <div class='grey--text text--darken-2 font-weight-medium caption mt-4'>Представитель</div>
                <div class='font-weight-bold'>
                  {{ client.representative.last_name }}
                  {{ client.representative.first_name }}
                  {{ client.representative.middle_name }}
                </div>
                <div class='mt-4'>
                  <PhoneList :items='client.representative.phones' :block='true' />
                </div>
                <div v-if='client.representative.email'>
                  <EmailShow :item='client.representative.email' />
                </div>
              </div>
            </div>
          </v-flex>
          <v-flex style='width: 400px'>
            <div v-if='client.branches.length'>
              Удобные филиалы: <BranchList :items='client.branches' />
            </div>
            <div class='font-weight-bold my-4'>Паспортные данные представителя</div>
            <div>
              Паспорт: {{ client.representative.number }} {{ client.representative.series }}
            </div>
            <div>
              Паспорт выдан: {{ client.representative.issued_date | date }} {{ client.representative.issued_by }}
            </div>
            <div>
              Код подразделения: {{ client.representative.code || 'не указано' }}
            </div>
            <div>
              Дата рождения: {{ client.representative.birthday | date }}
            </div>
            <div>
              Место прописки: {{ client.representative.address || 'не указано' }}
            </div>
          </v-flex>
          <v-spacer></v-spacer>
          <v-flex class='text-md-right' align-end d-flex>
            <div class='display-flex' style='flex-direction: column; align-items: flex-end'>
              <v-menu left>
                <v-btn slot='activator' flat icon color="black" class='ma-0'>
                  <v-icon>more_horiz</v-icon>
                </v-btn>
                <v-list dense>
                  <v-list-tile @click='$refs.ClientDialog.open(client.id)'>
                    <v-list-tile-action>
                      <v-icon>edit</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>Редактировать</v-list-tile-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-list-tile @click="loading = true; PreviewMode.login(client.id, CLASS_NAME)">
                    <v-list-tile-action>
                      <v-icon>visibility</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>Режим просмотра</v-list-tile-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-list>
              </v-menu>
            </div>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>

    <div v-if='client !== null'>
      <v-tabs fixed-tabs v-model='tabs' class='mb-4'>
        <v-tab>
          Заявки
        </v-tab>
        <v-tab>
          Договоры
        </v-tab>
        <v-tab>
          Расписание
        </v-tab>
        <v-tab>
          Баланс
        </v-tab>
        <v-tab>
          Группы
        </v-tab>
        <v-tab>
          Платежи
        </v-tab>
        <v-tab>
          Допуслуги
        </v-tab>
        <v-tab>
          Комментарии
        </v-tab>
        <v-tab>
          Тесты
        </v-tab>
        <v-tab>
          Отзывы
        </v-tab>
        <v-tab>
          Отчёты
        </v-tab>
        <v-tab>
          Логи
        </v-tab>
      </v-tabs>

      <v-tabs-items v-model="tabs">
        <!-- Заявки -->
        <v-tab-item>
          <DisplayData ref='RequestPage'
            :api-url='REQUEST_API_URL'
            :invisible-filters="{client_id: client.id}"
          >
            <template slot='items' slot-scope='{ items }'>
              <v-layout row wrap class='relative' v-if='items.length > 0'>
                <v-flex xs12 v-for='item in items' :key='item.id'>
                  <RequestItem :item='item' @openDialog='$refs.RequestDialog.open(item.id)' />
                </v-flex>
              </v-layout>
              <NoData 
                v-else
                square
                :add='() => $refs.RequestDialog.open(null, {
                  phones: client.phones,
                })'
                :class='config.elevationClass'
              />
              <!-- <v-data-table :items='[]' hide-actions hide-headers :class='config.elevationClass' v-else>
                <template slot='no-data'>
                  <no-data>
                    <AddBtn label='добавить заявку' @click.native='$refs.RequestDialog.open(null, {
                      phones: client.phones,
                    })' />
                  </no-data>
                </template>
              </v-data-table> -->
            </template>
            <template slot='buttons'>
              <AddBtn label='добавить заявку' animated @click.native='$refs.RequestDialog.open(null, {
                phones: client.phones,
              })' />
            </template>
          </DisplayData>
        </v-tab-item>

        <!-- Договоры -->
        <v-tab-item>
          <DisplayData ref='ContractPage'
            :tabs='true'
            :api-url='CONTRACT_API_URL' 
            :invisible-filters="{client_id: client.id}"
          >
            <template slot='items' slot-scope='{ items }'>
              <ContractList :items='items' :client-id='client.id' @updated='contractsUpdated' />
            </template>
          </DisplayData>
        </v-tab-item>

        <!-- Расписание -->
        <v-tab-item>
          <ClientSchedule :client-id='$route.params.id' />
        </v-tab-item>
        
        <!-- Баланс -->
        <v-tab-item>
          <Balance :entity-id='$route.params.id' :entity-type='CLASS_NAME' />
        </v-tab-item>

        <!-- Группы -->
        <v-tab-item>
          <DisplayData ref='GroupPage'
            :tabs='true'
            :api-url='GROUP_API_URL' 
            :options="{client_id: $route.params.id}"
            :invisible-filters="{client_ids: $route.params.id, abstract: 1}"
          >
            <template slot='items' slot-scope='{ items }'>
              <GroupList :items='items' :abstract='true' @updated='() => $refs.GroupPage.reloadData()' />
            </template>
          </DisplayData>
        </v-tab-item>

        <!-- Платежи -->
        <v-tab-item>
          <DisplayData ref='PaymentPage'
            :tabs='true'
            :api-url='PAYMENT_API_URL' 
            :invisible-filters="{entity_id: $route.params.id, entity_type: CLASS_NAME}"
          >
            <template slot='items' slot-scope='{ items }'>
              <PaymentList 
                :items='items' 
                :model-defaults='{ entity_id: $route.params.id, entity_type: userTypes.client }' 
                @updated='$refs.PaymentPage.loadData()'
              />
            </template>
          </DisplayData>
        </v-tab-item>

        <!--  Допуслуги -->
         <v-tab-item>
           <DisplayData ref='PaymentAdditionalPage'
            :tabs='true'
            :api-url='PAYMENT_ADDITIONAL_API_URL' 
            :invisible-filters="{ entity_id: $route.params.id, entity_type: CLASS_NAME }"
          >
            <template slot='items' slot-scope='{ items }'>
              <PaymentAdditionalList 
                :model-defaults='{ entity_id: $route.params.id, entity_type: userTypes.client }'
                :items='items' 
                @updated='$refs.PaymentAdditionalPage.loadData()'
              />
            </template>
          </DisplayData>
        </v-tab-item>

        <!-- Комментарии -->
        <v-tab-item>
          <v-card :class='config.elevationClass'>
            <v-card-text>
              <Comments :class-name='CLASS_NAME' :entity-id='client.id' />
            </v-card-text>
          </v-card>
        </v-tab-item>

        <!-- Тесты -->
        <v-tab-item>
          <TestAdminClientList :client='client' />
        </v-tab-item>

        <!-- Отзывы -->
        <v-tab-item>
          <DisplayData 
            ref='ReviewPage'
            :api-url='REVIEW_API_URL' 
            :tabs='true'
            :invisible-filters="{client_id: $route.params.id}"
          >
            <template slot='items' slot-scope='{ items }'>
              <ReviewAdminList :display-options="{client: false}" :items='items' @updated='() => $refs.ReviewPage.reloadData()' />
            </template>
          </DisplayData>
        </v-tab-item>

        <!-- Отчёты -->
        <v-tab-item>
          <DisplayData 
            ref='ReportPage'
            :api-url='REPORT_API_URL' 
            :tabs='true'
            :invisible-filters="{client_id: $route.params.id}"
          >
            <template slot='items' slot-scope='{ items }'>
              <ReportList :display-options='{client: false}' :items='items' @updated='() => $refs.ReportPage.reloadData()' />
            </template>
          </DisplayData>
        </v-tab-item>

        <!-- Логи -->
        <v-tab-item>
          <DisplayData 
            :api-url='LOG_API_URL' 
            :invisible-filters="{client_id: $route.params.id}"
          >
            <template slot='items' slot-scope='{ items }'>
              <LogList :items='items' />
            </template>
          </DisplayData>
        </v-tab-item>

      </v-tabs-items>
    </div>
    <ClientDialog ref='ClientDialog' @updated='loadData' />
    <PaymentDialog ref='PaymentDialog' v-if='$refs.PaymentPage' @updated='$refs.PaymentPage.reloadData' />
    <RequestDialog ref='RequestDialog' v-if='$refs.RequestPage' @updated='$refs.RequestPage.loadData' />
    <PaymentAdditionalDialog ref='PaymentAdditionalDialog' />
  </div>
</template>

<script>

import Comments from '@/components/Comments'
import AvatarLoader from '@/components/AvatarLoader'
import { 
  API_URL as PAYMENT_API_URL, 
  SORT as PAYMENT_SORT,
  PaymentDialog, 
  PaymentList
} from '@/components/Payment'
import PhoneList from '@/components/Phone/List'
import EmailShow from '@/components/Email/Show'
import BranchList from '@/components/UI/BranchList'
import { TestAdminClientList } from '@/components/Test'
import { DisplayData } from '@/components/UI'
import { API_URL as GROUP_API_URL } from '@/components/Group'
import GroupList from '@/components/Group/List'

// Логи
import { API_URL as LOG_API_URL } from '@/components/Log'
import LogList from '@/components/Log/List'

// Contracts
import { API_URL as CONTRACT_API_URL } from '@/components/Contract'
import ContractList from '@/components/Contract/List'

import ClientDialog from '@/components/Client/Dialog'
import { API_URL, CLASS_NAME } from '@/components/Client'
import { 
  RequestItem,
  RequestDialog,
  API_URL as REQUEST_API_URL
} from '@/components/Request'
import PreviewMode from '@/other/PreviewMode'
import ClientSchedule from '@/components/Client/Schedule'
import Balance from '@/components/Balance/Balance'


// Reviews
import ReviewAdminList from '@/components/Review/Admin/List'
import { API_URL as REVIEW_API_URL } from '@/components/Review'

// Reports
import ReportList from '@/components/Report/List'
import { API_URL as REPORT_API_URL } from '@/components/Report'

// Payment Additional
import {
  API_URL as PAYMENT_ADDITIONAL_API_URL,
  PaymentAdditionalList,
  PaymentAdditionalDialog,
} from '@/components/Payment/Additional'

export default {
  props: ['clientId'],

  data() {
    return {
      CLASS_NAME,
      GROUP_API_URL,
      CONTRACT_API_URL,
      PAYMENT_API_URL,
      PAYMENT_SORT,
      REQUEST_API_URL,
      REVIEW_API_URL,
      REPORT_API_URL,
      LOG_API_URL,
      PAYMENT_ADDITIONAL_API_URL,
      PreviewMode,
      tabs: null,
      loading: true,
      client: null,
    }
  },

  components: { 
    RequestDialog, RequestItem, Comments, ContractList, GroupList, 
    PaymentList, ClientDialog, PhoneList, BranchList, EmailShow, TestAdminClientList,
    DisplayData, PaymentDialog, ClientSchedule, Balance, ReportList, LogList,
    ReviewAdminList, PaymentAdditionalList, PaymentAdditionalDialog, AvatarLoader
  },

  watch: {
    '$route.params': {
        handler() {
          this.loadData()
        },
        immediate: true,
    }
  },

  methods: {
    loadData() {
      this.client = null
      this.loading = true
      Vue.nextTick(() => {
        axios.get(apiUrl(API_URL, (this.clientId || this.$route.params.id))).then(r => {
          this.client = r.data
          this.loading = false
        })
      })
    },

    contractsUpdated() {
      this.$refs.ContractPage.loadData()
      this.$refs.GroupPage.reloadData()
    },
  },
}
</script>