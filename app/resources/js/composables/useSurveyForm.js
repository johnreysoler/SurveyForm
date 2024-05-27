export function RemoveObject(id) {
    document.getElementById(id).remove()
}
export function setImageURL(file) {
    return file !== undefined ? URL.createObjectURL(file) : null;
}

export function ValidateCollection(array, id) {
    return array.filter(function (o) {
        return o.id === id;
    });
}

export function ValidateImageFileType(file) {
    if (file[0].type.split("/")[0] === 'image') {
        return true
    }
    return false
}

export function ValidateImageSize(imagesize) {
    return imagesize === '' || imagesize === 0 || imagesize === undefined ? null : imagesize
}

export function publicPath() {
    return window.location.origin;
}

export function isImgUrl(url) {
    return /\.(jpg|jpeg|png|webp|avif|gif)$/.test(url)
}

export function imageUrl(url){
    return isImgUrl(url) ? publicPath() + '/storage/'+url : setImageURL(url)
        
}

export function setImageSize(size){
    return size === null ? null : (size * 96) + 'px';
}

export function setObjectElement(elementid){
    return document.getElementById(elementid)
}

export function RemoveFromCollection(array, id) {
    return array.filter(function (o) {
        return o.id !== id;
    });
}

export function setBolean(bool){
    return bool === 1 ? true : false;
}

export function allowMultipleSelection(multipleSelect, className, id) {
    if (multipleSelect !== null) {
        if (multipleSelect === false) {
            let checkedoption = document.getElementById(id)
            let options = document.querySelectorAll(className);
            if (checkedoption.checked) {
                for (let i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        options[i].checked = false
                    }
                }
                if (checkedoption.checked) {
                    checkedoption.checked = false
                } else {
                    checkedoption.checked = true
                }
            }
        }
    }

}

export function listStyleTypeLowerAlpha(index) {
    let arr = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z']
    return arr[index]
}