/*
 * Inline uploading of images for the Jquery MarkItUp editor.
 * @see http://markitup.jaysalvat.com/
 *
 * The purpose of this is to avoid having to have already uploaded an image (and
 * to know the URL) when adding an img tag to edited content. This widget handles
 * the upload itself.
 *
 * The "Picture" button will reveal a dialog from which an image to be uploaded
 * is chosen. The upload's form target is a hidden iframe also within the dialog.
 * Once the iframe has loaded, the JSON response is parsed and the img tag is built
 * and then added to the editor selection.
 *
 * The server-side script should respond with a JSON object wrapped inside a textarea tag.
 * @see http://jquery.malsup.com/form/#file-upload
 *
 * The JSON object should include the following:
 * on success:
 * - status: string, 'success'
 * - src: string, the complete URL to the image
 * - width: string, the image width in pixels
 * - height: string, the image height in pixels
 *
 * on error:
 * - msg: string, whatever error msg you want to display
 *
 * All of the upload form inputs are submitted, so the server-side script might also make use of
 * the class or id assigned to the image. For example, if the image information is to be saved to
 * a database. In that case, it might also be desirable to have this script create hidden fields
 * with further data, like a content ID, so as to better identify the image.
 *
 * Keep in mind that one might have both this widget and the classic Picture buttons in the same toolbar.
 *
 * This particular example REQUIRES the jQuery.loading and jQuery.json plugins.
 * @see http://code.google.com/p/jquery-loading-plugin/
 * @see http://code.google.com/p/jquery-json/
 *
 * @author brian ally, brian ~at~ zijn-digital ~dot~ com
 * @copyright 2010, brian ally
 * @version 0.4
 * @license do what you want with it
 */
var InlineUpload =
{
	dialog: null,
	block: '',
	offset: {},
	options: {
		container_class: 'markItUpInlineUpload',
		form_id: 'inline_upload_form',
		action: '/posts/upload',
		inputs: {
			classname: { label: 'Class', id: 'inline_upload_class', name: 'inline_upload_class' },
			id: { label: 'ID', id: 'inline_upload_id', name: 'inline_upload_id' },
			alt: { label: 'Alt text', id: 'inline_upload_alt', name: 'inline_upload_alt' },
			file: { label: 'File', id: 'inline_upload_file', name: 'inline_upload_file' }
		},
		submit: { id: 'inline_upload_submit', value: 'upload' },
		close: 'inline_upload_close',
		iframe: 'inline_upload_iframe'
	},
	display: function(hash)
	{
		var self = this;

		/* Find position of toolbar. The dialog will inserted into the DOM elsewhere
		 * but has position: absolute. This is to avoid nesting the upload form inside
		 * the original. The dialog's offset from the toolbar position is adjusted in
		 * the stylesheet with the margin rule.
		 */
		this.offset = $(hash.textarea).prev('.markItUpHeader').offset();

		/* We want to build this fresh each time to avoid ID conflicts in case of
		 * multiple editors. This also means the form elements don't need to be
		 * cleared out.
		 */
		this.dialog = $([
			'<div class="',
			this.options.container_class,
			'"><div><form id="',
			this.options.form_id,
			'" action="',
			this.options.action,
			'" target="',
			this.options.iframe,
			'" method="post" enctype="multipart/form-data"><label for="',
			this.options.inputs.classname.id,
			'">',
			this.options.inputs.classname.label,
			'</label><input name="',
			this.options.inputs.classname.name,
			'" id="',
			this.options.inputs.classname.id,
			'" type="text" /><label for="',
			this.options.inputs.id.id,
			'">',
			this.options.inputs.id.label,
			'</label><input name="',
			this.options.inputs.id.name,
			'" id="',
			this.options.inputs.id.id,
			'" type="text" /><label for="',
			this.options.inputs.alt.id,
			'">',
			this.options.inputs.alt.label,
			'</label><input name="',
			this.options.inputs.alt.name,
			'" id="',
			this.options.inputs.alt.id,
			'" type="text" /><label for="',
			this.options.inputs.file.id,
			'">',
			this.options.inputs.file.label,
			'</label><input name="',
			this.options.inputs.file.name,
			'" id="',
			this.options.inputs.file.id,
			'" type="file" /><input id="',
			this.options.submit.id,
			'" type="button" value="',
			this.options.submit.value,
			'" /></form><div id="',
			this.options.close,
			'"></div><iframe id="',
			this.options.iframe,
			'" name="',
			this.options.iframe,
			'" src="about:blank"></iframe></div></div>',
		].join(''))
			.appendTo(document.body)
			.hide()
			.css('top', this.offset.top)
			.css('left', this.offset.left);


		/* init submit button
		 */
		$('#'+this.options.submit.id).click(function()
		{
			$('#'+self.options.form_id).submit().fadeTo('fast', 0.2).parent().loading();
		});


		/* init cancel button
		 */
                $('#'+this.options.close).bind('click', function(){self.cleanUp();});

		/* form response will be sent to the iframe
		 */
		$('#'+this.options.iframe).bind('load', function()
		{
                    if ($(this).contents().find('body').html() != '') {
			var response = $.secureEvalJSON($(this).contents().find('body').html());
			if (response.status == 'success')
			{
				this.block = [
					'<img src="',
					response.src,
					'" width="',
					response.width,
					'" height="',
					response.height,
					'" alt="',
					$('#'+self.options.inputs.alt.id).val(),
					'" class="',
					$('#'+self.options.inputs.classname.id).val(),
					'" id="',
					$('#'+self.options.inputs.id.id).val(),
					'" />'
				];

				self.cleanUp();

				/* add the img tag
				 */
				$.markItUp({ replaceWith: this.block.join('') } );
			}
			else
			{
				/* A really basic example. This should do something a bit more sophisticated.
				 */
				alert(response.msg);
				self.cleanUp();
			}
                    }
		});

		/* Finally, display the dialog
		 */
		this.dialog.fadeIn('slow');
	},
	cleanUp: function()
	{
            this.dialog.fadeOut().remove();
	}
};