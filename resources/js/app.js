import { createApp } from "vue"
import '@fortawesome/fontawesome-free/css/all.css';

import HomePage from './components/pages/HomePage.vue'
import OwnerPage from './components/pages/OwnerPage.vue'


import ListOwner from './components/ListOwner.vue'
import Dashboard from './components/Dashboard.vue'
import FormRegister from './components/FormRegister/FormRegister.vue'
import FormRegisterOwner from './components/FormRegister/FormRegisterOwner.vue'
import FormRegisterVehicle from './components/FormRegister/FormRegisterVehicle.vue'
import FormRegisterVerision from './components/FormRegister/FormRegisterRevision.vue'
import GraphicBar from './components/GraphicBar.vue'
import Report from './components/Report.vue'
import store from "./store/store"
import ReportVehicle from './components/ReportVehicle.vue'
import ReportOwner from './components/ReportOwner.vue'
import ReportRevision from './components/ReportRevision.vue'
import Loading from './components/Loading.vue'
import ModalCreate from './components/ModalCreate.vue'

const app = createApp()

app.use(store);

app.component('home-page', HomePage)
app.component('owner-page', OwnerPage)

app.component('dashboard', Dashboard)
app.component('list-owner', ListOwner)
app.component('form-register-generic', FormRegister)
app.component('form-register-revision', FormRegisterVerision)
app.component('form-register-owner', FormRegisterOwner)
app.component('form-register-vehicle', FormRegisterVehicle)
app.component('graphic-bar', GraphicBar)
app.component('report', Report)
app.component('report-vehicle', ReportVehicle)
app.component('report-owner', ReportOwner)
app.component('report-revision', ReportRevision)
app.component('loading', Loading)
app.component('modal-create', ModalCreate)

app.mount('#app')
