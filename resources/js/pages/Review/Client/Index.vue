<template>
  <div>
    <DisplayData 
      ref='DisplayData'
      :api-url='API_URL' 
      :tabs='true'
      :invisible-filters="{client_id: $store.state.user.id}"
    >
      <template slot='items' slot-scope='{ items }'>
        <ReviewClientList :items='items' @updated='() => $refs.DisplayData.loadData()' v-if='items.length > 0' />
        <NoData
          v-else
          transparent
        />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL } from '@/components/Review'
import ReviewClientList from '@/components/Review/Client/List'

export default {
  components: { DisplayData, ReviewClientList },

  data() {
    return {
      API_URL,
    }
  },
  
}
</script>
