;(function ($) {

  function loadResponseFrame(data, frame) {
    var filterData = $(data).find(frame.selector).children();
    $(frame).html(filterData);
  }

  function transferData(data,context) {
    context = context || $('html');
    $(data).find('[data-ajax-grab]').each(function () {
      var $this = $(this);
      var injectElement = $this.data('ajax-grab');
      var injectData = $this.html();
      context.find('[data-ajax-inject="' + injectElement + '"]').html(injectData);
    });
  }

  $.mlsModal = function (options) {
    $.magnificPopup.close();
    $.magnificPopup.open({
      items: {
        src: options.src
      },
      type: 'ajax',
      ajax: {
        settings: {
          data: options.data
        }
      },
      callbacks: {
        parseAjax: function (mfpResponse) {
          if (options.transferData) {
            $.mlsAjax.transferData(mfpResponse.data);
          }
        }
      },
      showCloseBtn: false,
      modal: false
    });
  };

  /**
   * Open modal
   */
  $(document).on('click', '[data-modal-wishlist]', function (e) {
    e.preventDefault();

    var $this = $(this);
    var modalUrl = $this.data('modal-wishlist');
    modalUrl = modalUrl ? modalUrl : $this.attr('href');

    $.magnificPopup.close();
    $.magnificPopup.open({
      items: {
        src: modalUrl
      },
      type: 'ajax',
      showCloseBtn: false,
      modal: false
    });

  });

  /**
   * Close modal
   */
  $(document).on('click', '[data-modal-close]', function (e) {
    e.preventDefault();
    $.magnificPopup.close();
  });

  /**
   * Focusing text field if relative radio is checked
   */
  $(document).on('click', '[data-wishlist-new-input]', function () {
    var radioNew = $(this).closest('[data-wishlist-new-scope]').find('[data-wishlist-new-radio]');
    $(radioNew).trigger('click');
  });

  $(document).on('click', '[data-wishlist-new-radio]', function () {
    var inputNew = $(this).closest('[data-wishlist-new-scope]').find('[data-wishlist-new-input]');
    $(inputNew).trigger('focus');
  });

  /**
   *  Set wishlist name field required if user chose them
   */
  $(document).on('change', 'input[type=radio]', function(e){
      var newWishListName = $('[data-wishlist-new-input]');

      if(e.target.hasAttribute('data-wishlist-new-radio')){
          newWishListName.attr('required', true);
      }else{
          newWishListName.attr('required', false);
      }
  });

  /**
   * Add to wishlist form
   */
  $(document).on('submit', '[data-wishlist-ajax]', function (e) {
    e.preventDefault();

    var form = $(this);
    var responseFrame = $('[data-wishlist-ajax]');

    $.ajax({
      url: form.attr('action'),
      type: form.attr('method') ? form.attr('method') : 'get',
      data: form.serialize(),
      dataType: 'json',
      beforeSend: function () {

        form.block({
          message: null,
          overlayCSS: {
            background: '#fff',
            opacity: 0.6
          }
        });

      },
      success: function (data) {
        if(data.reload){
          location.reload();
          return;
        }

        loadResponseFrame(data.html, responseFrame);
        transferData(data.html);
      }
    });

  });

  /**
   * Open wishlist modal
   */
  $(document).on('click', '[data-wishlist-edit-modal]', function () {
    event.preventDefault();

    var button = $(this);
    var url = button.attr('data-wishlist-edit-modal--url');

    $.mlsModal({
      src: url
    });

  });


})(jQuery);