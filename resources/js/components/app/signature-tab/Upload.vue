<script setup>
import { Cropper } from "vue-advanced-cropper"
import "vue-advanced-cropper/dist/style.css"

import { ref, onMounted, onUnmounted } from "vue"

const emit = defineEmits(['signature'])

defineExpose({ setSignature })

const uploadSignature = reactive({
  src: "https://thumbs.dreamstime.com/b/transparent-seamless-pattern-background-checkered-simulation-alpha-channel-png-wallpaper-empty-gird-grid-vector-illustration-308566526.jpg\n",
  type: "",
})

const cropper = ref()

const handleSignatureChange = event => {
  /// Reference to the DOM input element
  const { files } = event.target
  if (files && files[0]) {
    // 1. Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
    if (uploadSignature.src) {
      URL.revokeObjectURL(uploadSignature.src)
    }
    const blob = URL.createObjectURL(files[0])

    // 3. Update the image. The type will be derived from the extension and it can lead to an incorrect result:
    uploadSignature.src = blob
    uploadSignature.type = files[0].type

    setSignature()
  } else {
    console.log('unselected')
    uploadSignature.src = ""
    uploadSignature.type = ""
  }
}

onUnmounted(() => {
  if (uploadSignature.src) {
    URL.revokeObjectURL(uploadSignature.src)
  }
})

onMounted(() => {
  setSignature()
})

async function setSignature(){
  const { canvas: uploadSignature } = cropper.value.getResult()

  emit('signature', { signature: uploadSignature?.toDataURL(), type: 'upload' })
}
</script>

<template>
  <VCol
    class="mx-auto"
    md="7"
    cols="12"
  >
    <VAlertTitle class="justify-center justify-md-start">
      <VIcon
        icon="tabler-upload"
        size="24"
        class="me-2"
      />
      Upload Signature
    </VAlertTitle>
  </VCol>
  <VCol
    class="mx-auto"
    md="7"
    cols="12"
  >
    <VFileInput
      id="fileSignature"
      label=""
      accept="png|jpeg|jpg"
      placeholder=""
      prepend-icon="tabler-camera"
      class="mb-4"
      @change="handleSignatureChange"
    />
    <Cropper
      v-if="uploadSignature?.src"
      ref="cropper"
      class="cropper"
      :src="uploadSignature?.src"
      @click="setSignature"
    />
  </VCol>
</template>

<style scoped lang="scss">

</style>
