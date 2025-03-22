import { createApp } from 'vue'
import App from '@/App.vue'
import { registerPlugins } from '@core/utils/plugins'
import { createPinia } from 'pinia'
import VuePdf from 'vue3-pdfjs'

// Styles
import '@core-scss/template/index.scss'
import '@styles/styles.scss'
// eslint-disable-next-line import/no-named-as-default
import VueSignaturePad from 'vue-signature-pad'

const pinia = createPinia()

// Create vue app
const app = createApp(App)


// Register plugins
registerPlugins(app)
app.use(VuePdf)
app.use(VueSignaturePad)

// Mount vue app
app.mount('#app')
