/**
 * Created by migue on 01/06/2017.
 */
var FormValidationMd = function() {

    var handleValidation = function(form, rules, messages, method) {

        if (typeof messages === 'undefined' || messages === false) {
            messages = {};
        }

        form.validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: true,
            ignore: "",
            messages: messages,
            rules: rules,

            invalidHandler: function(event, validator) {
                //console.log(event, validator);
            },

            errorPlacement: function(error, element) {
              //console.log(element.parent());

                if (element.is(':checkbox')) {
                    error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                } else if (element.is(':radio')) {
                    error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                } else if (element.hasClass('select2_demo_3')) {
                  console.log('selects');
                    error.insertAfter(element.parent().find(".select2-container--default"));
                //  error.insertAfter(element);
                } else {

                    error.insertAfter(element);
                }
            },

            highlight: function(element) {
                $(element)
                    .closest('.form-group').addClass('has-error');
                    //element.parent().find(".select2-container--default")
                    
            },

            unhighlight: function(element) {
                $(element)
                    .closest('.form-group').removeClass('has-error');
            },

            success: function(label) {
                label
                    .closest('.form-group').removeClass('has-error');
            },

            submitHandler: function(form) {
                if ( typeof method !== 'undefined' && typeof method === 'object' ) {
                    method.init();
                }
                if (typeof method === 'undefined' || method === false) {
                    form.submit();
                }
            }
        });
    };
    return {
        init: function(form, rules, messages, method, btnAction) {
            handleValidation(form, rules, messages, method, btnAction);
        }
    };
}();
/*
var ComponentsBootstrapMaxlength = function () {

    var handleBootstrapMaxlength = function() {
        $('input[maxlength], textarea[maxlength]').maxlength({
            limitReachedClass: "label label-danger",
            alwaysShow: true,
        });
    };

    return {
        init: function () {
            handleBootstrapMaxlength();
        }
    };

}();

jQuery(document).ready(function() {
    ComponentsBootstrapMaxlength.init();
});*/
