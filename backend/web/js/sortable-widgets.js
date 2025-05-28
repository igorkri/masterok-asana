function initSortableWidgets() {
  // Находим таблицу, к которой нужно применить сортировку, исключая строку с суммированием
  $('[data-sortable-widget=1] tbody:not(.initialized):not(.kv-page-summary-container)').sortableWidgets({
    animation: 300,
    handle: '.sortable-widget-handler',
    dataIdAttr: 'data-sortable-id',
    onEnd: function (e) {
      var context = $(this.el).parents('[data-sortable-widget=1]');
      $.post(context.data('sortable-url'), {
        sorting: this.toArray(),
        offset: $(e.item).find('[data-offset]').data('offset')
      }).done(function () {
        if (context.data('pjax')) {
          $.pjax.reload({
            container: context.data('pjax-container'),
            timeout: context.data('pjax-timeout')
          });
        }
      });
    }
  }).addClass('initialized');
}

