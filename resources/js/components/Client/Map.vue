<template>
  <v-layout row justify-center>
    <v-dialog persistent v-model="dialog" max-width="1200px">
      <v-card>
        <v-card-text class='pa-0'>
          <GmapMap ref='map' @click='mapClick'
              :center="{lat: 55.7387, lng: 37.6032}"
              :zoom="12"
              :options="{
                disableDefaultUI: true
              }"
              style="width: 100%; height: 700px"
            >
            <GmapMarker
              v-for="(m, index) in items"
              :key="index"
              :position="{lat: m.lat, lng: m.lng}"
              :clickable="true"
              @dblclick='deleteMarker(index)'
            />
          </GmapMap>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
export default {
  props: {
    items: {
      type: Array,
      required: true
    },
    editable: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      dialog: false
    }
  },
  methods: {
    openMap() {
      this.dialog = true
      setTimeout(() => {
        Vue.$gmapDefaultResizeBus.$emit('resize')
      }, 1000)
    },
    deleteMarker(index) {
      if (this.editable) {
        this.items.splice(index, 1)
      }
    },
    mapClick(event) {
      if (this.editable) {
        this.items.push({
          lat: event.latLng.lat(),
          lng: event.latLng.lng()
        })
      }
    },
  }
}
</script>
