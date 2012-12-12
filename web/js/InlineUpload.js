var InlineUpload = {
    dialog: null,
    options: {
        form_class: 'inline_upload_form',
        action: '/posts/upload',
        iframe: 'inline_upload_iframe'
    },
    display: function(hash) {
        var self = this;

        this.dialog = $(document).find(".inline_upload_container");

        if (!this.dialog.size()) {
            // Create invisible form and iframe
            this.dialog = $([
                '<div style="opacity:0;position:absolute;" class="inline_upload_container"><form class="',this.options.form_class,'" action="',this.options.action,'" target="',this.options.iframe,'" method="post" enctype="multipart/form-data">',
                '<input name="upload_file" type="file" /></form>' +
                '<iframe id="',this.options.iframe,'" name="',this.options.iframe,'" class="',this.options.iframe,'" src="about:blank" width="0" height="0"></iframe></div>',
            ].join(''));
            this.dialog.appendTo(document.body);
        }

        // make 'click' action on file element right after 'Picture' selection on markItUp menu
        // to show system dialog
        $("input[name='upload_file']").focus();
        $("input[name='upload_file']").trigger('click');

        // submit hidden form after file was selected in system dialog
        $("input[name='upload_file']").live('change', function(){
            if ($(this).val() != '') {
                $('.' + self.options.form_class).submit();
            }
        });

        // response will be sent to the hidden iframe
        $('.' + this.options.iframe).bind('load', function() {
            var responseJSONStr = $(this).contents().text();
            if (responseJSONStr != '') {
                var response = $.parseJSON(responseJSONStr);
                if (response.status == 'success') {
                    var block = ['<img src="' + response.src + '" width="' + response.width + '" height="' + response.height + '" alt="" class=""/>'];
                    $.markItUp({replaceWith: block.join('')} );
                } else {
                    alert(response.msg);
                }
                self.cleanUp();
            }
        });
    },
    cleanUp: function() {
        $("input[name='upload_file']").die('change');
        this.dialog.remove();
    }
};