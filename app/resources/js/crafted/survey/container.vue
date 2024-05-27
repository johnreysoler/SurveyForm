<script setup>
import Footercomponent from './footer/footercomponent.vue';
import Imagecomponent from './image/imagecomponent.vue';
import Questioncomponent from './question/questioncomponent.vue';
import Textcomponent from './text/textcomponent.vue';
import { RemoveObject, ValidateCollection, RemoveFromCollection } from '../../composables/useSurveyForm.js'
import { onMounted, reactive } from 'vue'
const props = defineProps({
    component: { Type: Array },
    index: { Type: Number },
    formCollection: { Type: Array }
})

const emit = defineEmits(['formProperties'])

let initialState = {
    text: null,
    html: null,
    prerequisiteCollection: [],
    is_required: false,
    is_multiple_select: false
}

let form = reactive({ ...initialState })

function getFormProperties(collection) {
    if (collection.remove_form_section) {
        RemoveObject('form_section' + props.index)
        props.formCollection.formSectionCollection = RemoveFromCollection(props.formCollection.formSectionCollection, props.component.id)
    }
    else {
        form.is_multiple_select = collection.is_multiple_select
        form.is_required = collection.is_required
        let validatecollection = ValidateCollection(props.formCollection.formSectionCollection, props.component.id)[0]
        if (validatecollection) {
            validatecollection.is_required = form.is_required
            validatecollection.is_multiple_select = form.is_multiple_select
        }
        setFormSectionCollection(props.formCollection.formSectionCollection)
    }
}

function setFormSectionCollection(collection) {
    emit("formProperties", collection);
}

function getPrerequisiteCollection(collection) {
    form.prerequisiteCollection = collection
    let validatecollection = ValidateCollection(props.formCollection.formSectionCollection, props.component.id)[0]
    if (validatecollection) {
        validatecollection.prerequisites = form.prerequisiteCollection
    }
    setFormSectionCollection(props.formCollection.formSectionCollection)
}

onMounted(() => {
})
</script>
<template>
    <div class="border w-100 mb-2 p-4" :id="'form_section' + props.index">
        <Questioncomponent v-if="props.component.component === 1" class="component" :component="props.component"
            :index="props.index" :formCollection="props.formCollection"
            :formSection="props.formCollection.survey.sections[props.index]" :form="form"
            @questionProperties="setFormSectionCollection" />
        <Imagecomponent v-else-if="props.component.component === 2" class="component" :component="props.component"
            :index="props.index" :formSection="props.formCollection.survey.sections[props.index]"
            :formCollection="props.formCollection" />
        <Textcomponent v-else-if="props.component.component === 3" class="component" :component="props.component"
            :index="props.index" :formCollection="props.formCollection"
            :formSection="props.formCollection.survey.sections[props.index]"
            @textProperties="setFormSectionCollection" />
        <Footercomponent :component="props.component" :index="props.index" class="mt-2"
            :formCollection="props.formCollection" :formSection="props.formCollection.survey.sections[props.index]"
            @formProperties="getFormProperties" @PrerequisiteCollection="getPrerequisiteCollection" />
    </div>
</template>

<style>
.component {
    min-height: 250px;
}
</style>