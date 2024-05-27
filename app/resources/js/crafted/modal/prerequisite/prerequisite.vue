<script setup>
import { reactive, onMounted } from 'vue'
import axios from 'axios'
import { ValidateCollection, setObjectElement } from '../../../composables/useSurveyForm.js'

const props = defineProps({
    component: { Type: Array },
    index: { Type: Number },
    formCollection: { Type: Array }
})

const emit = defineEmits(['PrerequisiteCollection'])

let initialState = {
    modalTitle: null,
    prerequisiteForm: null,
    prerequisiteQuestion: null,
    prerequisiteOption: null,
    close: null,
    getCurrentForm: [],
    prerequisiteCollection: []
}

let form = reactive({ ...initialState })

let initialOptionState = {
    prerequisiteForms: [],
    prerequisiteQuestions: [],
    prerequisiteOptions: [],
}

let options = reactive({ ...initialOptionState })
function savePrerequisite() {
    let validatecollection = ValidateCollection(form.prerequisiteCollection, props.component.id)[0]
    if (validatecollection) {
        validatecollection.prerequisite_form = form.prerequisiteForm
        validatecollection.prerequisite_question = form.prerequisiteQuestion
        validatecollection.prerequisite_option = form.prerequisiteOption
    }
    else {
        form.prerequisiteCollection.push({
            id : props.component.id,
            prerequisite_form: form.prerequisiteForm,
            prerequisite_question: form.prerequisiteQuestion,
            prerequisite_option: form.prerequisiteOption,
            question: props.component.id,
            form: props.formCollection.form_id
        })
    }

    emit("PrerequisiteCollection", form.prerequisiteCollection);
    form.close.click()
}

function fetchPrerequisiteForm() {
    axios.get('/survey-form').then(function (response) {
        options.prerequisiteForms = response.data.surveyForms.filter(o => o.status_id !== 1 && o.status_id !== 3)
    })
    form.getCurrentForm = props.formCollection.formSectionCollection.filter(o => o.is_question === true && o.id !== props.component.id)
    fetchprerequisiteQuestions()
}
function fetchprerequisiteQuestions() {
    options.prerequisiteQuestions = []
    if (props.formCollection.form_id === form.prerequisiteForm) {
        form.getCurrentForm.forEach(item => {
            options.prerequisiteQuestions.push({
                id: item.id,
                text: item.text
            })
        });
    }
    else {
        axios.get('/question/' + form.prerequisiteForm).then(function (response) {
            options.prerequisiteQuestions = response.data
        })
    }
}

function fetchPrerequisiteOption() {
    options.prerequisiteOptions = []
    if (props.formCollection.form_id === form.prerequisiteForm) {
        let getQuestionOption = ValidateCollection(form.getCurrentForm, form.prerequisiteQuestion)[0]
        if (getQuestionOption) {
            getQuestionOption.options.forEach(item => {
                options.prerequisiteOptions.push({
                    id: item.id,
                    text: item.text
                })
            });
        }

    }
    else {
        axios.get('/option/' + form.prerequisiteQuestion).then(function (response) {
            options.prerequisiteOptions = response.data
        })
    }
}

function getprerequisiteOptionCollection() {
    form.close = setObjectElement('btnCloseprerequisiteModal' + props.index)
}
onMounted(() => {
    form.prerequisiteForm = props.formCollection.form_id
    fetchPrerequisiteForm()
})
</script>
<template>
    <div>
        <button type="button" class="btn btn-sm btn-link" data-bs-toggle="modal"
            :data-bs-target="`#PrerequisitestaticBackdrop${props.index}`" @click="getprerequisiteOptionCollection()">
            Add Pre-requisite
        </button>
        <!-- Modal -->
        <div class="modal fade" :id="'PrerequisitestaticBackdrop' + props.index" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="PrerequisitestaticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticPrerequisiteBackdropLabel">Add Pre-requisite</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-3">
                                <label class="mt-3">Survey Form</label>
                            </div>
                            <div class="col">
                                <select class="form-control" v-model="form.prerequisiteForm"
                                    @change="fetchprerequisiteQuestions()">
                                    <option :value="props.formCollection.form_id">Current Form</option>
                                    <option v-for="(survey, index) in options.prerequisiteForms" :value="survey.id"
                                        :key="index">{{ survey.title }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-3">
                                <label class="mt-2">Survey Question</label>
                            </div>
                            <div class="col">
                                <select class="form-control" v-model="form.prerequisiteQuestion"
                                    @change="fetchPrerequisiteOption()">
                                    <option v-for="(question, index) in options.prerequisiteQuestions"
                                        :value="question.id" :key="index">{{ question.text }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-3">
                                <label class="mt-1">Option</label>
                            </div>
                            <div class="col">
                                <select class="form-control" v-model="form.prerequisiteOption">
                                    <option v-for="(option, index) in options.prerequisiteOptions" :value="option.text"
                                        :key="index">{{ option.text }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            :id="'btnCloseprerequisiteModal' + props.index">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="savePrerequisite()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>