<script setup>
import { onMounted, reactive } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import Multiselect from 'vue-multiselect'
let initialState = {
    form_id: null,
    search: null,
    title: null,
    description: null,
    start_date: null,
    end_date: null,
    assignment: null,
    surveyForms: [],
    modalTitle: null,
    modalClose: null,
    surveyFormsRespondents: [],
    prerequisiteId: null,
    prerequisiteForm: null,
    prerequisiteQuestion: null,
    prerequisiteOption: [],
    errors: [],
    assign_by: 1,
    isBUHead: false
}

let initialOptionState = {
    surveyAssignment: [],
    surveyRespondent: [],
    prerequisiteForms: [],
    prerequisiteQuestions: [],
    prerequisiteOptions: []

}
let form = reactive({ ...initialState })
let options = reactive({ ...initialOptionState })

function fetchData() {
    axios.get('/survey-form', {
        params: {
            search: form.search
        }
    }).then(function (response) {
        form.surveyForms = response.data.surveyForms
        options.prerequisiteForms = form.surveyForms.filter(o => o.status_id !== 1 && o.status_id !== 3)
        options.surveyAssignment = response.data.assignments
        form.assign_by = response.data.assignments[0]
        form.isBUHead = response.data.isBUHead
        fetchAssignmentRespondent()
    })
}

function fetchAssignmentRespondent(survey) {
    axios.get('/assignment/' + form.assign_by.id).then(function (response) {
        options.surveyRespondent = response.data
        if (survey) {
            form.surveyFormsRespondents = response.data.filter(o => survey.form_assignmets.map(repondent => repondent.respondent_id).includes(o.id));
        }

    })
}



function fetchprerequisiteQuestions() {
    axios.get('/question/' + form.prerequisiteForm).then(function (response) {
        options.prerequisiteQuestions = response.data
    })
}

function fetchPrerequisiteOption() {
    axios.get('/option/' + form.prerequisiteQuestion).then(function (response) {
        options.prerequisiteOptions = response.data
    })
}

function formUpdate(survey) {
    form.modalTitle = 'Update Survey Form'
    options.surveyRespondent = []
    if (survey) {
        form.title = survey.title
        form.description = survey.description
        form.assign_by = survey.assignments
        form.start_date = survey.start_date
        form.end_date = survey.end_date
        form.form_id = survey.id
        fetchAssignmentRespondent(survey)

        if (survey.prerequisites.length > 0) {
            form.prerequisiteForm = survey.prerequisites[0].prerequisite_form_id
            fetchprerequisiteQuestions()
            form.prerequisiteQuestion = survey.prerequisites[0].prerequisite_section_id
            fetchPrerequisiteOption()
            form.prerequisiteOption = survey.prerequisites[0].answer
            form.prerequisiteId = survey.prerequisites[0].id
        }

    }
}

function publishSurveyForm(survey) {
    axios.patch('/publish', {
        form_id: survey.id
    }).then(response => {
        Swal.fire({
            title: "Success",
            text: "Successfully published survey form!",
            icon: "success"
        }).then(fetchData());
    })
}

function viewSurveyForm(survey) {
    axios.get('/form/view/' + survey.id).then(function (response) {
        window.open(response.data.redirect + '?id=' + response.data.id);
    })

}
function submitForm() {
    if (form.form_id !== null) {
        axios.patch('/form-update', {
            form_id: form.form_id,
            title: form.title,
            description: form.description,
            assign_by: form.assign_by.id,
            start_date: form.start_date,
            end_date: form.end_date,
            prerequisiteForm: form.prerequisiteForm,
            prerequisiteQuestion: form.prerequisiteQuestion,
            prerequisiteOption: form.prerequisiteOption,
            respondents: form.surveyFormsRespondents.map(repondent => repondent.id)
        }).then(response => {
            Swal.fire({
                title: "Success",
                text: "Successfully saved!",
                icon: "success"
            }).then(window.location.href = response.data.redirect + '?id=' + response.data.id);
        }).catch(error => {
            form.errors = error.response.data.errors;
        })
    }
    else {
        axios.post('/form-store', {
            title: form.title,
            description: form.description,
            assign_by: form.assign_by.id,
            start_date: form.start_date,
            end_date: form.end_date,
            prerequisiteForm: form.prerequisiteForm,
            prerequisiteQuestion: form.prerequisiteQuestion,
            prerequisiteOption: form.prerequisiteOption,
            respondents: form.surveyFormsRespondents.map(repondent => repondent.id)
        }).then(response => {
            Swal.fire({
                title: "Success",
                text: "Successfully saved!",
                icon: "success"
            }).then(window.location.href = response.data.redirect + '?id=' + response.data.id);
        }).catch(error => {
            form.errors = error.response.data.errors;
        })
    }
}

function resetFields() {
    form.form_id = null
    form.search = null
    form.title = null
    form.description = null
    form.start_date = null
    form.end_date = null
    form.assignment = null
    form.surveyFormsRespondents = []
    form.prerequisiteForm = null
    form.prerequisiteQuestion = null
    form.prerequisiteOption = []
    form.modalTitle = 'Create new Survey Form'
}

function exportToExcel(form_id){
    window.open(window.location.origin+'/export/' + form_id) 
}
onMounted(() => {
    fetchData()
    form.modalClose = document.getElementById('btnCloseIndexModal');
})
</script>
<link rel="canonical" href="https://keenthemes.com/metronic" />
<template>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <input type="text" class="form-control me-2" placeholder="Search..." v-model="form.search"
                        @input="fetchData()">
                </div>
                <div class="col">
                    <div class="mt-1 d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                            data-bs-target="#IndexstaticBackdrop" @click="resetFields()">
                            Create new survey
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mt-5" style="max-height: 700px;min-height: 700px; overflow-y: scroll;">
                <div class="col">
                    <table class="table table-hover">
                        <thead class="sticky-top">
                            <tr>
                                <th>id</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Assign To</th>
                                <th>Start date</th>
                                <th>End date</th>
                                <th>Created By</th>
                                <th>Published By</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(survey, index) in form.surveyForms">
                                <td>{{ (index + 1) }}</td>
                                <td>{{ survey.title }}</td>
                                <td>{{ survey.description }}</td>
                                <td>{{ survey.statuses.name }}</td>
                                <td>{{ survey.assignments.name }}</td>
                                <td>{{ survey.start_date }}</td>
                                <td>{{ survey.end_date }}</td>
                                <td>{{ survey.created_bys === null ? null : survey.created_bys.name }}</td>
                                <td>{{ survey.published_bys === null ? null : survey.published_bys.name }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false"
                                            aria-labelledby="navbarDropdown">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"
                                                    v-if="survey.statuses.allow_update && !form.isBUHead"
                                                    data-bs-toggle="modal" data-bs-target="#IndexstaticBackdrop"
                                                    @click="formUpdate(survey)">Edit</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    v-if="survey.statuses.allow_update && form.isBUHead"
                                                    @click="publishSurveyForm(survey)">Publish</a></li>
                                            <li><a class="dropdown-item" href="#" @click="viewSurveyForm(survey)">View</a></li>
                                            <li><a class="dropdown-item" v-if="!survey.statuses.allow_update" href="#" @click="exportToExcel(survey.id)">Export to excel</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="IndexstaticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="IndexstaticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ form.modalTitle }}</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-3">
                                <label class="mt-3">Title</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" v-model="form.title">
                                <span v-if="form.errors['title']" class="text-danger" style="font-size: 10px;">{{
                        form.errors['title'][0] }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-3">
                                <label class="mt-3">Description</label>
                            </div>
                            <div class="col">
                                <textarea class="form-control" rows="5" style="resize: none;"
                                    v-model="form.description"></textarea>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-3">
                                <label class="mt-3">Start Date</label>
                            </div>
                            <div class="col">
                                <input type="date" class="form-control" v-model="form.start_date">
                                <span v-if="form.errors['start_date']" class="text-danger" style="font-size: 10px;">{{
                        form.errors['start_date'][0] }}</span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-3">
                                <label class="mt-3">End Date</label>
                            </div>
                            <div class="col">
                                <input type="date" class="form-control" v-model="form.end_date">
                                <span v-if="form.errors['end_date']" class="text-danger" style="font-size: 10px;">{{
                        form.errors['end_date'][0] }}</span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-3">
                                <label class="mt-3">Assign To</label>
                            </div>
                            <div class="col">
                                <!-- <select class="form-control" v-model="form.assign_by"
                                    @change="fetchAssignmentRespondent()">
                                    <option v-for="(assignment, index) in options.surveyAssignment"
                                        :value="assignment.id" :key="index">{{ assignment.name }}</option>
                                </select> -->

                                <multiselect v-model="form.assign_by" :multiple="false" :preselect-first="true"
                                    @select="fetchAssignmentRespondent()" :options="options.surveyAssignment"
                                    label="name" track-by="id"></multiselect>
                                <span v-if="form.errors['assign_by']" class="text-danger" style="font-size: 10px;">{{
                        form.errors['assign_by'][0] }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-3">
                                <label class="mt-3">Respondents</label>
                            </div>
                            <div class="col-9">
                                <!-- <select class="form-control" v-model="form.surveyFormsRespondents">
                                    <option v-for="(respondent, index) in options.surveyRespondent"
                                        :value="respondent.id" :key="index">{{ respondent.name }}</option>
                                </select> -->

                                <multiselect v-model="form.surveyFormsRespondents" :multiple="true"
                                    :preselect-first="true" :options="options.surveyRespondent" label="name"
                                    track-by="id"></multiselect>
                                <span v-if="form.errors['respondents']" class="text-danger" style="font-size: 10px;">{{
                        form.errors['respondents'][0] }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-3">
                                <label class="mt-3">Survey Form</label>
                            </div>
                            <div class="col">
                                <select class="form-control" v-model="form.prerequisiteForm"
                                    @change="fetchprerequisiteQuestions()">
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
                            id="btnCloseIndexModal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="submitForm()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style>
.swal2-icon-show {
    margin-left: 40% !important;
}
</style>