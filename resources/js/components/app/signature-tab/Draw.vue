<script setup xmlns="http://www.w3.org/1999/html">
import { useSignerStore } from "@/stores/signer";
import { defineProps, onMounted } from "vue";

const props = defineProps({
  shortUrl: {
    type: String,
    required: true,
  },
})

const emit = defineEmits(['alert', 'signature'])

defineExpose({ setSignature })

const signaturePadRef = ref("")

const loadImage = imageUrl => {
  const canvas = signaturePadRef.value.$el.querySelector('canvas')
  const context = canvas.getContext('2d')
  const image = new Image()

  // Function to resize the canvas and draw the image
  const resizeCanvasAndDrawImage = () => {
    const ratio = window.devicePixelRatio || 1

    // Use the actual pixel dimensions
    canvas.width = canvas.clientWidth * ratio
    canvas.height = canvas.clientHeight * ratio

    // Scale the context to match
    context.scale(ratio, ratio);

    // Clear the canvas before redrawing
    context.clearRect(0, 0, canvas.width, canvas.height)

    // Draw the image with its original dimensions
    context.drawImage(image, 0, 0, canvas.clientWidth, canvas.clientHeight)
  }

  image.onload = () => {
    resizeCanvasAndDrawImage();

    const signature = canvas.toDataURL('image/png')

    isLoading.value = false

    if (signature) {
      emit('signature', { signature: signature, type: 'draw' });
    }
  }

  // Set the image source
  image.src = imageUrl

  window.addEventListener('resize', resizeCanvasAndDrawImage);
  window.addEventListener('orientationchange', resizeCanvasAndDrawImage);
}

onMounted(async () => {

  reset()

  isLoading.value = false

  setTimeout(() => {
    signaturePadRef.value.resizeCanvas()
  }, 0)
})

function setSignature(){

  const { isEmpty, data } = signaturePadRef.value.saveSignature()
  if (!isEmpty) {

    const data = removeWhiteSpace()

    emit('signature', { signature: data, type: 'draw' })

  }

}

function removeWhiteSpace()
{
  const canvas = signaturePadRef.value.$el.querySelector('canvas');
  const ctx = canvas.getContext('2d');
  const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
  const data = imgData.data;

  let top = canvas.height, bottom = 0, left = canvas.width, right = 0;

  for (let y = 0; y < canvas.height; y++) {
    for (let x = 0; x < canvas.width; x++) {
      const index = (y * canvas.width + x) * 4;
      const alpha = data[index + 3]; // Alpha channel

      if (alpha > 0) { // Non-transparent pixel
        if (y < top) top = y;
        if (y > bottom) bottom = y;
        if (x < left) left = x;
        if (x > right) right = x;
      }
    }
  }

  // Ensure we have a valid area to crop
  if (right > left && bottom > top) {
    // Draw the bounding box
    // ctx.strokeStyle = 'red';
    // ctx.lineWidth = 2;
    // ctx.strokeRect(left, top, right - left, bottom - top);

    const cropWidth = right - left;
    const cropHeight = bottom - top;

    const croppedCanvas = document.createElement('canvas');
    croppedCanvas.width = cropWidth;
    croppedCanvas.height = cropHeight;
    const croppedCtx = croppedCanvas.getContext('2d');

    croppedCtx.drawImage(canvas, left, top, cropWidth, cropHeight, 0, 0, cropWidth, cropHeight);

    return croppedCanvas.toDataURL()

  } else {
    alert("No signature detected or the image is already cropped.");
  }

}

const reset = () => {
  if (signaturePadRef.value) {
    signaturePadRef.value.clearSignature()
  } else {
    console.log('SignaturePad not found')
  }
}

const undoSignature = () => {
  if (signaturePadRef.value) {
    signaturePadRef.value.undoSignature()
  } else {
    console.log('SignaturePad not found')
  }
}

const saveSignature = () => {
  if (signaturePadRef.value) {
    const { isEmpty, data } = signaturePadRef.value.saveSignature()
    if (!isEmpty) {
      const a = document.createElement("a") //Create <a>

      a.href = data //Image Base64 Goes here
      a.download = "signature.png" //File name Here
      a.click()
    } else {
      console.log('Signature is empty')
    }

  } else {
    console.log("No Signature Found")
  }
}

const isLoading = ref(true)
</script>

<template>
  <VCol
    class="mx-auto"
    cols="12"
    md="7"
  >
    <VAlertTitle class="justify-center justify-md-start">
      <VIcon
        icon="tabler-pencil"
        size="24"
        class="me-2"
      />
      Draw Signature
    </VAlertTitle>
  </VCol>
  <VCol
    class="mx-auto"
    md="7"
    sm="12"
  >
    <AppCardActions
      no-actions
      v-model:loading="isLoading"
      class="mb-6 border"
      title=""
      style="background: lightgrey!important; border-radius: 1rem"
    >
      <VueSignaturePad
        ref="signaturePadRef"
        width="100%"
        height="300px"
        @click="setSignature"
      />
    </AppCardActions>
  </VCol>
  <div class="d-flex gap-4 justify-center mb-2">
    <VBtn
      color="error"
      @click="reset"
    >
      <VIcon
        icon="tabler-refresh"
        size="24"
      />
      <VTooltip
        activator="parent"
        location="bottom"
      >
        Reset
      </VTooltip>
    </VBtn>

    <VBtn @click="saveSignature">
      <VIcon
        icon="tabler-device-floppy"
        size="24"
      />
      <VTooltip
        activator="parent"
        location="bottom"
      >
        Save
      </VTooltip>
    </VBtn>

    <VBtn
      color="warning"
      @click="undoSignature"
    >
      <VIcon
        icon="tabler-rotate-2"
        size="24"
      />
      <VTooltip
        activator="parent"
        location="bottom"
      >
        Undo
      </VTooltip>
    </VBtn>
  </div>
</template>

<style scoped lang="scss">

</style>
