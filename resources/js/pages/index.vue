<script setup>
import Footer from "@/components/app/Footer.vue"
import DocumentDownload from "@/components/app/stepper/DocumentDownload.vue"
import DocumentReview from "@/components/app/stepper/DocumentReview.vue"
import UserSignature from "@/components/app/stepper/UserSignature.vue"
import UserValidation from "@/components/app/stepper/UserValidation.vue"
import AppStepper from "@core/components/AppStepper.vue"

import Verification from '@images/svg/wizard-account.svg'
import Review from '@images/svg/wizard-address.svg'
import Signature from '@images/svg/wizard-personal.svg'
import Download from '@images/svg/wizard-social-link.svg'

import { ref } from "vue"

definePage({ meta: { layout: 'blank' } })

const route = useRoute()
const shortUrl = ref(route.params.id)

const iconsSteps = [
  {
    title: 'Verification',
    icon: Verification,
  },
  {
    title: 'Review',
    icon: Review,
  },
  {
    title: 'Signature',
    icon: Signature,
  },
  {
    title: 'Download',
    icon: Download,
  },
]

const currentStep = ref(0)
const isCurrentStepValid = ref(true)

let alertShow = ref(null)
let alertMsg = ref(null)
let alertType = ref('error')

const handleAlert = response => {

  const data = response.data
  const type = response.type
  const custom = response?.custom

  alertShow.value = true
  alertType.value = type

  if(type === 'success' || custom === true)
  {
    alertMsg.value = data
  }else {
    if (typeof data?.response?._data?.message === 'object' && data?.response?._data?.message !== null) {
      let message = ''

      for (const key in data.response._data.message) {
        if (data.response._data.message.hasOwnProperty(key)) {
          message += `${data.response._data.message[key]}\n`
        }
      }

      alertMsg.value = message.trim()
    } else {
      alertMsg.value = data.response._data.message
    }
  }
}

//change step
const nextStep = step => {
  currentStep.value = currentStep.value + step
}

const prevStep = step => {
  currentStep.value = currentStep.value - step
}

const documentReviewKey = ref(0)
const UserSignatureKey = ref(0)
const currentStepperStep = ref(0)

watch(currentStep, newStep => {
  if (newStep === 1 || newStep === 2) {
    documentReviewKey.value++
  }

  currentStepperStep.value = newStep
})
</script>

<template>
  <VContainer class="v-container">
    <VCard>
      <VRow>
        <VCardText>
          <!-- 👉 Stepper -->
          <AppStepper
            v-model:current-step="currentStep"
            :items="iconsSteps"
            :is-active-step-valid="isCurrentStepValid"
            align="center"
          />
        </VCardText>
      </VRow>
      <VDivider />
    </VCard>
    <VSnackbar
      v-model="alertShow"
      location="top end"
      variant="flat"
      :color="alertType"
    >
      {{ alertMsg }}
    </VSnackbar>
    <div :class="{ 'step-rule': currentStep === 0 }">
      <VCard>
        <!-- 👉 stepper content -->
        <VWindow
          v-model="currentStep"
          disabled
        >
          <!-- 👉 name email step -->
          <VWindowItem>
            <UserValidation
              :short-url="shortUrl"
              @next-step="nextStep"
              @alert="handleAlert"
            />
          </VWindowItem>
          <!-- 👉 end name email step -->

          <!-- 👉 Document preview step -->
          <VWindowItem>
            <DocumentReview
              :key="documentReviewKey"
              :current-step="currentStepperStep"
              @prev-step="prevStep"
              @next-step="nextStep"
            />
          </VWindowItem>
          <!-- 👉 End Document preview step -->

          <!-- 👉 signature step -->
          <VWindowItem>
            <UserSignature
              :key="UserSignatureKey"
              :short-url="shortUrl"
              @prev-step="prevStep"
              @next-step="nextStep"
              @alert="handleAlert"
            />
          </VWindowItem>
          <!-- 👉 end signature step -->

          <!-- 👉 Download step -->
          <VWindowItem>
            <DocumentDownload
              @prev-step="prevStep"
              @alert="handleAlert"
            />
          </VWindowItem>
          <!-- 👉 End Download step -->
        </VWindow>
      </VCard>
      <Footer />
    </div>
  </VContainer>
  <!-- 👉 Footer -->
</template>

<style  lang="scss">
@use "@core-scss/template/mixins" as templateMixins;
.v-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  min-height: 100vh;
}
</style>

