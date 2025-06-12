jQuery(document).ready(function($) {
    // Product meta box tabs
    $('.product-meta-box-tabs a').on('click', function(e) {
        e.preventDefault();
        
        // Update active tab
        $('.product-meta-box-tabs li').removeClass('active');
        $(this).parent('li').addClass('active');
        
        // Show selected panel
        var targetTab = $(this).data('tab');
        $('.product-meta-box-panels .panel').removeClass('active');
        $('#' + targetTab).addClass('active');
    });
    
    // Update the _price field based on regular and sale price
    function updatePrice() {
        var regularPrice = parseFloat($('#_regular_price').val()) || 0;
        var salePrice = parseFloat($('#_sale_price').val()) || 0;
        
        if (salePrice > 0 && salePrice < regularPrice) {
            return salePrice;
        } else {
            return regularPrice;
        }
    }
    
    // Update price on form submit for products 
    if ($('body').hasClass('post-type-product')) {
        $('form#post').on('submit', function() {
            var price = updatePrice();
            $('<input>').attr({
                type: 'hidden',
                name: '_price',
                value: price
            }).appendTo('form#post');
        });
    }
});