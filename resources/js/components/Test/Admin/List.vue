<template>
  <div>
    <div class='flex-items' v-show='items.length > 0'>
      <v-spacer></v-spacer>
      <AddBtn class='mr-2' @click.native='$refs.TestIntroTextDialog.open()' label='редактировать вступительный текст' icon='format_align_left' />
      <AddBtn @click.native='$refs.TestDialog.open()' animated label='добавить тест' />
    </div>

    <TestDialog ref='TestDialog' @updated='loadData' />
    <TestIntroTextDialog ref='TestIntroTextDialog' />

    <Loader v-if='loading' />
    <div v-else>
      <v-data-table v-if='items.length > 0' hide-headers hide-actions :items='items' class='mt-3' :class="config.elevationClass">
        <template slot='items' slot-scope="{ item }">
          <td>
            <a @click='$refs.TestDialog.open(item.id)'>{{ item.title }}</a>
          </td>
          <td>
            <span v-if='item.subject_id'>
              {{ getData('subjects', item.subject_id).three_letters }}
            </span>
          </td>
          <td>
            <span v-if='item.grade_id'>
              {{ getData('grades', item.grade_id).title }}
            </span>
          </td>
          <td>
            {{ item.problems_count  }} вопросов
          </td>
          <td>
            {{ item.minutes }} минут
          </td>
        </template>
      </v-data-table>
      <NoData 
        class='mt-3'
        label='тестов нет'
        fullheight
        v-else>
        <div class='flex-items'>
          <v-btn class='mr-2' icon flat color='primary' @click='$refs.TestIntroTextDialog.open()'>
            <v-icon>format_align_left</v-icon>
          </v-btn>
          <v-btn icon flat color='primary' @click='$refs.TestDialog.open(null)'>
            <v-icon>add</v-icon>
          </v-btn>
        </div>
      </NoData>
    </div>
  </div>
</template>

<script>

import { API_URL, TestDialog, TestIntroTextDialog } from '@/components/Test'

export default {

  components: { TestDialog, TestIntroTextDialog },
  
  data() {
    return {
      loading: false,
      items: [],
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      this.loading = true
      axios.get(apiUrl(API_URL)).then(r => {
        this.items = r.data
        this.loading = false
      })
    },
  },
}
</script>