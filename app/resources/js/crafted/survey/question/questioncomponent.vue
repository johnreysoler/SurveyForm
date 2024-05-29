<script setup>
import { reactive, onMounted } from 'vue'
import { ValidateCollection, imageUrl, setImageSize, setObjectElement,RemoveFromCollection,RemoveObject } from '../../../composables/useSurveyForm.js'
import ImageModal from '../../modal/image/image.vue'
import optionComponent from './option/option.vue'
import textComponent from './option/text.vue'
const props = defineProps({
    component: { Type: Array },
    index: { Type: Number },
    formCollection: { Type: Array },
    form: { Type: Array },
    formSection: { Type: Array },
})

const emit = defineEmits(['questionProperties'])

let initialState = {
    inputfield: null,
    imageCollection: [],
    imagePosition: 'start',
    OptionType: 1,
    OptionTypeProperty: [],
    optionVerticalAlign: 'block',
    addOption: 1,
    addOtherOption: false,
    optionCollection: [],
    id: null,
    surveyForm: []
}

let initialOpitionState = {
    optionTypes: [],
}

let form = reactive({ ...initialState })
let options = reactive({ ...initialOpitionState })
function setQuestionProperties() {
    let validatecollection = ValidateCollection(props.formCollection.formSectionCollection, props.component.id)[0]
    if (validatecollection) {
        validatecollection.text = form.inputfield.textContent
        validatecollection.html = form.inputfield.innerHTML
        validatecollection.images = form.imageCollection
        validatecollection.image_position = form.imagePosition
        validatecollection.with_other_option = form.addOtherOption
        validatecollection.options = form.optionCollection
        validatecollection.option_position = form.optionVerticalAlign
        validatecollection.prerequisites = props.form.prerequisiteCollection
        validatecollection.is_required = props.form.is_required
        validatecollection.is_multiple_select = props.form.is_multiple_select
        validatecollection.option_type = form.OptionTypeProperty
        validatecollection.section_id = form.id
    }
    else {
        if (form.inputfield !== null) {
            props.formCollection.formSectionCollection.push({
                form_id: props.formCollection.form_id,
                id: props.component.id,
                section_id: form.id,
                text: form.inputfield.textContent,
                html: form.inputfield.innerHTML,
                is_required: props.form.is_required,
                is_multiple_select: props.form.is_multiple_select,
                is_question: true,
                images: form.imageCollection,
                image_position: form.imagePosition,
                with_other_option: form.addOtherOption,
                options: form.optionCollection,
                option_type: form.OptionTypeProperty,
                option_position: form.optionVerticalAlign,
                prerequisites: props.form.prerequisiteCollection
            })
        }
    }
    emit("questionProperties", props.formCollection.formSectionCollection);
}

function getImageCollection(collection) {
    form.imageCollection = collection
    setQuestionProperties()
}

function getDropdownOption(collection) {
    form.optionCollection = collection
    setQuestionProperties()
}

function fetchOptionType() {
    axios.get('/option-type').then(function (response) {
        options.optionTypes = response.data
        getOptionTypeProperty()
    })
}

function getOptionText(text, index,id) {
    let validatecollection = ValidateCollection(form.optionCollection, index)[0]
    if (validatecollection) {
        validatecollection.text = text
    }
    else {
        form.optionCollection.push({
            id: index,
            option_id: id,
            text: text
        })
    }
    setQuestionProperties()
}
function getOptionTypeProperty() {
    form.OptionTypeProperty = ValidateCollection(options.optionTypes, form.OptionType)[0]
    setQuestionProperties()
}

function AddOtherOption(boolean) {
    form.addOtherOption = boolean
    setQuestionProperties()
}

function RemoveOption(index,Elementid){
    form.optionCollection = RemoveFromCollection(form.optionCollection,index)
    RemoveObject(Elementid);
    setQuestionProperties()
}
onMounted(() => {
    form.inputfield = setObjectElement('inputfield' + props.index)
    form.surveyForm = props.formSection
    if (form.surveyForm) {
        form.inputfield.innerHTML = form.surveyForm.html
        form.OptionType = form.surveyForm.option_types.id
        form.imagePosition = form.surveyForm.image_position
        form.optionVerticalAlign = form.surveyForm.option_vertical_align
        form.addOption = form.surveyForm.options.length
        form.id = form.surveyForm.id
        AddOtherOption(form.surveyForm.with_other_option)
    }
    fetchOptionType()
})
</script>
<template>
    <div class="row">
        <div class="col">
            <div class="row">
                <div class="col">
                    <div contenteditable="true" class="border-bottom w-100 outline-none inputfield px-2 py-1"
                        :id="'inputfield' + props.index" placeholder="Untitled Question"
                        @input="setQuestionProperties()">
                    </div>
                </div>
                <div class="col-2 d-flex" style="height: fit-content">
                    <ImageModal :component="props.component" :index="props.index"
                    :formSection="formSection" :key="props.index"
                        @ImageCollection="getImageCollection" />
                    <select class="border p-1 w-100 outline-none" v-model="form.OptionType"
                        @change="getOptionTypeProperty()">
                        <option v-for="(option, index) in options.optionTypes" :key="index" :value="option.id">{{
                            option.name }}</option>
                    </select>
                </div>
            </div>
            <div class="row mt-2" v-if="form.imageCollection.length > 0">
                <div class="row">
                    <div class="col-10">
                        <label>Position</label>
                        <select class="border p-1 outline-none mx-2" v-model="form.imagePosition"
                            @change="setQuestionProperties()">
                            <option value="start">Left</option>
                            <option value="center">Center</option>
                            <option value="end">Right</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col d-flex" style="flex-flow: row wrap;"
                        :class="'justify-content-' + form.imagePosition">
                        <div :style="{ width: setImageSize(image.width) }"
                            v-for="(image, index) in form.imageCollection" class="mx-1 my-1" :id="'image' + index">
                            <div :style="{ height: setImageSize(image.height) }" class="position-relative">
                                <img :src="imageUrl(image.url)" class="w-100 h-100">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row mt-2" style="height:fit-content">
                <div class="col" :class="form.OptionTypeProperty.multiple_select ? 'mx-1' : null">
                    <div class="row" v-if="form.OptionTypeProperty.multiple_select">
                        <div class="col">
                            <label class="me-2">Alignment</label>
                            <select class="border p-1 outline-none" v-model="form.optionVerticalAlign"
                                @change="setQuestionProperties()">
                                <option value="block">Vertical</option>
                                <option value="flex">Horizontal</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-2" v-for="(option, index) in form.addOption"
                v-if="form.OptionTypeProperty.multiple_select" :id="'option_' + index + '_' + props.component.id">
                <div class="col">
                    <optionComponent :component="props.component" :index="index"
                        :formSection="formSection" :key="props.index"
                        :optionProperty="form.OptionTypeProperty" @OptionText="getOptionText" />
                </div>
                <div class="col" v-if="form.OptionTypeProperty.multiple_select">
                    <button class="btn-icon d-block me-2 btn-remove-option bg-transparent border-0"
                        title="Remove option" @click="RemoveOption(index,'option_' + index + '_' + props.component.id)">
                        <i class="bi bi-x"></i></button>
                </div>
            </div>
            <div class="row mb-2" v-else :id="'option_' + index + '_' + props.component.id">
                <div class="col">
                    <textComponent :component="props.component" :index="props.index"
                        :formCollection="formSection" :key="props.index"
                        :optionProperty="form.OptionTypeProperty" @DropdownOptionCollection="getDropdownOption" />
                </div>
            </div>
            <div class="row mb-2" v-if="form.addOtherOption && form.OptionTypeProperty.multiple_select"
                :id="'option_0' + props.index">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" :type="form.OptionTypeProperty.type" name="flexRadioDefault"
                            id="flexRadioDefault1" style="margin-top:9px" disabled>
                        <label class="form-check-label ms-2" for="flexRadioDefault1">
                            Other
                        </label>
                        <input type="text" class="border ms-2 p-1 px-2 w-75" disabled placeholder="Type something..." />
                    </div>
                </div>
                <div class="col">
                    <button class="btn-icon d-block me-2 btn-remove-option bg-transparent border-0"
                        title="Remove option" @click="AddOtherOption(false)">
                        <i class="bi bi-x"></i></button>
                </div>
            </div>
            <div class="row" style="height:fit-content" v-if="form.OptionTypeProperty.multiple_select">
                <div class="col d-flex">
                    <button class="btn btn-light btn-sm" @click="form.addOption++">Add option</button>
                    <div v-if="!form.addOtherOption">
                        <span class="mx-1">or</span>
                        <button class="btn btn-link btn-sm" @click="AddOtherOption(true)">Add
                            "Other"</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>