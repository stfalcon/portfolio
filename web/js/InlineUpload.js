var InlineUpload = {
    dialog: null,
    block: '',
    options: {
        form_class: 'inline_upload_form',
        action: '/posts/upload',
        iframe: 'inline_upload_iframe'
    },
    display: function(hash) {
        pointer = this;

        // Create invisible form and iframe
        this.dialog = $([
            '<div style="opacity:0;position:absolute;" class="inline_upload_container"><form class="',this.options.form_class,'" action="',this.options.action,'" target="',this.options.iframe,'" method="post" enctype="multipart/form-data">',
            '<input name="uploadForm[inlineUploadFile]" type="file" /></form>' +
            '<iframe id="',this.options.iframe,'" name="',this.options.iframe,'" class="',this.options.iframe,'" src="about:blank" width="0" height="0"></iframe></div>',
        ].join(''));
        if ($(document).find(".inline_upload_container").length == 0) {
            this.dialog.appendTo(document.body);
        } else {
            this.dialog = $(document).find(".inline_upload_container");
        }

        // make 'click' action on file element right after 'Picture' selection on markItUp menu
        // to show system dialog
        $("input[name='uploadForm[inlineUploadFile]']").focus();
        $("input[name='uploadForm[inlineUploadFile]']").click();

        // submit hidden form after file was selected in system dialog
        $("input[name='uploadForm[inlineUploadFile]']").live('change', function(){
            if ($(this).val() != '') $('.' + pointer.options.form_class).submit();
        });

        // response will be sent to the hidden iframe
        $('.' + this.options.iframe).bind('load', function() {
            if ($(this).contents().find('body').html() != '') {
                var response = $.parseJSON($(this).contents().find('body').html());
                if (response.status == 'success') {
                    this.block = ['<img src="' + response.src + '" width="' + response.width + '" height="' + response.height + '" alt="" class=""/>'];
                    $.markItUp({replaceWith: this.block.join('')} );
                } else {
                    alert(response.msg);
                }
                pointer.cleanUp();
            }
        });
    },
    cleanUp: function() {
        $("input[name='uploadForm[inlineUploadFile]']").die('change');
        this.dialog.remove();
    }
};