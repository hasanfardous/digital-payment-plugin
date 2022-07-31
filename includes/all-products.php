<?php
    // Settings array
    $srpwp_settings_datas = get_option('srpwp_settings_datas');

    // Table design
    $tbl_cell_bg_color = isset($srpwp_settings_datas['tbl_cell_bg_color']) ? sanitize_text_field($srpwp_settings_datas['tbl_cell_bg_color']) : '';
    $tbl_cell_txt_color = isset($srpwp_settings_datas['tbl_cell_txt_color']) ? sanitize_text_field($srpwp_settings_datas['tbl_cell_txt_color']) : '';

    // Table content
    $show_quick_view = isset($srpwp_settings_datas['show_quick_view']) ? sanitize_text_field($srpwp_settings_datas['show_quick_view']) : '';
    $show_review_column = isset($srpwp_settings_datas['show_review_column']) ? sanitize_text_field($srpwp_settings_datas['show_review_column']) : '';

    // Table controls
    $rows_per_page = isset($srpwp_settings_datas['rows_per_page']) ? sanitize_text_field($srpwp_settings_datas['rows_per_page']) : '8';
    $add_to_cart_btn_title = isset($srpwp_settings_datas['add_to_cart_btn_title']) ? sanitize_text_field($srpwp_settings_datas['add_to_cart_btn_title']) : '';
    $add_to_cart_btn_color = isset($srpwp_settings_datas['add_to_cart_btn_color']) ? sanitize_text_field($srpwp_settings_datas['add_to_cart_btn_color']) : '';
    $show_search_box = isset($srpwp_settings_datas['show_search_box']) ? sanitize_text_field($srpwp_settings_datas['show_search_box']) : '';
    $show_reset_btn = isset($srpwp_settings_datas['show_reset_btn']) ? sanitize_text_field($srpwp_settings_datas['show_reset_btn']) : '';
    $show_mini_cart = isset($srpwp_settings_datas['show_mini_cart']) ? sanitize_text_field($srpwp_settings_datas['show_mini_cart']) : '';

    // Products array
    $srpwp_products = [];
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
    );
    $loop = new WP_Query( $args );
    if ( !function_exists( 'wc_get_rating_html' ) ) { 
        require_once '/includes/wc-template-functions.php'; 
    } 
    $product_variations = [];
    while ( $loop->have_posts() ) : $loop->the_post();
        global $product;
        $product_id = $loop->post->ID;
        $cat_slugs = get_the_terms($product_id, 'product_cat');
        $srpwp_product_img = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'full' )[0];
        $srpwp_products[] = [
            'cat_slug' => $cat_slugs[0]->slug,
            'image' => '<a '.($show_quick_view=='checked'?'data-toggle="modal"':'').' data-target="#srpwp-'.$product_id.'"><img src="'.get_the_post_thumbnail_url($product_id, 'thumbnail').'"></a>
            <div class="modal fade bd-example-modal-lg" id="srpwp-'.$product_id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">'.get_the_title().'</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid px-0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <img src="'.$srpwp_product_img.'">
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center">
                                        '.$product->get_short_description().'
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-danger">'.__('Close', 'woo-product-table-cd').'</button>
                        </div>
                    </div>
                </div>
            </div>',
            'name' => '<a href="'.get_the_permalink().'">'.get_the_title().'</a>',
            'summary' => $product->get_short_description(),
            'reviews' => $product->get_average_rating() > 0 ? wc_get_rating_html( $product->get_average_rating() ) : '<div class="star-rating" role="img" aria-label="Rated 0 out of 5"><span style="width: 0;">Rated <strong class="rating">0</strong> out of 5</span></div>',
            'price' => $product->get_price_html(),
            'action' => '<input type="number" min="1" data-variation-id="" data-product-id="'. $product_id.'" class="srpwp-product-quantity" value="1"><a href="javascript:void(0)" class="btn btn-info srpwp-add-to-cart">'.$add_to_cart_btn_title.'</a>',
        ];
    endwhile;

    wp_reset_query();
    $srpwp_products_json = json_encode( $srpwp_products );
?>

<script>
    (function ($) {
        $(document).ready( function () {
            $('#srpwp-product-table').DataTable( {
                data: <?php echo $srpwp_products_json?>,
                createdRow: function(row, data, dataIndex){
                    $(row).find('td:not(:last-child) a').css({
                        'color': '<?php echo ($tbl_cell_txt_color!='')?$tbl_cell_txt_color:''?>',
                    });
                    $(row).find('td:last-child .srpwp-add-to-cart').css({
                        'background-color': '<?php echo $add_to_cart_btn_color?>',
                        'border-color': '<?php echo $add_to_cart_btn_color?>',
                    });
                    $(row).find('td').css({
                        'color': '<?php echo ($tbl_cell_txt_color!='')?$tbl_cell_txt_color:''?>',
                        'background-color': '<?php echo ($tbl_cell_bg_color!='')?$tbl_cell_bg_color:''?>',
                    });
                },
                pageLength: <?php echo $rows_per_page?>,
                searching: true,
                lengthChange: false,
                columns: [
                    { data: 'cat_slug', className: 'd-none' },
                    { data: 'image', orderable: false },
                    { data: 'name', className: 'srpwp-product-name' },
                    { data: 'summary', orderable: false },
                    <?php echo ($show_review_column=='checked'?'{ data: "reviews", orderable: false },':'')?>
                    { data: 'price' },
                    { data: 'action', orderable: false, width: '150px' },
                ]
            } );
           
            // Filter action
            var table = $('#srpwp-product-table');
            $('.srpwp-cat-filter').on( 'change', function () {
                var selectedVal = $(this).val();                
                table.DataTable().column(0).search(selectedVal).draw();
            });
            $('.srpwp-reset-btn').on('click', function () {
                console.log('reset clicked');
                $('.srpwp-cat-filter')[0].selectedIndex = 0;
                table.DataTable().column(0).search('').draw();
            });
        } );
})(jQuery);
</script>