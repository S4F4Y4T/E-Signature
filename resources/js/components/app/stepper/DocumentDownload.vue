<script setup>
import { useDocumentStore } from "@/stores/document"
import { useSignerStore } from "@/stores/signer"
import { formatDateTime } from '@/utils/formatDateTime'
import { defineProps, ref } from "vue"
import Authenticate from '@images/authenticate.png'

const emit = defineEmits(['alert', 'prevStep'])

const document = useDocumentStore()
const signer = useSignerStore()

let copiedIndex = ref([])
let host = ref(window.appConfig.APP_URL)

const copyToClipboard = async (index, shortUrl) => {
  try {
    if (!navigator.clipboard) {
      // Use polyfill if navigator.clipboard is not available
      await window.clipboard.writeText(shortUrl)
    } else {
      // Use native clipboard API
      await navigator.clipboard.writeText(shortUrl)
    }

    copiedIndex.value.push(index)
    console.log('Text copied to clipboard')
  } catch (err) {
    console.error('Failed to copy text: ', err)
  }
}
</script>

<template>
  <VRow>
    <VCol
      class="mx-auto my-5"
      cols="10"
    >
      <VCard class="my-5">
        <VCardText>
          <span class="preview-link">{{ document.getDocumentData.original_filename }}</span>
          <br>
          <span class="text-h6">Created At <b>{{ formatDateTime(document.getDocumentData.created_at) }}</b></span>

          <VRow class="mt-2">
            <VCol
              sm="12"
              md="6"
              class="d-flex justify-md-start justify-center"
            >
              <a
                :href="document.downloadUrl"
                :download="document.getDocumentData.original_filename"
              >
                <VBtn
                  variant="tonal"
                  color="light"
                >
                  Download
                </VBtn>
              </a>
            </VCol>
            <VCol
              sm="12"
              md="6"
              class="d-flex justify-md-end justify-center"
            >
              <span class="text-h5">{{ document.getDocumentData.total_signed }} / {{ document.getDocumentData.total_signer }} SIGNATURES</span>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>

      <template v-if="signer.all.length > 0">
        <VRow>
          <template
            v-for="(signerValue, index) in signer.all"
            :key="index"
          >
            <VCol cols="12">
              <VAlert
                density="comfortable"
                color="secondary"
                variant="tonal"
                border="start"
                :border-color="signerValue.signed ? 'primary' : 'warning'"
              >
                <VRow>
                  <VCol
                    cols="12"
                    md="6"
                    class="signStatus"
                  >
                    <h4>
                      {{ signerValue.name }}
                    </h4>
                    <span>{{ signerValue.email }}</span>
                  </VCol>
                  <VCol
                    class="text-md-right"
                    cols="12"
                    md="6"
                  >
                    <h4>Signed at</h4>
                    <span class="text-h6">{{ signerValue.signed_time ? formatDateTime(signerValue.signed_time) : 'Not signed yet' }}</span>

                    <h4>Viewed at</h4>
                    <span class="text-h6">{{ signerValue.viewed_time ? formatDateTime(signerValue.viewed_time) : "Not viewed yet" }}</span>
                  </VCol>
                </VRow>
              </VAlert>
            </VCol>

            <VCol
              cols="12"
              class="d-flex justify-space-between align-center"
            >
              <VAlert
                density="comfortable"
                color="secondary"
                variant="tonal"
                class="documentURLCopy w-100">
                <template v-if="signerValue.signed">
                  <h4 class="urlCopyField">
                    {{ document.downloadUrl.length > 40 ? document.downloadUrl + '...' : document.downloadUrl }}
                    <VTooltip
                      activator="parent"
                      location="bottom"
                    >
                      {{ document.downloadUrl }}
                    </VTooltip>
                  </h4>
                </template>
                <template v-else>
                  <h4 class="urlCopyField">
                    {{ (host + signerValue.short_url).length > 40 ? (host + signerValue.short_url) + '...' : (host + signerValue.short_url) }}
                    <VTooltip
                      activator="parent"
                      location="bottom"
                    >
                      {{ host + signerValue.short_url }}
                    </VTooltip>
                  </h4>
                </template>
                <VBtn
                  :ref="'copy_' + index"
                  class="documentURLCopyBtn"
                  color="secondary"
                  @click="copyToClipboard(index, signerValue.signed ? document.downloadUrl : host + signerValue.short_url)"
                >
                  <VIcon
                    :icon="copiedIndex.includes(index) ? 'tabler-copy-check' : 'tabler-copy'"
                    size="20"
                    class="flip-in-rtl"
                  />
                  <VTooltip
                    activator="parent"
                    location="bottom"
                  >
                    {{ copiedIndex.includes(index) ? 'copied' : 'copy' }}
                  </VTooltip>
                </VBtn>
              </VAlert>
            </VCol>
          </template>
        </VRow>
      </template>
    </VCol>
    <VDivider />
  </VRow>
</template>

<style scoped lang="scss">
.documentURLCopyBtn{
  position: absolute;
  top: 0;
  right: 0;
  border-radius: 0px 5px 5px 0px;
  //background: #fff;
  cursor: pointer;
  height: 100% !important;
}
.documentURLCopy{
  padding: 12px 14px;
  font-size: 14px;
  color: #000;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.signStatus{
  padding-left: 15px;
}

.urlCopyField{
  white-space: nowrap;
}
.preview-link {
  font-size: 17px; /* Desktop view font size */
  font-weight: bold; /* Make the text bold */

  @media (max-width: 768px) {
    font-size: 16px; /* Mobile view font size */
  }
}
</style>
