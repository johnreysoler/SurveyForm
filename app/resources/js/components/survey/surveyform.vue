<script setup>
import { onMounted, reactive } from 'vue'
import { imageUrl, setImageSize, allowMultipleSelection, listStyleTypeLowerAlpha } from '../../composables/useSurveyForm.js'
import axios from 'axios'
import Swal from 'sweetalert2'
const props = defineProps({
    userform: { Type: Array },
    user_id: { Type: Number }
})


let initialState = {
    userForm: [],
    title: '',
    id: 0,
    response: []
}

let form = reactive({ ...initialState })


function fetchForm() {
    axios.get('/form/user/' + props.userform.id).then(function (response) {
        form.userForm = response.data.sections
    })
}

function SubmitResponse() {
    let requiredQuestion = form.userForm.filter(o => o.is_required === 1)
    let filledupfields = requiredQuestion.length
    requiredQuestion.forEach((item) => {
        let requiredfield = document.getElementById('required' + item.id)
        let checkuserresponse = form.response.filter(o => o.questionid === item.id).length
        if (checkuserresponse > 0) {
            requiredfield.classList.add('d-none')
            filledupfields -= 1
        }
        else {
            requiredfield.classList.remove("d-none")
        }
    });
    if (filledupfields === 0) {
        axios.post('/form/user/response', {
            user_id: props.user_id,
            form_id: props.userform.id,
            user_response: form.response
        }).then(function (response) {
            Swal.fire({
                title: "Success",
                text: "Successfully published survey form!",
                icon: "success"
            }).then(window.location.reload());
        })
    }

}
function getResponse(is_multiple_select, className, Elementid, questionid, optionid, optiontext, prerequisite) {
    if (prerequisite.length > 0) {
        if (prerequisite[0].section_id !== null) {
            let validateprerequisite = prerequisite.filter(o => o.answer === optiontext)
            if (prerequisite.length > 0) {
                if (validateprerequisite.length > 0) {
                    document.getElementById('section' + prerequisite[0].section_id).classList.remove("d-none");
                    document.getElementById('section' + prerequisite[0].section_id).classList.add("d-flex");
                }
                else {
                    document.getElementById('section' + prerequisite[0].section_id).classList.add("d-none");
                }
            }
        }
    }

    allowMultipleSelection(is_multiple_select, className, Elementid)

    if (optiontext === null) {

        if (optionid !== 0) {
            optiontext = document.getElementById('otheroption' + questionid).value
        }
        else {
            optiontext = document.getElementById('input' + questionid).value
        }
    }

    let validateResponse = form.response.filter(o => o.questionid === questionid)
    if (validateResponse.length === 0) {
        form.response.push({
            questionid: questionid,
            optionid: optionid,
            text: optiontext
        })
    }
    else {
        if (is_multiple_select) {
            validateResponse = validateResponse.filter(o => o.optionid === optionid)
            if (validateResponse.length === 0) {
                form.response.push({
                    questionid: questionid,
                    optionid: optionid,
                    text: optiontext
                })
            }
            else {
                validateResponse[0].optionid = optionid
                validateResponse[0].text = optiontext
            }
        }
        else {
            validateResponse[0].optionid = optionid
            validateResponse[0].text = optiontext
        }
    }

}
function Validatesection(section) {
    let className;
    if (section.is_question) {
        if (section.prerequisite === 0) {
            className = 'question mx-3 d-flex'
        }
        else {
            if (section.prerequisites.length === 0) {
                if (section.prerequisite === 1) {
                    className = 'question mx-3 d-none'
                }
                else {
                    className = 'question mx-3 d-flex'
                }
            }
            else {
                className = 'question mx-3 d-none'
            }
        }

    }
    else {
        className = 'section d-block'
    }
    return className
}

onMounted(() => {
    fetchForm()
})

</script>
<template>
    <div class="mt-5">
        <h2 class="text-center">{{ props.userform ? props.userform.title : null }}</h2>
        <div v-if="form.userForm.length > 0" class="mt-5">
            <ul id="preview" style="height:83vh;overflow-y:scroll">
                <li v-for="(content, index) in form.userForm" :key="index" :class="Validatesection(content)"
                    :id="'section' + content.id">
                    <div class="row w-100">
                        <div class="col">
                            <div v-html="content.html" class="ms-3"></div>
                            <span class="text-danger d-none" :id="'required' + content.id"
                                v-if="content.is_question && content.is_required">*
                                Required</span>
                            <div v-if="content.images !== null" class="mt-2"
                                :class="[content.is_question ? 'mt-2 mx-3' : null, 'd-flex justify-content-' + content.image_position]"
                                style="flex-flow: wrap;">
                                <img v-for="(image, index) in content.images" :src="imageUrl(image.path)"
                                    :style="{ height: setImageSize(image.height), width: setImageSize(image.width) }"
                                    class="m-1" />
                            </div>

                            <div v-if="content.is_question">
                                <div v-if="content.option_types.multiple_select"
                                    :style="{ display: content.option_vertical_align }">
                                    <div class="mx-3" v-for="(option, index) in content.options" :key="index">
                                        <div class="form-check">
                                            <input :type="content.option_types.type"
                                                :name="'input' + content.id + '_' + index"
                                                :id="'input' + content.id + '_' + index" class="form-check-input"
                                                :class="'question' + content.id" style="margin-top:5px" @click="getResponse(content.is_multiple_select === 0 ? false : true,
            '.question' + content.id, 'input' + content.id + '_' + index,
            content.id, option.id, option.text, content.prerequisites)">
                                            <label :for="'switch' + option.id">
                                                <span class="me-2">{{ listStyleTypeLowerAlpha(index) }}.</span>{{
            option.text }}
                                            </label>

                                        </div>
                                    </div>
                                    <div class="form-check mx-3" v-if="content.with_other_option">
                                        <input class="form-check-input" :type="content.option_types.type"
                                            :name="'input' + content.id + '_otheroption'" style="margin-top:5px"
                                            :class="'question' + content.id" :id="'input' + content.id + '_otheroption'"
                                            @input="getResponse(content.is_multiple_select === 0 ? false : true,
            '.question' + content.id, 'input' + content.id + '_otheroption',
            content.id, content.options.length, null)">
                                        <label class="form-check-label" :for="'switch' + content.id">
                                            <span class="me-2">{{ listStyleTypeLowerAlpha(content.options.length)
                                                }}.</span> Other
                                        </label>
                                        <input type="text" class="border ms-2 p-1 px-2 w-50"
                                            placeholder="Type something..." />
                                    </div>
                                </div>
                                <div v-else class="mx-3 my-2">
                                    <div v-if="content.option_types.tag === 'text'">
                                        <input type="text" v-if="content.option_types.type === 'text'"
                                            :id="'input' + content.id" class="border p-2 w-75 outline-none inputfield"
                                            placeholder="Type something..." @input="getResponse(null, null, null,
            content.id, content.options.length, null, content.prerequisites)" />
                                        <textarea v-else class="border p-2 w-75 outline-none inputfield" rows=""
                                            :id="'input' + content.id" placeholder="Type something..."
                                            style="resize: none;" @input="getResponse(null, null, null,
            content.id, content.options.length, null, content.prerequisites)"></textarea>
                                    </div>
                                    <div v-else>
                                        <select class="border p-2 w-25 outline-none">
                                            <option value="">Select...</option>
                                            <option :value="option.text" v-for="(option, index) in content.options">{{
            option.text }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

            <div>
                <button type="button" class="btn btn-success btn-sm" @click="SubmitResponse()">Submit</button>
            </div>
        </div>
    </div>
</template>

<style>
#preview {
    counter-reset: counter;
}

#preview li:not(.section) {
    counter-increment: counter;
    list-style: none;
}

#preview li:not(.section):before {
    content: counter(counter) ".";
}

.swal2-icon-show {
    margin-left: 40% !important;
}
</style>