<template>
  <div>
    <div class='headline'>
      Макросы
    </div>
    <DisplayData :api-url='API_URL' :paginate='1'>
      <template slot='items' slot-scope='{ items }'>
        <MacroList :items='items' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL, MacroList } from '@/components/Macro'

export default {
  components: { DisplayData, MacroList },

  data() {
    return {
      API_URL,
    }
  },
}
</script>
