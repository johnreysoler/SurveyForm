<script setup>
import { reactive, onMounted } from 'vue'
import dropdownComponent from '../../../modal/dropdown/dropdown.vue'
const props = defineProps({
    component: { Type: Array },
    index: { Type: Number },
    formCollection: { Type: Array },
    optionProperty: { Type: Array },
    formSection: { Type: Array },
})

const emit = defineEmits(['DropdownOptionCollection'])

let initialState = {
    text: null,
    dropdownOption : []
}

function getDropdownOption(collection){
    form.dropdownOption = collection
    emit("DropdownOptionCollection", collection);
}

let form = reactive({ ...initialState })

</script>
<template>
    <div v-if="props.optionProperty.tag === 'text'">
        <input type="text" placeholder="Type something..." class="border p-2 w-50 outline-none inputfield" disabled
            v-if="props.optionProperty.type === 'text'" />
        <textarea v-else class="border p-2 w-75 outline-none inputfield" rows="7" placeholder="Type something..."
            disabled style="resize: none;"></textarea>
    </div>
    <div v-else>
        <div class="row">
            <div class="col d-flex">
                <select class="border p-2 w-25 outline-none">
                    <option value="">Select...</option>
                    <option v-for="(option,index) in form.dropdownOption" :value="option.text">{{ option.text }}</option>
                </select>
                <dropdownComponent :component="props.component" :index="props.index"
                    :formSection="props.formSection" :key="props.index"
                    :optionProperty="form.OptionTypeProperty" @DropdownOptionCollection="getDropdownOption"/>
            </div>
        </div>
    </div>
</template>