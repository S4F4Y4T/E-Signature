<script setup>
const props = defineProps({
  selectedRadio: {
    type: String,
    required: true,
  },
  signatureHistory: {
    type: [Array, Object],
    required: true,
  },
  gridColumn: {
    type: null,
    required: false,
  },
})

const emit = defineEmits(['update:selectedRadio', 'onChange'])

const updateSelectedOption = value => {
  if (value !== null)
    emit('update:selectedRadio', value)
    emit('onChange', value)
}
</script>

<template>
  <VRadioGroup
    v-if="props.signatureHistory"
    :model-value="props.selectedRadio"
    class="custom-input-wrapper"
    @update:model-value="updateSelectedOption"
  >
    <VRow>
      <template v-if="props.signatureHistory.length > 0">
        <VCol
          v-for="item in props.signatureHistory"
          :key="item.id"
          v-bind="gridColumn"
        >

          <VLabel
            class="custom-input custom-radio-icon rounded cursor-pointer"
            :class="props.selectedRadio === item.value ? 'active' : ''"
          >
            <slot :item="item">
              <div class="d-flex flex-column align-center text-center gap-2">
                <img style="max-height: 120px; min-height: 120px" :src="item.public_signature">
              </div>
            </slot>

            <div>
              <VRadio :value="item.signature" />
            </div>
          </VLabel>
        </VCol>
      </template>
      <VCol v-else v-bind="12">
        <VAlert
          border="start"
          border-color="warning"
        >
          No Signature Found
        </VAlert>
      </VCol>
    </VRow>
  </VRadioGroup>
</template>

<style lang="scss" scoped>
.custom-radio-icon {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;

  .v-radio {
    margin-block-end: -0.5rem;
  }
}
</style>

<style lang="scss">
.custom-radio-icon {
  .v-radio {
    margin-block-end: -0.25rem;

    .v-selection-control__wrapper {
      margin-inline-start: 0;
    }
  }
}
</style>
