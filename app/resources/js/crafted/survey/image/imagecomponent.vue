<script setup>
import { reactive, onMounted } from 'vue'
import { ValidateCollection, imageUrl, setImageSize } from '../../../composables/useSurveyForm.js'
import ImageModal from '../../modal/image/image.vue'
const props = defineProps({
    component: { Type: Array },
    index: { Type: Number },
    formCollection: { Type: Array },
    formSection: { Type: Array },
})

const emit = defineEmits(['imageProperties'])
let initialState = {
    inputfield: null,
    imageCollection: [],
    imagePosition: 'start',
    id : null,
    surveyForm : []
}

let form = reactive({ ...initialState })

function getImageCollection(collection) {
    form.imageCollection = collection
    setImageProperties()
}

function setImageProperties() {
    let validatecollection = ValidateCollection(props.formCollection.formSectionCollection, props.component.id)[0]

    if (validatecollection) {
        validatecollection.text = null
        validatecollection.html = null
        validatecollection.images = form.imageCollection
        validatecollection.image_position = form.imagePosition
        validatecollection.section_stored = form.section_stored
        validatecollection.section_id = form.id
    }
    else {
        props.formCollection.formSectionCollection.push({
            form_id: props.formCollection.form_id,
            id: props.component.id,
            section_id: form.id,
            text: null,
            html: null,
            is_required: false,
            is_multiple_select: false,
            is_question: false,
            images: form.imageCollection,
            image_position: form.imagePosition,
            with_other_option: false,
            options: null,
            option_type: null,
            option_position: null,
            prerequisites: null
        })
    }
    emit("imageProperties", props.formCollection.formSectionCollection);
}

onMounted(() => {
    form.surveyForm = props.formSection
    if(form.surveyForm) {
        form.imagePosition = form.surveyForm.image_position
        form.section_stored = true
        form.id = form.surveyForm.id
        setImageProperties()
    }
})
</script>
<template>
    <div>
        <ImageModal :component="props.component" :index="props.index"
        :formSection="formSection" :key="props.index"
            @ImageCollection="getImageCollection" />
        <div class="row mt-2" v-if="form.imageCollection">
            <div class="row">
                <div class="col">
                    <label>Position</label>
                    <select class="border p-1 outline-none mx-2" v-model="form.imagePosition"
                        @change="setImageProperties()">
                        <option value="start">Left</option>
                        <option value="center">Center</option>
                        <option value="end">Right</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col d-flex" style="flex-flow: row wrap;" :class="'justify-content-' + form.imagePosition">

                    <div :style="{ width: setImageSize(image.width) }" v-for="(image, index) in form.imageCollection"
                        class="mx-1 my-1">
                        <div :style="{ height: setImageSize(image.height) }" class="position-relative">
                            <img :src="imageUrl(image.url)" class="w-100 h-100">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>