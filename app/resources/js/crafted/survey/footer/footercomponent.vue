<script setup>
import { reactive, onMounted } from 'vue'
import Prerequisite from '../../modal/prerequisite/prerequisite.vue'
import { setBolean  } from '../../../composables/useSurveyForm';
const props = defineProps({
    component: { Type: Array },
    index: { Type: Number },
    formCollection: { Type: Array },
    formSection: { Type: Array },
})

const emit = defineEmits(['formProperties','PrerequisiteCollection'])

let initialState = {
    is_required: false,
    is_multiple_select: false,
    remove_form_section: false
}

let form = reactive({ ...initialState })

function setFormProperties(switchName) {
    if (switchName === 'required') {
        form.is_required = !form.is_required
    }
    else {
        form.is_multiple_select = !form.is_multiple_select
    }
    emit("formProperties", form);
}

function removeFormSection() {
    form.remove_form_section = true
    setFormProperties()
}

function getPrerequisiteCollection(collection){
    emit("PrerequisiteCollection", collection);
}

onMounted(() => {
    if (props.formSection) {
        form.is_required = setBolean(props.formSection.is_required)
        form.is_multiple_select = setBolean(props.formSection.is_multiple_select)
    }
})

</script>
<template>
    <div class="footer">
        <div class="row">
            <div class="col">
                <div class="d-flex" v-if="props.component.component === 1">
                    <Prerequisite :key="props.index" :component="props.component" :index="props.index" :formCollection="props.formCollection"
                    @PrerequisiteCollection="getPrerequisiteCollection"/>
                </div>
            </div>
            <div class="col">
                <div class="d-flex justify-content-end" v-if="props.component.component === 1">
                    <div class="form-check form-switch p-0 d-flex mt-2 mx-3">
                        <label for="flexSwitchCheckDefault"
                            class="form-check-label me-2 cursor-pointer">Required</label>
                        <input type="checkbox" role="switch" id="flexSwitchCheckDefault" v-model="form.is_required"
                            :value="form.is_required" class="form-check-input cursor-pointer"
                            @click="setFormProperties('required')" style="margin-top:3px!important">
                    </div>
                    <div class="form-check form-switch p-0 d-flex mt-2 mx-4">
                        <label for="flexSwitchCheckDefault" class="form-check-label me-2 cursor-pointer">Multiple
                            Select</label>
                        <input type="checkbox" role="switch" id="flexSwitchCheckDefault"
                            v-model="form.is_multiple_select" :value="form.is_multiple_select"
                            class="form-check-input cursor-pointer" @click="setFormProperties('multi_select')"
                            style="margin-top:3px!important">
                    </div>
                </div>
            </div>
            <div class="col-1">
                <button class="bg-transparent border-0" @click="removeFormSection()">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>
        </div>
    </div>
</template>