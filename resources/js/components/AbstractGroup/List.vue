<template>
  <div>
    <v-data-table
      :class='config.elevationClass'
      hide-actions
      hide-headers
      :items='items'
      v-if='items.length > 0'
    >
      <template slot='items' slot-scope="{ item }">
        <td>
          {{ getData('subjects', item.subject_id).three_letters }}–{{ getData('grades', item.grade_id).short }}
        </td>
        <td>
          <span v-if='item.year'>
            {{ getData('years', item.year).title }}
          </span>
        </td>
        <td>
          {{ item.clients_count }} учеников
        </td>
        <td class='text-md-right'>
          <router-link :to="{name: 'AbstractGroupShow', params: {
            year: item.year,
            grade_id: item.grade_id,
            subject_id: item.subject_id,
          }}">
            <v-btn flat icon color="black" class='ma-0'>
              <v-icon>more_horiz</v-icon>
            </v-btn>
          </router-link>
        </td>
      </template>
    </v-data-table>
    <NoData
      v-else
      fullheight
      label='нет болот'
    />
  </div>
</template>
<script>

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  data() {
    return {
    }
  },
  
}
</script>