<script setup>
import { reactive, onMounted } from 'vue'
import { ValidateCollection,setObjectElement,RemoveFromCollection,RemoveObject } from '../../../composables/useSurveyForm.js'
const props = defineProps({
    component: { Type: Array },
    index: { Type: Number },
    formCollection: { Type: Array },
    optionProperty: { Type: Array },
    formSection : { Type: Array },
})

const emit = defineEmits(['DropdownOptionCollection'])

let initialState = {
    text: [],
    addOption: 1,
    dropdownOptionCollection: [],
    close: null,
    id : null
}

let form = reactive({ ...initialState })

function setDropdownProperties(index) {
    let validatecollection = ValidateCollection(form.dropdownOptionCollection, index)[0]
    if (validatecollection) {
        validatecollection.text = form.text[index]
    }
    else {
        form.dropdownOptionCollection.push({
            id: index,
            option_id: form.id,
            text: form.text[index]
        })
    }
}

function saveDropdownOption() {
    emit("DropdownOptionCollection", form.dropdownOptionCollection);
    form.close.click()
}

function RemoveDropdownOption(index, Elementid){
    RemoveObject(Elementid)
    form.dropdownOptionCollection = RemoveFromCollection(form.dropdownOptionCollection,index)
    console.log(form.dropdownOptionCollection)
}
onMounted(() => {
    form.close = setObjectElement('btnClosedropdownModal'+props.index)
    if(props.formSection){
        form.addOption = props.formSection.options.length 
        form.id = null
        props.formSection.options.forEach((item,index) => {
            form.text[index] = item.text
            form.id = item.id
            setDropdownProperties(index)
        });
    }
        emit("DropdownOptionCollection", form.dropdownOptionCollection);
    
})
</script>
<template>
    <div>
        <button class="me-2 outline-none bg-transparent border-0 btn btn-link" title="Add dropdown option"
            data-bs-toggle="modal" :data-bs-target="`#DropdwnModaltaticBackdrop${props.index}`">Add dropdown option
        </button>
        <!-- Modal -->
        <div class="modal fade" :id="'DropdwnModaltaticBackdrop' + props.index" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="DropdwnstaticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticDropdwnBackdropLabel">Add dropdown option</h1>
                    </div>
                    <div class="modal-body" style="max-height: 300px;overflow-y: auto;">
                        <div class="row" v-for="(option, index) in form.addOption" :key="index" :id="'dropdown_'+index+'_'+props.index">
                            <div class="col">
                                <input type="text" class="border-bottom inputfield border-0 outline-none p-2 w-100"
                                    @input="setDropdownProperties(index)" placeholder="Option"
                                    v-model="form.text[index]">
                            </div>
                            <div class="col-2">
                                <button type="button" @click="RemoveDropdownOption(index,'dropdown_'+index+'_'+props.index)"
                                    class="btn-icon d-block me-2 btn-remove-option bg-transparent border-0"><i
                                        class="bi bi-x"></i></button>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <button type="button" class="btn btn-link btn-sm" @click="form.addOption++">Add
                                    option</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            :id="'btnClosedropdownModal'+props.index">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="saveDropdownOption()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>