<template>
  <div>
    <DisplayData 
      :tabs='true'
      :api-url='API_URL' 
      :paginate='30' 
      :options="{client_id: $store.state.user.id}"
      :invisible-filters="{client_ids: $store.state.user.id}"
    >
      <template slot='items' slot-scope="{ items }">
        <GroupList :items='items' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import DisplayData from '@/components/UI/DisplayData'
import { API_URL } from '@/components/Group'
import GroupList from '@/components/Group/List'

export default {
  components: { DisplayData, GroupList },
  
  data() {
    return {
      API_URL,
    }
  },
}
</script>
