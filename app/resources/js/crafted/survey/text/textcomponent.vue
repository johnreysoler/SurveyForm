<script setup>
import { reactive, onMounted } from 'vue'
import { ValidateCollection, setObjectElement } from '../../../composables/useSurveyForm.js'
const props = defineProps({
    component: { Type: Array },
    index: { Type: Number },
    formCollection: { Type: Array },
    formSection:{ Type: Array },
})
const emit = defineEmits(['textProperties'])

let initialState = {
    inputfield: null,
    id : null
}

let form = reactive({ ...initialState })

function setTextProperties() {
    let validatecollection = ValidateCollection(props.formCollection.formSectionCollection,props.component.id)[0]

    if (validatecollection) {
        validatecollection.text = form.inputfield.textContent
        validatecollection.html = form.inputfield.innerHTML
        validatecollection.section_id = form.id
    }
    else {
        props.formCollection.formSectionCollection.push({
            form_id: props.formCollection.form_id,
            id: props.component.id,
            section_id : form.id,
            text: form.inputfield.textContent,
            html: form.inputfield.innerHTML,
            is_required: false,
            is_multiple_select: false,
            is_question: false,
            images: null,
            image_position: null,
            with_other_option: false,
            options: null,
            option_type: null,
            option_position: null,
            prerequisites: null,
        })
    }
    emit("textProperties", props.formCollection.formSectionCollection);
}

onMounted(() => {
    form.inputfield = setObjectElement('inputfield' + props.index)
    if(props.formSection){
        form.inputfield.innerHTML = props.formSection.html
        form.id = props.formSection.id
        setTextProperties()
    }
})

</script>
<template>
    <div>
        <div contenteditable="true" class="border w-100 outline-none p-2 inputfield" placeholder="Type something..."
            style="height: 250px;overflow-y:scroll" :id="'inputfield' + props.index" @input="setTextProperties()"></div>
    </div>
</template>