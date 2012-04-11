var InlineUpload =
{
	dialog: null,
	block: '',
	options: {
		form_class: 'inline_upload_form',
		action: '/posts/upload',
		iframe: 'inline_upload_iframe'
	},
	display: function(hash)
	{
                pointer = this;

		this.dialog = $([
                        '<div style="display:none;"><form class="',this.options.form_class,'" action="',this.options.action,'" target="',this.options.iframe,'" method="post" enctype="multipart/form-data">',
                            '<input name="inline_upload_file" type="file" /></form>' +
                        '<iframe id="',this.options.iframe,'" name="',this.options.iframe,'" class="',this.options.iframe,'" src="about:blank"></iframe></div>',
		].join('')).appendTo(document.body);

                $("input[name='inline_upload_file']").click();
                $("input[name='inline_upload_file']").live('change', function(){
                    if ($(this).val() != '') $('.' + pointer.options.form_class).submit();
                });

		/*
                 * form response will be sent to the iframe
		 */
		$('.'+this.options.iframe).bind('load', function()
		{
                    if ($(this).contents().find('body').html() != '') {
			var response = $.parseJSON($(this).contents().find('body').html());
			if (response.status == 'success') {
                            this.block = ['<img src="' + response.src + '" width="' + response.width + '" height="' + response.height + '" alt="" class=""/>'];
                            $.markItUp({ replaceWith: this.block.join('') } );
			} else {
                            alert(response.msg);
			}
                        pointer.cleanUp();
                    }
		});
	},
	cleanUp: function()
	{
            this.dialog.fadeOut().remove();
	}
};