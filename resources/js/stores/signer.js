import { defineStore } from 'pinia'

export const useSignerStore = defineStore('signer', {
  state: () => ({
    name: '',
    email: '',
    otp: '',
    signers: {},
  }),
  actions: {
    setSigner(data) {
      this.$state.name = data.name
      this.$state.email = data.email
      this.$state.type = data.type
      this.$state.otp = data.otp
      this.$state.shortUrl = data.shortUrl
    },
    setAllSigner(data){
      this.$state.signers = data
    },
  },
  getters: {
    data() {
      return { name: this.$state.name, email: this.$state.email, otp: this.$state.otp, shortUrl: this.$state.shortUrl, type: this.$state.type }
    },
    all(){
      return this.$state.signers
    },
  },
})
