<template lang="html">
  <div>
    <DisplayData ref='DisplayData' :api-url='API_URL' :filters='FILTERS' :sort='SORT' :paginate='30'>
      <template slot='items' slot-scope='{ items }'>
        <PaymentList :items='items' 
          v-if='items.length > 0'
          @updated='$refs.DisplayData.reloadData()'
          :display-options="{entity: true, addBtn: false}" />
        <NoData
          v-else
          box
        />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL, FILTERS, SORT, ENUMS } from '@/components/Payment'
import PaymentList from '@/components/Payment/List'

export default {
  components: { DisplayData, PaymentList },

  data() {
    return {
      API_URL,
      ENUMS,
      SORT,
      FILTERS,
    }
  },
  
}
</script>
