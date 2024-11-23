(function ($) {
    
    $(".loading").css("display", "none"); // Для скрытия
    $('#reset-order-list').on('click', '.order-product-remove', function() {

        // $("#reset-order-list").addClass('loading');

        let id = $(this).data("order")
        let orderId = $(this).data("orderId")
        let myAlert = confirm("Вы уверены что хотите удалить?");
        if (myAlert == true) {
            $(document).ready(function() { // Загрузка страницы
                $(".loading").css("display", "block"); // Для показа
                $.ajax({
                    url: '/admin/shop/order-item/delete-order',
                    type: 'get',
                    data: {
                        id: id,
                        order_id: orderId
                    },
                    success: function(res) {

                        $(".loading").css("display", "none"); // Для скрытия
                        $("#reset-order-list").load(
                            "/admin/app/ajax/order-item-product-remove?" + $.param({
                                id: res.order_id,
                            }));
                    },
                    error: function(res) {
                        console.log('errors', res);
                    }
                });
            })
        }
        return false;
    })

})(jQuery);
