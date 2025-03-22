import { defineStore } from 'pinia'

export const useDocumentStore = defineStore('document', {
  state: () => ({
    url: '',
    downloadUrl: '',
    documentData: {},
  }),
  actions: {
    setReviewSrc(src) {
      this.$state.url = src
    },
    setDocument(value) {
      this.$state.documentData = value
    },
    setDownloadUrl(value) {
      this.$state.downloadUrl = value
    },
  },
  getters: {
    reviewSrc() {
      return this.$state.url
    },
    getDocumentData() {
      return this.$state.documentData
    },
    getDownloadUrl() {
      return this.$state.downloadUrl
    },
  },
})
