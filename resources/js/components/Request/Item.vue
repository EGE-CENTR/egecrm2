<template>
    <v-card class="mb-3" :class="{
      [config.elevationClass]: true,
      'request_is-current': isCurrent
    }">
      <v-card-text class='relative' style='z-index: 1; background: white'>
        <v-layout row>
          <v-flex style='width: 80%; border-right: 1px solid #9e9e9e'>
            <div class='mb-3'>
              <div class='flex-items'>
                <Avatar :photo='item.createdUser.photo' :size='35' class='mr-3' />
                <div>
                  <div>
                    <b>{{ item.createdUser.default_name }}</b>
                    <span class='d-inline-block ml-1 grey--text'>
                      {{ item.created_at | date-time }}
                    </span>
                    <span class='orange--text  ml-1' v-if='item.get_back_at !== null'>
                      вернуться {{ item.get_back_at | date-time }}
                    </span>
                  </div>
                  <div>
                    <div>{{ item.comment }}</div>
                    <PhoneList :items='item.phones' />
                  </div>
                </div>
              </div>
            </div>
            <Comments class-name='Request' :entity-id='item.id' :items='item.comments' />
          </v-flex>
          <v-flex style='width: 20%' class='ml-3'>
            <div class='mb-3'>
              <div class='item-label'>Статус</div>
              {{ REQUEST_STATUSES.find(e => e.id == item.status).title }}
            </div>
            <div class='mb-3' v-if='item.responsibleAdmin'>
              <div class='item-label'>Ответственный</div>
              {{ item.responsibleAdmin.default_name }}
            </div>
            <div class='mb-3'>
              <div class='item-label'>Филиалы</div>
              <BranchList :items='item.branches' />
            </div>
            <div class='mb-3'>
              <div class='item-label'>Предметы и класс</div>
              <span v-for='(subject_id, index) in item.subjects' :key='subject_id'>{{ getData('subjects', subject_id).three_letters }}{{ index == (item.subjects.length - 1) ? '' : '+' }}</span>
              <span v-if='item.grade_id'>
                ({{ getData('grades', item.grade_id).title }})
              </span>
            </div>
            <div class='mb-3'>
              <div class='item-label'>Клиенты</div>
              <div v-if='item.client_ids.length'>
                <div v-for='client_id in item.client_ids' :key='client_id'>
                  <router-link :to="{ name: 'ClientShow', params: {id: client_id}}">
                    {{ client_id }}
                  </router-link>
                </div>
              </div>
              <div v-else>
                <a @click="$emit('openClientDialog', null, {phones: item.phones})">добавить</a>
              </div>
            </div>
            <div class='mb-3' v-if='item.relative_ids.length'>
              <div class='item-label'>Заявки</div>
              <div>
                <div v-for='id in item.relative_ids' :key='id'>
                  {{ id }}
                  <!-- <router-link :to="{ name: 'RequestShow', params: { id }}">
                    {{ id }}
                  </router-link> -->
                </div>
              </div>
            </div>
            <v-btn flat icon 
              @click="$router.push({name: 'RequestShow', params: { id: item.id }})"
              color="black" class='ma-0 request-button request-button_open'>
              <v-icon>open_in_new</v-icon>
            </v-btn>
            <v-btn flat icon 
              @click="$emit('openDialog', item.id)"
              color="black" class='ma-0 request-button request-button_edit'>
              <v-icon>more_horiz</v-icon>
            </v-btn>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>
</template>

<script>

import { REQUEST_STATUSES } from './'
import Avatar from '@/components/UI/Avatar'
import Comments from '@/components/Comments'
import PhoneList from '@/components/Phone/List'
import BranchList from '@/components/UI/BranchList'

export default {
  components: { Avatar, Comments, PhoneList, BranchList },
  data() {
    return {
      REQUEST_STATUSES
    }
  },
  props: {
    item: {},
    isCurrent: Boolean,
  }
}
</script>

<style scoped lang='scss'>
  .request-info {
    display: flex;
    & > div {
      margin-right: 14px;
    }
  }

  .request {
    &_is-current {
      &:before {
        content: '';
        position: absolute;
        left: -10px;
        top: 20px;
        height: 120px;
        width: 50px;
        border-radius: 10px;
        display: block;
        background: #e06f4a;
        z-index: 1;
      }
    }
  }

  .request-button {
    position: absolute; 
    right: 10px;
    &_edit {
      bottom: 5px; 
    }
    &_open {
      top: 5px;
    }
  }
</style>
