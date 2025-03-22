<script setup>
import html2canvas from "html2canvas"

const emit = defineEmits(['signature'])

defineExpose({ setSignature })

const  typeSignature = ref('')
const signatureName = ref('')
const selectedFont = ref('Dancing Script')
const fontOptions = ref(['Dancing Script', 'Pacifico', 'Kaushan Script', 'Great Vibes'])

async function setSignature(){

  console.log(typeSignature.value)

  const typeSignCanvas = await html2canvas(typeSignature.value, { backgroundColor: null })

  emit('signature', { signature: typeSignCanvas.toDataURL('image/png'), type: 'type' })
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
        icon="tabler-pencil"
        size="24"
        class="me-2"
      />
      Type Signature
    </VAlertTitle>
  </VCol>
  <VCol
    class="mx-auto"
    lg="7"
    md="12"
  >
    <VRow class="mb-2">
      <VCol sm="12" md="7">
        <AppTextField
          v-model="signatureName"
          label=""
          maxlength="30"
          placeholder="Typer Your Name"
        />
      </VCol>
      <VCol sm="12" md="5">
        <VSelect
          v-model="selectedFont"
          :items="fontOptions"
          label=""
          placeholder="Select Font"
        />
      </VCol>
    </VRow>
    <span style="overflow-x: auto; display: block; width: 100%;">
      <div
            class="mb-6 border"
            title="Signature"
            style="position:relative; background: lightgrey!important; border-radius: 1rem; height: 250px; min-width: 400px;"
          >
      <VRow>
        <VCol
          style="margin:10px;position: absolute; bottom: 20px;"
          cols="12"
        >
          <VIcon
            icon="tabler-pencil"
            class="mb-2 font-bold"
            style="font-size: 30px;color:#7367F0"
          />

          <div style="margin-top:-15px;margin-left: 35px;border-bottom: 3px solid #7367F0" />
        </VCol>

        <div style="position: absolute; bottom:50px; left:50%; transform: translateX(-50%)">
          <span
            ref="typeSignature"
            style="font-size:35px; color:black; background-color:transparent"
            :style="{ fontFamily: selectedFont }"
          > {{ signatureName }} </span>
        </div>
      </VRow>
    </div>
    </span>
  </VCol>
</template>
