import { createStore } from "vuex";

export default createStore({
    state: {
        owners: [],
        revision: [],
        isRegisted: false,
        idVehicle: null,
        isOpenForm: false,
        isOpenModal: false
    },
    mutations: {
        selectedOwner(state, newOwner) {
            state.owners.push(newOwner)
        },
        clearListOwners(state){
            state.owners = []
        },
        setRegister(state) {
            state.isRegisted = !state.isRegisted
        },
        setIdVehicle(state, id){
            state.idVehicle = id
        },
        setRevision(state, value){
            state.revision = value
        }, 
        setIsOpenForm(state){
            state.isOpenForm = !state.isOpenForm
        },
        setIsOpenModal(state){
            state.isOpenModal = !state.isOpenModal
        }
    },
    getters: {
        myOwners: (store) => store.owners,
        myRegister: (store) => store.isRegisted,
        myIdVehicle: (store) => store.idVehicle,  
        myRevision: (store) => store.revision,
        myIsOpenForm: (store) => store.isOpenForm,
        myIsOpenModal: (store) => store.isOpenModal
    }
})