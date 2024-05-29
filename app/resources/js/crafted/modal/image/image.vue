<script setup>
import { reactive, onMounted } from 'vue'
import { ValidateImageFileType, ValidateImageSize, imageUrl, setObjectElement,RemoveFromCollection,RemoveObject } from '../../../composables/useSurveyForm.js'

const props = defineProps({
    component: { Type: Array },
    index: { Type: Number },
    formSection: { Type: Array }
})

const emit = defineEmits(['ImageCollection'])

let initialState = {
    input: null,
    close: null,
    width: [],
    height: [],
    imageCollection: [],
    id: 1
}

let form = reactive({ ...initialState })

function getImageFile() {
    const file = form.input.files
    if (file.length > 0) {
        if (file !== undefined) {
            if (ValidateImageFileType(file)) {
                addImageToCollection(null, file)
            }
            else {
                alert('invalid file type')
            }
        }
    }
}

function addImageToCollection(index, file) {
    if (index === null) {
        form.imageCollection.push({
            questionid: props.component.id,
            id: index,//form.id++,
            width: null,
            height: null,
            image_id : null,
            url: file[0],
            name: file[0].name
        })
    }
    else {
        form.imageCollection[index].height = ValidateImageSize(form.height[index])
        form.imageCollection[index].width = ValidateImageSize(form.width[index])
    }
}

function getImageCollection() {
    form.input = setObjectElement('input_Image' + props.index)
    form.close = setObjectElement('btn_close_image_modal' + props.index)
}
function browse() {
    form.input.value = null
    form.input.click()
}

function saveImageFile() {
    emit("ImageCollection", form.imageCollection);
    form.close.click()
}

function RemoveImage(id,Elementid){
    form.imageCollection = RemoveFromCollection(form.imageCollection,id)
    RemoveObject(Elementid)
}
onMounted(() => {
    if (props.formSection) {
        props.formSection.images.forEach((item,index) => {
            form.imageCollection.push({
                questionid: props.component.id,
                id: form.id++,
                image_id : item.id,
                width: item.width,
                height: item.height,
                url: item.path,
                name: item.name
            })
            form.width[index] = item.width
            form.height[index] = item.height
        });
        emit("ImageCollection", form.imageCollection);
    }
})
</script>
<template>
    <div>
        <button class="me-2 outline-none bg-transparent border-0"
            :class="props.component.component === 1 ? 'btn-icon d-block' : 'btn btn-link'" title="Add image"
            data-bs-toggle="modal" :data-bs-target="`#ImageModal${props.index}`" @click="getImageCollection()">
            <i class="bi bi-image-fill" v-if="props.component.component === 1"></i>
            <span v-else>Add Image</span>
        </button>

        <div class="modal fade" :id="'ImageModal' + props.index" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="ImageModalLabel">Insert Image</h1>
                    </div>
                    <div class="modal-body d-flex" style="flex-flow: row wrap;max-height: 500px;overflow-y: auto;">
                        <div style="width: 15rem;" class="mx-1 my-1">
                            <div style="height:16rem;" class="card">
                                <input type="file" hidden :id="'input_Image' + props.index" @change="getImageFile()"
                                    accept="image/*">
                                <button type="button" class="btn btn-light w-100 h-100" @click="browse()">+
                                    Add
                                    Image</button>
                            </div>
                        </div>
                        <div style="width: 15rem;" v-for="(image, index) in form.imageCollection"
                            class="mx-1 my-1 image" :id="'image' + index+'_'+props.index">
                            <div style="height:16rem;" class="position-relative">
                                <button class="position-absolute bg-transparent border-0 btn-remove" @click="RemoveImage(image.id,'image' + index+'_'+props.index)"><i
                                        class="bi bi-x fw-bold" style="font-size:25px"></i></button>
                                <img :src="imageUrl(image.url)" style="height:100%;width:100%" class="rounded">
                            </div>
                            <div class="row mt-2 d-flex justify-content-center">
                                <div class="col-8 d-flex">
                                    <input type="text" class="border p-1 text-center w-100 outline-none" placeholder="W"
                                        @change="addImageToCollection(index, null)" v-model="form.width[index]" />
                                    <label class="mx-2 mt-1 fw-bold">x</label>
                                    <input type="text" class="border p-1 text-center w-100 outline-none" placeholder="H"
                                        @change="addImageToCollection(index, null)" v-model="form.height[index]" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" :show="false"
                            :id="'btn_close_image_modal' + props.index">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="saveImageFile()">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>