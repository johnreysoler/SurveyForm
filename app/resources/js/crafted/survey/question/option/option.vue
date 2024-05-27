<script setup>
import { reactive, onMounted } from 'vue'
const props = defineProps({
    component: { Type: Array },
    index: { Type: Number },
    formSection: { Type: Array },
    optionProperty: { Type: Array },
})

const emit = defineEmits(['OptionText'])

let initialState = {
    text: null,
    id: 1
}

let form = reactive({ ...initialState })

function setOptiontext() {
    emit("OptionText", form.text, props.index, form.id);
}

onMounted(() => {
    if (props.formSection) {
        let option = props.formSection.options[props.index]
        if (option !== undefined) {
            form.text = option.text
            form.id = option.id
        } else {
            form.id = null
        }
        setOptiontext()
    }

})
</script>
<template>
    <div v-if="props.optionProperty.tag === 'input'">
        <div class="form-check">
            <input :type="props.optionProperty.type" name="flexRadioDefault" id="flexRadioDefault1"
                class="form-check-input" style="margin-top:9px" disabled placeholder="Type something...">
            <input type="text" class="border-bottom border-0 outline-none option inputfield ms-2 p-1 px-2 w-100"
                @input="setOptiontext()" v-model="form.text" v-if="props.optionProperty.multiple_select"
                placeholder="Option" />
        </div>
    </div>
</template>