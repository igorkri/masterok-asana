
function shopToggle(id){
        var toggleStock = document.getElementById('toggle-'+id);
        const data = toggleStock.getAttribute('data-toggle-stock');
        if(data === 'on'){
                $.ajax({
                        url: '/admin/crm/stock/toggle-ajax',
                        type: 'get',
                        data:{
                                id: id,
                                on: 0
                        },
                        success: function(res){
                                const updateData = toggleStock.setAttribute('data-toggle-stock','off');
                                document.getElementById('toggle-'+id).innerHTML = '<i class="fas fa-toggle-off"></i>';
                                document.getElementById('toggle-'+id).style.color = "grey";
                        },
                        error: function (res) {
                                console.log('errors', res);
                        }
                });

        }else {
                $.ajax({
                        url: '/admin/crm/stock/toggle-ajax',
                        type: 'get',
                        data:{
                                id: id,
                                on: 1
                        },
                        success: function(res){
                                const updateData = toggleStock.setAttribute('data-toggle-stock','on');
                                document.getElementById('toggle-'+id).innerHTML = '<i class="fas fa-toggle-on"></i>';
                                document.getElementById('toggle-'+id).style.color = "dodgerblue";
                        },
                        error: function (res) {
                                console.log('errors', res);
                        }
                });

        }

}

function recommendedProducts(id){
        var toggleStock = document.getElementById('recommended-products-'+id);
        const data = toggleStock.getAttribute('data-recommended-products');
        if(data === 'on'){
                $.ajax({
                        url: '/admin/crm/stock/recommended-products-ajax',
                        type: 'get',
                        data:{
                                id: id,
                                on: 0
                        },
                        success: function(res){
                                const updateData = toggleStock.setAttribute('data-recommended-products','off');
                                document.getElementById('recommended-products-'+id).innerHTML = '<i class="fas fa-toggle-off"></i>';
                                document.getElementById('recommended-products-'+id).style.color = "grey";
                        },
                        error: function (res) {
                                console.log('errors', res);
                        }
                });

        }else {
                $.ajax({
                        url: '/admin/crm/stock/recommended-products-ajax',
                        type: 'get',
                        data:{
                                id: id,
                                on: 1
                        },
                        success: function(res){
                                const updateData = toggleStock.setAttribute('data-recommended-products','on');
                                document.getElementById('recommended-products-'+id).innerHTML = '<i class="fas fa-toggle-on"></i>';
                                document.getElementById('recommended-products-'+id).style.color = "dodgerblue";
                        },
                        error: function (res) {
                                console.log('errors', res);
                        }
                });

        }

}

function bestsellerProducts(id){
        var bestsellerId = document.getElementById('bestseller-'+id);
        const data = bestsellerId.getAttribute('data-bestseller');

        console.log(bestsellerId);
        console.log(data);

        if(data === 'on'){
                $.ajax({
                        url: '/admin/crm/stock/bestseller-ajax',
                        type: 'get',
                        data:{
                                id: id,
                                on: 0
                        },
                        success: function(res){
                                bestsellerId.setAttribute('data-bestseller','off');
                                document.getElementById('bestseller-'+id).innerHTML = '<i class="fas fa-toggle-off"></i>';
                                document.getElementById('bestseller-'+id).style.color = "grey";
                        },
                        error: function (res) {
                                console.log('errors', res);
                        }
                });

        }else {
                $.ajax({
                        url: '/admin/crm/stock/bestseller-ajax',
                        type: 'get',
                        data:{
                                id: id,
                                on: 1
                        },
                        success: function(res){
                                bestsellerId.setAttribute('data-bestseller','on');
                                document.getElementById('bestseller-'+id).innerHTML = '<i class="fas fa-toggle-on"></i>';
                                document.getElementById('bestseller-'+id).style.color = "dodgerblue";
                        },
                        error: function (res) {
                                console.log('errors', res);
                        }
                });

        }

}

function newArrivalProducts(id){
        var toggleStock = document.getElementById('new-arrival-'+id);
        const data = toggleStock.getAttribute('data-new-arrival');
        if(data === 'on'){
                $.ajax({
                        url: '/admin/crm/stock/new-arrival-ajax',
                        type: 'get',
                        data:{
                                id: id,
                                on: 0
                        },
                        success: function(res){
                                const updateData = toggleStock.setAttribute('data-new-arrival','off');
                                document.getElementById('new-arrival-'+id).innerHTML = '<i class="fas fa-toggle-off"></i>';
                                document.getElementById('new-arrival-'+id).style.color = "grey";
                        },
                        error: function (res) {
                                console.log('errors', res);
                        }
                });

        }else {
                $.ajax({
                        url: '/admin/crm/stock/new-arrival-ajax',
                        type: 'get',
                        data:{
                                id: id,
                                on: 1
                        },
                        success: function(res){
                                const updateData = toggleStock.setAttribute('data-new-arrival','on');
                                document.getElementById('new-arrival-'+id).innerHTML = '<i class="fas fa-toggle-on"></i>';
                                document.getElementById('new-arrival-'+id).style.color = "dodgerblue";
                        },
                        error: function (res) {
                                console.log('errors', res);
                        }
                });

        }

}

function categoryFilter(id){
        $.ajax({
                url: '/admin/crm/stock/',
                type: 'get',
                data:{
                        category_id: id,
                },
                success: function(res){
                        // let inputValue = document.getElementById("category-id").value += id;
                        console.log('url',id)
                },
                error: function (res) {
                        console.log('errors', res);
                }
        });

}

