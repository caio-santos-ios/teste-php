import { createApp } from "vue"
import '@fortawesome/fontawesome-free/css/all.css';
import { createPinia } from 'pinia'

import HomePage from './components/pages/HomePage.vue'
import OwnerPage from './components/pages/OwnerPage.vue'
import VehiclePage from './components/pages/VehiclePage.vue'
import RevisionPage from './components/pages/RevisionPage.vue'


import List from './components/List.vue'
import Dashboard from './components/Dashboard.vue'
import FormRegister from './components/FormRegister/FormRegister.vue'

import FormRegisterOwner from './components/FormRegister/FormRegisterOwner.vue'
import FormUpdateOwner from './components/FormUpdate/FormUpdateOwner.vue'

import FormRegisterVehicle from './components/FormRegister/FormRegisterVehicle.vue'
import FormRegisterVerision from './components/FormRegister/FormRegisterRevision.vue'
import GraphicBar from './components/GraphicBar.vue'
import Report from './components/Report.vue'
import ReportVehicle from './components/ReportVehicle.vue'
import ReportOwner from './components/ReportOwner.vue'
import ReportRevision from './components/ReportRevision.vue'
import Loading from './components/Loading.vue'
import Modal from './components/Modal.vue'
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const app = createApp()
const pinia = createPinia()

app.use(pinia)

app.component('home-page', HomePage)
app.component('owner-page', OwnerPage)
app.component('vehicle-page', VehiclePage)
app.component('revision-page', RevisionPage)

app.component('dashboard', Dashboard)
app.component('list', List)
app.component('form-register-generic', FormRegister)
app.component('form-register-revision', FormRegisterVerision)

app.component('form-register-owner', FormRegisterOwner)
app.component('form-update-owner', FormUpdateOwner)


app.component('form-register-vehicle', FormRegisterVehicle)
app.component('form-update-owner', FormUpdateOwner)
app.component('graphic-bar', GraphicBar)
app.component('report', Report)
app.component('report-vehicle', ReportVehicle)
app.component('report-owner', ReportOwner)
app.component('report-revision', ReportRevision)
app.component('loading', Loading)
app.component('modal', Modal)
app.component('VueDatePicker', VueDatePicker);


app.mount('#app')
