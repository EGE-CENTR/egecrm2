export default {
  // static data from Models/Data
  setData(state, data) {
    state.data = data
  },

  setUser(state, user) {
    state.user = user
  },

  toggleDrawer(state, value = null) {
    if (value !== null) {
      state.drawer = value
    } else {
      state.drawer = !state.drawer
    }
    localStorage.setItem('drawer', state.drawer)
  },

  loading(state, value) {
    state.loading = value
  }
}
