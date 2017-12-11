(function (w, $)
{
	var vrModal = {
		_elements: {
			modal: '#vrTour'
		},

		getModal: function ()
		{
			return $(vrModal._elements.modal);
		},

		showModal: function (title, content)
		{
			var modal = vrModal.getModal();
			$(modal).find('.modal-title').html(title);
			$(modal).find('.modal-body .container-fluid').html(content);
			$(vrModal.getModal()).modal('show');
		},

		showModalWithText: function (title, content)
		{
			var modal = vrModal.getModal();
			$(modal).find('.modal-title').html(title);
			$(modal).find('.modal-body .container-fluid').text(content);
			$(vrModal.getModal()).modal('show');
		},

		addButton: function(html)
		{
			$(html).insertBefore(vrModal._elements.modal + ' .modal-footer #modal-close');
		},

		hideModal: function ()
		{
			$(vrModal.getModal()).modal('hide');
		},

		reload: function()
		{
			window.location = window.location.href
		},

		hooks: function()
		{
			$('body').on('click', vrModal._elements.modal + ' .modal-footer #modal-close', function()
			{
				vrModal.reload();
			})
		}
	}

	w.vrAdmin.Modal = vrModal;

	$(document).ready(function(){
		vrModal.hooks();
	})

})(window, jQuery);