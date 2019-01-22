---
to: resources/js/pages/<%= Name %>/Index.vue
---
<template>
  <div>
    <% if (typeof(dialog) != "undefined") { %>
    <<%= Name %>Dialog ref='<%= Name %>Dialog' />
    <% } %>
    <IndexPage :api-url='API_URL' :filters='FILTERS'>
      <% if (typeof(dialog) != "undefined") { %>
      <template slot='buttons'>
        <AddBtn label='добавить' @click.native='$refs.<%= Name %>Dialog.open(null)' />
      </template>
      <% } %>
      <template slot='items' slot-scope='{ items }'>
        <<%= Name %>List :items='items' />
      </template>
    </IndexPage>
  </div>
</template>

<script>

import { IndexPage } from '@/components/UI'
import { API_URL, FILTERS, <%= Name %>List<%= (typeof(dialog) != "undefined") ? (', ' + Name + 'Dialog') : '' %> } from '@/components/<%= Name %>'

export default {
  components: { IndexPage, <%= Name %>List<%= (typeof(dialog) != "undefined") ? (', ' + Name + 'Dialog') : '' %> },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
