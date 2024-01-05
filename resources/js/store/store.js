import { createStore } from "vuex";

export default createStore({
    state: {
        owner: {},
        revision: [],
        isRegisted: false,
        idVehicle: null,
    },
    mutations: {
        selectedOwner(state, newOwner) {
            state.owner = newOwner
        },
        setRegister(state) {
            state.isRegisted = !state.isRegisted
        },
        setIdVehicle(state, id){
            state.idVehicle = id
        },
        setRevision(state, value){
            state.revision = value
        }
    },
    getters: {
        myOwner: (store) => store.owner,
        myRegister: (store) => store.isRegisted,
        myIdVehicle: (store) => store.idVehicle,  
        myRevision: (store) => store.revision
    }
})