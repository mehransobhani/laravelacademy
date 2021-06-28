import constants from './constants';
import Cropper from 'cropperjs';

const $siteUrl = constants.$siteUrl;

const src = document.getElementById("image");
const image = document.getElementById('cropImage');
if (image){

    const cropper = new Cropper(image, {
        aspectRatio: 1 / 1,
    });

    showImage(src, image);
    function showImage(src, image) {
        const fr = new FileReader();
        // when image is loaded, set the src of the image where you want to display it
        fr.onload = function (e) {
            image.src = this.result;
            $(image).parent('.crop-image-wrapper').addClass("my-4");
            cropper.replace(e.target.result);
        };
        src.addEventListener("change", function () {
            // fill fr with image data
            fr.readAsDataURL(src.files[0]);

        });
    }

    $('#course-create-button').click(function( event ) {
        const cover_img =$('#cover_img');
        if(document.getElementById("image").value !== "") {

            $('.loading-overlay').addClass('is-active');
            event.preventDefault();
            cropper.getCroppedCanvas({
                width: 680,
                height: 680,
                minWidth: 256,
                minHeight: 256,
                maxWidth: 1024,
                maxHeight: 1024,
                fillColor: '#fff',
                imageSmoothingEnabled: false,
                imageSmoothingQuality: 'high',
            }).toBlob((blob) => {
                const formData = new FormData();

                // Pass the image file name as the third parameter if necessary.
                formData.append('croppedImage', blob/*, 'example.png' */);
                formData.append('oldImage', cover_img.val()/*, 'example.png' */);

                // Use `jQuery.ajax` method for example
                $.ajax($siteUrl + 'api/image-cropper/upload', {
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success(res) {
                        $('.loading-overlay').removeClass('is-active');
                        if (res.successful){
                            $('#cover_img').val(res.data.path);
                            $('#course-create-form').submit();
                        }
                        else {
                            console.log('something went wrong, please contact admin')
                        }
                    },
                    error() {
                        $('.loading-overlay').removeClass('is-active');
                        console.log('something went wrong, please contact admin');
                    },
                });
            }/*, 'image/png' */);
        }
        else{
            $('.custom-file').after('<div class="alert alert-danger w-100 mt-1"><ul class="mb-0 pr-0"><li>تصویر نمیتواند خالی باشد</li></ul></div>')
        }
    });
}



const cropEditImage = document.getElementById('cropEditImage');
if (cropEditImage) {
    showImage(src, cropEditImage);
    function showImage(src, image) {
        const fr = new FileReader();
        // when image is loaded, set the src of the image where you want to display it
        fr.onload = function (e) {
            image.src = this.result;
            $(image).parent('.crop-image-wrapper').addClass("my-4");
            if (cropper){
                cropper.replace(e.target.result);
            }
            else {
                cropper = new Cropper(cropEditImage, {
                    aspectRatio: 1 / 1,
                });
            }
        };
        src.addEventListener("change", function () {
            // fill fr with image data
            fr.readAsDataURL(src.files[0]);

        });
    }

    let cropper = null;
    $('#cropEditImage').click(function (){
        cropper = new Cropper(cropEditImage, {
            aspectRatio: 1 / 1,
        });
    })


    $('#course-edit-button').click(function( event ) {
        if(cropper){
            $('.loading-overlay').addClass('is-active');
            event.preventDefault();
            cropper.getCroppedCanvas({
                width: 680,
                height: 680,
                minWidth: 256,
                minHeight: 256,
                maxWidth: 1024,
                maxHeight: 1024,
                fillColor: '#fff',
                imageSmoothingEnabled: false,
                imageSmoothingQuality: 'high',
            }).toBlob((blob) => {
                const formData = new FormData();

                // Pass the image file name as the third parameter if necessary.
                formData.append('croppedImage', blob/*, 'example.png' */);

                // Use `jQuery.ajax` method for example
                $.ajax($siteUrl + 'api/image-cropper/upload', {
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success(res) {
                        $('.loading-overlay').removeClass('is-active');
                        if (res.successful){
                            $('#cover_img').val(res.data.path);
                            $('#course-create-form').submit();
                        }
                        else {
                            console.log('something went wrong, please contact admin')
                        }
                    },
                    error() {
                        $('.loading-overlay').removeClass('is-active');
                        console.log('something went wrong, please contact admin');
                    },
                });
            }/*, 'image/png' */);
        }else {
            $('#course-create-form').submit();
        }
    });
}


const stepCropImage = document.getElementById('stepCropImage');
if (stepCropImage){

    const cropper = new Cropper(stepCropImage, {
        aspectRatio: 1 / 1,
    });

    showImage(src, stepCropImage);
    function showImage(src, image) {
        const fr = new FileReader();
        // when image is loaded, set the src of the image where you want to display it
        fr.onload = function (e) {
            image.src = this.result;
            $(image).parent('.crop-image-wrapper').addClass("my-4");
            cropper.replace(e.target.result);
        };
        src.addEventListener("change", function () {
            // fill fr with image data
            fr.readAsDataURL(src.files[0]);

        });
    }

    $('#step-create-button').click(function( event ) {
        const cover_img =$('#cover_img');
        if(document.getElementById("image").value !== "") {

            $('.loading-overlay').addClass('is-active');
            event.preventDefault();
            cropper.getCroppedCanvas({
                width: 680,
                height: 680,
                minWidth: 256,
                minHeight: 256,
                maxWidth: 1024,
                maxHeight: 1024,
                fillColor: '#fff',
                imageSmoothingEnabled: false,
                imageSmoothingQuality: 'high',
            }).toBlob((blob) => {
                const formData = new FormData();

                // Pass the image file name as the third parameter if necessary.
                formData.append('croppedImage', blob/*, 'example.png' */);
                formData.append('oldImage', cover_img.val());
                formData.append('path', 'blog/images');

                // Use `jQuery.ajax` method for example
                $.ajax($siteUrl + 'api/image-cropper/upload', {
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success(res) {
                        $('.loading-overlay').removeClass('is-active');
                        if (res.successful){
                            $('#cover_img').val(res.data.path);
                            $('#course-create-form').submit();
                        }
                        else {
                            console.log('something went wrong, please contact admin')
                        }
                    },
                    error() {
                        $('.loading-overlay').removeClass('is-active');
                        console.log('something went wrong, please contact admin');
                    },
                });
            }/*, 'image/png' */);
        }
        else{
            $('.custom-file').after('<div class="alert alert-danger w-100 mt-1"><ul class="mb-0 pr-0"><li>تصویر نمیتواند خالی باشد</li></ul></div>')
        }
    });
}



const stepEditCropImage = document.getElementById('stepEditCropImage');
if (stepEditCropImage){
    let cropper = null;
    showImage(src, stepEditCropImage);
    function showImage(src, image) {
        const fr = new FileReader();
        // when image is loaded, set the src of the image where you want to display it
        fr.onload = function (e) {
            image.src = this.result;

            if (cropper){
                cropper.replace(e.target.result);
            }
            else {
                cropper = new Cropper(stepEditCropImage, {
                    aspectRatio: 1 / 1,
                });
            }
        };
        src.addEventListener("change", function () {
            // fill fr with image data
            fr.readAsDataURL(src.files[0]);

        });
    }

    $('#step-create-button').click(function( event ) {
        if(cropper) {
            const cover_img = $('#cover_img');
                $('.loading-overlay').addClass('is-active');
                event.preventDefault();
                cropper.getCroppedCanvas({
                    width: 680,
                    height: 680,
                    minWidth: 256,
                    minHeight: 256,
                    maxWidth: 1024,
                    maxHeight: 1024,
                    fillColor: '#fff',
                    imageSmoothingEnabled: false,
                    imageSmoothingQuality: 'high',
                }).toBlob((blob) => {
                    const formData = new FormData();

                    // Pass the image file name as the third parameter if necessary.
                    formData.append('croppedImage', blob/*, 'example.png' */);
                    formData.append('oldImage', cover_img.val());
                    formData.append('path', 'blog/images');

                    // Use `jQuery.ajax` method for example
                    $.ajax($siteUrl + 'api/image-cropper/upload', {
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success(res) {
                            $('.loading-overlay').removeClass('is-active');
                            if (res.successful) {
                                $('#cover_img').val(res.data.path);
                                $('#course-create-form').submit();
                            } else {
                                console.log('something went wrong, please contact admin')
                            }
                        },
                        error() {
                            $('.loading-overlay').removeClass('is-active');
                            console.log('something went wrong, please contact admin');
                        },
                    });
                }/*, 'image/png' */);

        }
        else {
            $('#course-create-form').submit();
        }
    });
}
