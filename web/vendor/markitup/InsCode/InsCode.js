var InsCode = function()
{
	return {
		display: function(markItUp) {
			console.log(markItUp);
			var textareaID = markItUp.textarea.id;
			var textarea = $('#' + textareaID);
			var output = '';
			$('#ins-code-form').remove();
			$.get(markItUp.root + 'InsCode/InsCode.html', function (data) {
				$('body').append(data);
				var form = $('#ins-code-form');

				form.css({
					'top': textarea.position().top + 5,
					'left': textarea.closest('.markItUpContainer').find('li.ins-code-button').position().left - 322
				});
				form.find('#ins-code-code').val(markItUp.selection);

				form.find('a.cancel').click(function (e) {
					e.preventDefault();
					form.fadeOut(function () {
						form.remove();
					});
				});

				form.fadeIn();

				form.on('submit', function(e) {
					e.preventDefault();
					$.markItUp({target: '#' + textareaID, openWith: '<pre lang="' + $(this).find('#ins-code-language').val() + '">', closeWith: '</pre>', placeHolder:$(this).find('#ins-code-code').val()});
					form.fadeOut(function () {
						form.remove();
					});
				});
			});
		}
	}
}();
