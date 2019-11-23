
const efile = document.getElementById("uploadfile")
const uploaddrop = document.getElementById("upload-drop")
let FILE
window.onload = () => {

    uploaddrop.addEventListener("dragover", (e) => {
        e.preventDefault()
        e.stopPropagation()
    })
    uploaddrop.addEventListener("dragenter", e => {
        e.preventDefault()
        e.stopPropagation()
    })
    uploaddrop.addEventListener("drop", e => {
        e.preventDefault()
        e.stopPropagation()

        FILE = e.dataTransfer.files[0]
        if (FILE) {
            showPreview()
        }
    })

    document.getElementById("upload-icon").addEventListener("click", (e) => {
        efile.click()
    })

    efile.addEventListener("change", (e) => {
        FILE = efile.files[0]
        efile.value = ''
        showPreview()
    })

    document.getElementById("upload-preview-img").addEventListener("click", (e) => {
        e.stopPropagation()
        document.getElementById('upload-preview').classList.toggle('upload-preview-scale')
    })

    document.getElementById('upload-delete').addEventListener("click", (e) => {
        e.stopPropagation()
        FILE = undefined
        showPreview()
    })

    const upclouds = document.getElementsByClassName('upload-cloud-up')
    for (var upc of upclouds) {
        upc.addEventListener('click', (e) => {
            if (!FILE) {
                showTips('请选择要上传的图片')
                return
            }
            showTips('')
            let cname = getEventCloudName(e.target)
            // 上传到对应图床
            const formdata = new FormData()
            formdata.append('file', FILE)
            formdata.append('pichost', cname)
            if (window.fetch) {
                fetch('/upload.php', {
                    method: 'POST',
                    body: formdata
                }).then((res) => {
                    if (res.ok) {
                        return res.json()
                    }
                    else {
                        showTips(res.status + ':' + res.statusText)
                    }
                })
                    .catch(error => console.error('Error:', error))
                    .then(response => {
                        console.log('Success:', response)
                        showTips(JSON.stringify(response, null, 2))
                        FILE = undefined
                        showPreview()
                    });
            } else {

            }
        })
    }
}
function showTips(msg) {
    document.getElementById("upload-result").innerText = msg
}
function getEventCloudName(target) {
    if (!target) return ''
    else if (target.attributes['cname']) return target.attributes['cname'].value
    else return getEventCloudName(target.parentElement)
}
function showPreview() {
    const img = document.getElementById("upload-preview-img")
    if (!FILE) {
        img.src = '/img/picp.ico'
        document.getElementById("upload-icon").classList.remove('upload-icon-active')
        document.getElementById('upload-preview').classList.remove('upload-preview-scale')
        return
    }

    const reader = new FileReader()
    reader.readAsDataURL(FILE)
    reader.onloadend = (e) => {
        img.src = e.currentTarget.result
        document.getElementById("upload-icon").classList.add('upload-icon-active')
        document.getElementById('upload-preview').classList.remove('upload-preview-scale')
    }
}