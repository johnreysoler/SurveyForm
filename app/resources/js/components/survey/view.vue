<script setup>
import { onMounted } from 'vue';
import { imageUrl, setImageSize } from '../../composables/useSurveyForm.js'
const props = defineProps({
    form: { Type: Array }
})

</script>
<template>
    <div class="mt-5" style="max-height:800px;overflow-y:scroll">
        <h2 class="text-center">{{ props.form ? props.form.title : null }}</h2>
        <div v-if="props.form.sections.length > 0" class="mt-5">
            <ul id="preview">
                <li v-for="(content, index) in props.form.sections" :key="index"
                    :class="content.is_question ? 'question mx-3 d-flex' : 'section d-block'">
                    <div class="row w-100">
                        <div class="col">
                            <div v-html="content.html" class="ms-3"></div>
                            <div v-if="content.images !== null" class="mt-2"
                                :class="[content.is_question ? 'mt-2 mx-3' : null, 'd-flex justify-content-' + content.image_position]"
                                style="flex-flow: wrap;">
                                <img v-for="(image, index) in content.images" :src="imageUrl(image.url)"
                                    :style="{ height: setImageSize(image.height), width: setImageSize(image.width) }"
                                    class="m-1" />
                            </div>
                            <div v-if="content.is_question">
                                <div v-if="content.option_types.multiple_select"
                                    :style="{ display: content.option_position }">
                                    <div class="mx-3" v-for="(option, index) in content.options" :key="index">
                                        <div class="form-check">
                                            <input :type="content.option_types.type" :name="'switch' + option.id"
                                                :id="'switch' + option.id" class="form-check-input"
                                                style="margin-top:5px" placeholder="Type something..." disabled>
                                            <label :for="'switch' + option.id">{{ option.text }}</label>
                                        </div>
                                    </div>
                                    <div class="form-check mx-3" v-if="content.with_other_option">
                                        <input class="form-check-input" :type="content.option_type.type"
                                            :name="'switch' + content.id" :id="'switch' + content.id"
                                            style="margin-top:5px">
                                        <label class="form-check-label ms-2" :for="'switch' + content.id">
                                            Other
                                        </label>
                                        <input type="text" class="border ms-2 p-1 px-2 w-50"
                                            placeholder="Type something..." />
                                    </div>
                                </div>
                                <div v-else class="mx-3 my-2">
                                    <div v-if="content.option_types.tag === 'text'">
                                        <input type="text" placeholder="Type something..."
                                            class="border p-2 w-50 outline-none inputfield"
                                            v-if="content.option_types.type === 'text'" />
                                        <textarea v-else class="border p-2 w-75 outline-none inputfield" rows="7"
                                            placeholder="Type something..." style="resize: none;"></textarea>
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
</style> 