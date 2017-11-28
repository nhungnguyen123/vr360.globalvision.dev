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

		hideModal: function ()
		{
			$(vrModal.getModal()).modal('hide');
		}
	}

	w.vrAdmin.Modal = vrModal;
})(window, jQuery);