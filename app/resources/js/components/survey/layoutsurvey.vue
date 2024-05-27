<script setup>
import { onMounted, reactive } from 'vue';
import container from '../../crafted/survey/container.vue'
import preview from './preview.vue'
import axios from 'axios'
import swal from 'sweetalert';
let initialState = {
    form_id: null,
    componentCollection: [],
    component_id: 0,
    formSectionCollection: [],
    preview: false,
    survey: null
}

let form = reactive({ ...initialState })

function fetchFormDetails() {
    axios.get('/survey-form-layout/' + form.form_id).then(function (response) {
        form.survey = response.data

        form.survey.sections.forEach(item => {
            if (item.is_question) {
                addComponent(1)
            }
            else {
                if (item.text === null && item.html === null) {
                    addComponent(2)
                }
                else {
                    addComponent(3)
                }
            }
        });
    })

}


function addComponent(component) {
    form.componentCollection.push({
        id: form.component_id++,
        component: component,
        active: true
    })
}

function getformProperties(collection) {
    form.formSectionCollection = collection
}

function formSave() {
    let formData = new FormData()
    for (let i = 0; form.formSectionCollection.length > i; i++) {
        if (form.formSectionCollection[i].images !== null) {
            for (let j = 0; form.formSectionCollection[i].images.length > j; j++) {
                if (form.formSectionCollection[i].images[j].image_id === null) {
                    formData.append('question_ids[]', form.formSectionCollection[i].id)
                    formData.append('heights[]', form.formSectionCollection[i].images[j].height)
                    formData.append('widths[]', form.formSectionCollection[i].images[j].width)
                    formData.append('upload_files[]', form.formSectionCollection[i].images[j].url)
                }
            }
        }
    }

    formData.append('collection', JSON.stringify(form.formSectionCollection))
    swal("","Do you want to publish survey form?", "info", {
        className: "boxstyle",

        buttons: {
            New: {
                text: "Save as draft",
                value: false,
                visible: true,
            },

            New2: {
                text: " Publish",
                value: true,
                visible: true,
            },
            cancel: true,
        },
    }).then((result) => {
        if (result || !result) {
            formData.append('status', result)
            if (form.survey.sections.length > 0) {
                formData.append("_method", "PATCH");
                axios.post('/survey-form-update', formData).then(function (response) {
                    console.log(response)
                    // window.location.href = response.data.redirect
                })
            }
            else {
                axios.post('/survey-form-store', formData).then(function (response) {
                    console.log(response)
                    // window.location.href = response.data.redirect
                })
            }
        }
    });

}
onMounted(() => {
    form.form_id = new URLSearchParams(window.location.search).get('id');
    fetchFormDetails()
})
</script>
<template>
    <div class="card">
        <div class="card-body" style="min-height:75vh;">
            <div class="row">
                <div class="col" style="min-height:75vh;max-height:75vh;overflow-y: auto;">
                    <preview :formCollection="form" v-show="form.preview" />
                    <container v-show="!form.preview" v-for="(component, index) in form.componentCollection"
                        :component="component" :index="index" :formCollection="form"
                        @formProperties="getformProperties"></container>
                </div>
                <div class="col-1">
                    <button type="button" class="bg-transparent border-0 d-block" title="Add text"
                        @click="form.preview = !form.preview"><i class="bi bi-eye-fill"></i></button>
                    <div v-if="!form.preview">
                        <button type="button" class="bg-transparent border-0 d-block" title="Add question"><i
                                class="bi bi-plus-circle-fill" @click="addComponent(1)"></i></button>
                        <button type="button" class="bg-transparent border-0 d-block" title="Add image"><i
                                class="bi bi-image-fill" @click="addComponent(2)"></i></button>
                        <button type="button" class="bg-transparent border-0 d-block" title="Add text"
                            @click="addComponent(3)"><i class="bi bi-fonts"></i></button>
                        <button class="bg-transparent border-0 d-block" title="Save survey form " @click="formSave()"><i
                                class="bi bi-save2"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<style>
.outline-none {
    outline: none;
}

div[placeholder]:empty:before {
    content: attr(placeholder);
    color: #555;
}

div[placeholder]:empty:before {
    content: attr(placeholder);
    color: #555;
}

.inputfield:focus {
    border-color: black !important;
}

.btn-remove:hover {
    color: red;
}
</style>