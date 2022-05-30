<?php

use Premmerce\Wishlist\WishlistPlugin;

global $wishlistPage;
global $wishlist_current;
$wishlistPage = true;
?>
<?php

global $premmerce_wishlist_frontend;

?>



<?php if($_GET['show'] == 'all') { ?>
<?php if(count($wishlists) == 0): ?>

<div class="typo">
    <h2>
        <?php _e('Your wishlist is empty', WishlistPlugin::DOMAIN); ?>
    </h2>
    <p>
        <?php _e('Once added new items, you\'ll be able to continue shopping any time and also share the information about the purchase with your friends.', WishlistPlugin::DOMAIN); ?>
    </p>
</div>

<?php else : ?>
<style>
    .entry-title {
        display: none;
    }

    li.product {
        margin-top: 20px !important;
    }

    span.et_shop_image img {
    width: 100%;
}

@media (min-width: 981px) {
    #left-area {
        width: 100%;
    }
}

@media (min-width: 960px) {
    .wl-product-list > * {
        width: 33.3% !important;
    }
}

@media (max-width: 980px) {
    .et_right_sidebar #left-area {
        padding: 30px;
    }
}

#left-area ul, .entry-content ul, .et-l--body ul, .et-l--footer ul, .et-l--header ul {
    padding: 0 0 0px 0em !important;
}

</style>
<script>
jQuery(document).ready(function ($) {
    
$(function() {
  //select all checkboxes
  $(".select_all").on('click', function() { //"select all" change 
    $(".orderforsample").data('checked', !$(".orderforsample").data('checked')).prop('checked', $(".orderforsample").data('checked')); //change all ".checkbox" checked status
    if ($(".orderforsample").data('checked')) {
      this.innerHTML = "Remove All";
    } else {
      this.innerHTML = "Select All";
    }
  });

  $(".select_all_mobile").click(function() { //"select all" change 
    $(".orderforsample").data('checked', !$(".orderforsample").data('checked')).prop('checked', $(".orderforsample").data('checked')); //change all ".checkbox" checked status
    if ($(".orderforsample").data('checked')) {
        this.innerHTML = '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>';
    } else {
        this.innerHTML = '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true" fill-rule="evenodd" clip-rule="evenodd"><path d="M21 6.285l-11.16 12.733-6.84-6.018 1.319-1.49 5.341 4.686 9.865-11.196 1.475 1.285z"/></svg>';
    }
  });

});

$(".share-project-btn").click(function(event) {

    $('.orderforsample:checkbox:checked').each(function(){
        $("#order_items_names").append('<li class="w-full text-gray-900 bg-gray-50 px-4 py-2 border-b border-gray-200">• ' + $(this).val() + '</li>');
    });

    if(!$('#order_items_names').is(':empty')) {
        $("#authentication-modal").removeClass("hidden");
    }

    if($('#order_items_names').is(':empty')) {
        alert('Please select at least one product from the list');
    }
    
});

$("#close-order").click(function(event){
    $("#authentication-modal").addClass("hidden");
    document.querySelector('[id="order_items_names"]').innerHTML = "";
});

});
</script>
<script>
jQuery(document).ready(function ($) {

    jQuery('#sub').submit(function(e){
    e.preventDefault();

    var email =  jQuery('#email').val();
    var name =  jQuery('#name').val();
    var note =  jQuery('#message').val();
    var company_name =  jQuery('#company_name').val();
    var street_address =  jQuery('#street_address').val();
    var phone =  jQuery('#phone').val();
    var city =  jQuery('#city').val();
    var state =  jQuery('#state').val();
    var zipcode =  jQuery('#zipcode').val();
    var order_items_names = $("input:checkbox:checked").map(function(){ return $(this).attr("value"); }).toArray();

jQuery.ajax({
    url: '<?php echo admin_url('admin-ajax.php'); ?>',
    type: "POST",
    cache: false,
    
    data:{ 
        action: 'send_email', 
        name: name,
        email: email,
        note: note,
        company_name: company_name,
        street_address: street_address,
        city: city,
        state: state,
        zipcode: zipcode,
        phone: phone,
        order_items_names: order_items_names,
        isOrder: true,
    },
    
    success:function(res){
        alert("Project Board Sent Successfully!");
    }

}); 
});
});
</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  important: true,
}
</script>
<div class="hidden z-[99999] fixed top-0 left-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto bg-slate-900/40"
id="authentication-modal" tabindex="-1" aria-labelledby="exampleModalScrollableLabel" aria-hidden="true">
<div class="max-w-lg my-6 mx-auto relative w-auto pointer-events-none">
<div
  class="max-h-full overflow-hidden border-none relative flex flex-col w-full pointer-events-auto rounded-md outline-none text-current">
  <div class="relative bg-white rounded-lg shadow mx-5">
  
  <button id="close-order" type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>

        <div class="py-6 px-6 lg:px-8">
            <h3 class="mb-4 text-xl font-medium text-gray-900">Order Form</h3>
            <form class="space-y-6" method="POST" action="" id="sub">
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Your Email</label>
                    <input type="text" name="email" id="email" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="" >
                </div>
                <div>
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900">Your Note</label>
                    <textarea id="message" name="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
                </div>

                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <input type="text" name="name" id="name" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your fullname" required="" >
                </div>
                <div>
                    <label for="company_name" class="block mb-2 text-sm font-medium text-gray-900">Company Name</label>
                    <input type="text" name="company_name" id="company_name" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Company Name" required="" >
                </div>

                <div>
                    <label for="street_address" class="block mb-2 text-sm font-medium text-gray-900">Street Address</label>
                    <input type="text" name="street_address" id="street_address" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Phone Number" required="" >
                </div>          
                <div class="grid xl:grid-cols-2 xl:gap-6">
                    <div class="relative z-0 w-full group">
                        <label for="city" class="block mb-2 text-sm font-medium text-gray-900">City</label>
                        <input type="text" name="city" id="city" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your City">
                    </div>
                    <div class="relative z-0 w-full group">
                        <label for="state" class="block mb-2 text-sm font-medium text-gray-900">State</label>
                        <input type="text" name="state" id="state" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your State" required="">
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="zipcode" class="block mb-2 text-sm font-medium text-gray-900">Zip</label>
                        <input type="number" name="zipcode" id="zipcode" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Zipcode" required="">
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">Phone</label>
                        <input type="number" name="phone" id="phone" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Phone Number" required="">
                    </div>
                </div>
                <div>
                <ul class="w-full list-none text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="list-none w-full px-4 py-2 border-b text-green-700 bg-green-100 border-gray-200 rounded-t-lg dark:border-gray-600 font-bold">List of Samples Being Requested:</li>
                    <div id="order_items_names"></div>
                </ul>
                </div>
                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Share now</button>
        </form>
    </div>
  </div>
</div>
</div>
</div>

<?php do_action("premmerce_wishlist_page_after_header_fields", $wl, $onlyView); ?>
<?php foreach($wishlists as $wl): ?>
    <?php $wishlist_current = $wl; ?>
    <section class="content__row woocommerce">
        <div class="wl-frame">
                <?php do_action("premmerce_wishlist_page_before_header_fields", $wl, $onlyView); ?>
                <?php
                    $wishlist_url = esc_url(home_url($pageSlug));
                    $wishlist_url .= (parse_url($wishlist_url, PHP_URL_QUERY) ? '&' : '?') . 'key=' . $wl['wishlist_key'];
                ?>
                <div class="relative bg-white">
  <div class="max-w-7xl mx-auto md:rounded-lg md:shadow-lg md:ring-1 md:ring-black md:ring-opacity-5 px-4 sm:px-6 my-5">
    <div class="flex justify-between items-center py-6 md:justify-start md:space-x-10">
      <div class="flex justify-start lg:w-0 lg:flex-1">
      <a class="wl-frame__title-link font-sans text-lg font-medium text-slate-900 dark:text-slate-200 no-underline" <?php if(!$onlyView): ?> href="<?php echo $wishlist_url ?>" <?php endif; ?> rel="nofollow"><?php echo $wl['name']; ?></a>
      </div>
      <div class="-mr-2 -my-2 md:hidden">
        <button id="close-share" type="button" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-expanded="false">
          <span class="sr-only">Open menu</span>
          <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
      <div class="hidden md:flex items-center justify-end md:flex-1 lg:w-0">
        <a id="select_all" class="select_all ml-8 no-underline whitespace-nowrap bg-gray-800 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white">Select All</a>
        <a id="share-project-btn" class="share-project-btn ml-2 no-underline whitespace-nowrap inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">Order Now</a>
      </div>
    </div>
  </div>
  <div class="absolute top-0 inset-x-0 p-2 transition transform origin-top-right md:hidden">
    <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white divide-y-2 divide-gray-50">
      <div class="pt-5 pb-6 px-5">
        <div class="flex items-center justify-between">
          <div>
            <a class="wl-frame__title-link font-sans text-sm text-slate-900 dark:text-slate-200 no-underline" <?php if(!$onlyView): ?> href="<?php echo $wishlist_url ?>" <?php endif; ?> rel="nofollow"><?php echo $wl['name']; ?></a>
          </div>
          <div class="-mr-2">
            <button type="button" class="select_all_mobile bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
              <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true" fill-rule="evenodd" clip-rule="evenodd"><path d="M21 6.285l-11.16 12.733-6.84-6.018 1.319-1.49 5.341 4.686 9.865-11.196 1.475 1.285z"/></svg>
            </button>
            <a class="share-project-btn bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
              <svg class="h-6 w-6" fill="currentColor" width="24" aria-hidden="true" class="h-6 w-6" height="24" viewBox="0 0 24 24"><path d="M19.5 3c-2.485 0-4.5 2.015-4.5 4.5s2.015 4.5 4.5 4.5 4.5-2.015 4.5-4.5-2.015-4.5-4.5-4.5zm-.5 7v-2h-2v-1h2v-2l3 2.5-3 2.5zm-3.5 8c.828 0 1.5.671 1.5 1.5s-.672 1.5-1.5 1.5-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5zm-15.5-15l.743 2h1.929l3.474 12h13.239l1.307-3.114c-.387.072-.785.114-1.192.114-3.59 0-6.5-2.91-6.5-6.5 0-.517.067-1.018.181-1.5h-5.993l2.542 9h-2.103l-3.431-12h-4.196zm9 16.5c0 .829.672 1.5 1.5 1.5s1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5s-1.5.672-1.5 1.5z"/></svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
            <div class="wl-frame__inner wl-content__row wl-content__row--sm">
                <?php if($wl['products']): ?>
                    <?php $productsIds = array_map(function($product){
                        return $product->get_ID();
                    }, $wl['products']);

                    $query = new WP_Query([
                        'post_type' => 'product',
                        'post__in'  => $productsIds,
                    ]); ?>
                    <ul class="wl-product-list products">
                        <?php while($query->have_posts()) : ?>
                            <?php $query->the_post(); ?>
                            <?php wc_get_template_part('content', 'product'); ?>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    </ul>
                <?php else: ?>
                    <div class="typo">
                        <h3>
                            <?php _e('This list is empty', WishlistPlugin::DOMAIN); ?>
                        </h3>
                        <p>
                            <?php _e('Once added new items, you\'ll be able to continue shopping any time and also share the information about the purchase with your friends.', WishlistPlugin::DOMAIN); ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </section>

<?php endforeach ?>

<?php endif; ?>
<?php $wishlistPage = false; ?>
<? }
?>



<?php if(count($wishlists) == 0): ?>

<div class="typo">
    <h2><?php _e('Your wishlist is empty', WishlistPlugin::DOMAIN); ?></h2>
</div>
    
<?php else : ?>
<? if(!$_GET['key'] && !$_GET['show']) { ?>
<style>
        .image-grid-wrapper {cursor:pointer;overflow: hidden; margin-bottom: .8rem; border-radius: .5rem;border: 1px solid #f2f2f2;  box-shadow: 1px 1px 10px 0 rgba(0,0,0,.23); transition: box-shadow .3s ease-out;}
        .image-grid { align-content: flex-start;display: flex;  flex-wrap: wrap;height: 13.4rem;margin: -.5rem;  background-color: #f2f2f2;}    
        .image-wrapper {height:auto; width: 33.33%;}
        .product-board-card.col-xl-4 {  flex: 0 0 30%; max-width: 100%; padding: 0px 8px 0px 8px;}
        span.right-span {float: right;}
        .image-grid img { display: block;}
        p.card-text { display: inline-block;}
        .woocommerce-MyAccount-content {display: none;}
        .group-of-board {order: 2;width: 100%;display: flex;flex-wrap: wrap;}
        .entry-content {flex-flow: row;}
        .woocommerce nav.woocommerce-MyAccount-navigation { width: 100%;}
        .product-board-card.col-xl-4{
            margin-bottom:20px;
        }
        .entry-content .woocommerce{
            width:180px
        }
        .product-board-card img { height: 78px;width: 100%;object-fit: cover; }
        .singleselecallborads { margin-right: 4px; }

        .project_btns {
            display: flex;
            margin-bottom: 15px;
            justify-content: center;
        }

        .project_btns .button-btns {
            margin-top: 15px;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            border-radius: 10px;
            cursor: pointer;
            margin-left: 3px;
        }

        @media (min-width: 981px) {
        #left-area {
            width: 100% !important;
            padding-bottom: 23px;
            }
        }

        @media (max-width: 768px) {
        .product-board-card.col-xl-4 {
            flex: 0 0 100%;
            }
        .image-grid {
            align-content: unset;
            display: grid;
            flex-wrap: unset;
            height: 13.4rem;
            margin: -0.5rem;
            background-color: #f2f2f2;
            }
        .project_btns .button-btns {
            padding: 5px 10px;
            }
        }

        h1.entry-title.main_title {
            display: none;
        }
        h1.entry-title {
            display: none;
        }

        @media (max-width: 980px) {
            .et_right_sidebar #left-area {
                padding: 15px;
            }
        }

        @media (max-width: 580px) {
            .image-wrapper {
                width: 33.33%;
            }

            .image-grid {
                align-content: unset;
                display: flex;
                flex-wrap: wrap;
            }
        }


    </style>

<script>
jQuery(document).ready(function ($) {

$(function() {
  //select all checkboxes
  $("#select-all-btn").click(function() { //"select all" change 
    $(".project_boards_data").data('checked', !$(".project_boards_data").data('checked')).prop('checked', $(".project_boards_data").data('checked')); //change all ".checkbox" checked status
    if ($(".project_boards_data").data('checked')) {
      this.innerHTML = "Remove All";
    } else {
      this.innerHTML = "Select All";
    }
  });
});

$("#remove-all-btn").click(function() {
$('[type=checkbox]').prop("checked", false);
});

$("#share-project-btn").click(function(event) {

    event.preventDefault();
    var searchIDs = $("input:checkbox:checked").map(function(){
        return $(this).attr("data-title");
    }).toArray();

    var searchIDs1 = $("input:checkbox:checked").map(function(){
        var url_string = this.value;
        var url = new URL(url_string);
        var filteredLinks = url.searchParams.get("key");
        return filteredLinks;
    }).toArray();

    console.log(searchIDs1);
    console.log(searchIDs);


    for(i=0;i<searchIDs.length;i++){
        $("#projectboard-names").append('<li class="w-full text-gray-900 bg-gray-50 px-4 py-2 border-b border-gray-200"><a class="no-underline" id="project-urls" href="'+searchIDs1[i]+'">' + searchIDs[i] + '</a></li>');
    }

    if(!$('#projectboard-names').is(':empty')) {
        $("#authentication-modal").removeClass("hidden");
    }

    if($('#projectboard-names').is(':empty')) {
        alert('Please select at least one Project Board');
    }

});

$("#cancel-modal").click(function(){
    $("#authentication-modal").addClass("hidden");
    document.querySelector('[id=projectboard-names]').innerHTML = "";
});

jQuery('#sub').submit(function(e){

e.preventDefault();
var name =  jQuery('#name').val();
var email =  jQuery('#email').val();
var company_name =  jQuery('#company_name').val();
var phone =  jQuery('#phone').val();
var message =  jQuery('#message').val();
var pricing = $("input[name='coloredradio']:checked").val();
var project_board_urls = $("input:checkbox:checked").map(function()
{
    var url_string = this.value;
    var url = new URL(url_string);
    var filteredLinks = url.searchParams.get("key");
    return filteredLinks; 

}).toArray().toString();
console.log(project_board_urls);
var project_board_names = $("input:checkbox:checked").map(function(){ return $(this).attr("data-title"); }).toArray().toString();


jQuery.ajax({
    url: '<?php echo admin_url('admin-ajax.php'); ?>',
    type: "POST",
    cache: false,
    
    data:{ 
        action: 'send_email', 
        email: email,
        name: name,
        company_name: company_name,
        phone: phone,
        message: message,
        pricing: pricing,
        project_board_urls: project_board_urls,
        project_board_names: project_board_names,
    },
    
    success:function(res){
        alert("Project Board Sent Successfully!");
    }

}); 
});


});
</script>

<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  important: true,
}
</script>

<div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="popup-modal">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
            </button>
            <div class="p-6 text-center">
                <svg class="mx-auto mb-4 w-14 h-14 text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this product?</h3>
                <button data-modal-toggle="popup-modal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Yes, I'm sure
                </button>
                <button data-modal-toggle="popup-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="hidden z-[99999] fixed top-0 left-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto bg-slate-900/40"
id="authentication-modal" tabindex="-1" aria-labelledby="exampleModalScrollableLabel" aria-hidden="true">
<div class="max-w-lg my-6 mx-auto relative w-auto pointer-events-none">
<div
  class="max-h-full overflow-hidden border-none relative flex flex-col w-full pointer-events-auto rounded-md outline-none text-current">
  <div class="relative bg-white rounded-lg shadow mx-5">
  
  <button id="cancel-modal" type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>

        <div class="py-6 px-6 lg:px-8">
            <h3 class="mb-4 text-xl font-medium text-gray-900">Share Project Board</h3>
            <form class="space-y-6" method="POST" action="" id="sub">
                <div>
                    <label for="email1" class="block mb-2 text-sm font-medium text-gray-900">From email</label>
                    <input type="text" name="email1" id="email1" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="" >
                </div>

                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Recipient Email</label>
                    <input type="text" name="email" id="email" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com [multiple recipients by comma (,)]" required="" >
                </div>

                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <input type="text" name="name" id="name" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your fullname" required="" >
                </div>

                <div>
                    <label for="company_name" class="block mb-2 text-sm font-medium text-gray-900">Company Name</label>
                    <input type="text" name="company_name" id="company_name" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Company Name" required="" >
                </div>

                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <input type="number" name="phone" id="phone" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Phone Number" required="" >
                </div>
                
                <div>
                <label for="message" class="block mb-2 text-sm font-medium text-gray-900">Your Note</label>
                <textarea id="message" name="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
                </div>

                <div>
                <div class="flex flex-wrap">
                    <div class="flex items-center mr-4">
                        <input id="red-radio" type="radio" value="show" name="coloredradio" id="coloredradio" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="red-radio" class="ml-2 text-sm font-medium text-gray-900">Show Pricing</label>
                    </div>
                    
                    <div class="flex items-center mr-4">
                        <input id="green-radio" type="radio" checked value="hide" name="coloredradio" id="coloredradio" class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="green-radio" class="ml-2 text-sm font-medium text-gray-900">Hide Pricing</label>
                    </div>
                </div>
                </div>


                <div>
                <ul class="w-full list-none text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="list-none w-full px-4 py-2 border-b text-green-700 bg-green-100 border-gray-200 rounded-t-lg dark:border-gray-600 font-bold">List of Project Board Selected:</li>
                    <div id="projectboard-names"></div>
                </ul>
                </div>
                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Share now</button>
        </form>
    </div>
  </div>
</div>
</div>
</div>


<div class="project_btns"><div id="select-all-btn" class="button-btns bg-gray-800">Select All</div><div id="share-project-btn" class="button-btns bg-gray-800">Share Project Board</div></div>
<div class='group-of-board'>
<?php foreach($wishlists as $wl): ?>
    <?php $wishlist_current = $wl; ?>
    <?php 
    
    $wishlist_url = esc_url(home_url($pageSlug));
    $wishlist_url .= (parse_url($wishlist_url, PHP_URL_QUERY) ? '&' : '?') . 'key=' . $wl['wishlist_key'];
    
    ?>
    <section class="product-board-card col-12 col-md-6 col-xl-4">
        <div class="product-board-card col-12 col-md-6 col-xl-4">
            <div class="image-grid-wrapper" onclick="window.location.href='<?= $wishlist_url; ?>'">
                <div class="image-grid">
                <?php if($wl['products']): ?>
                    <?php $productsIds = array_map(function($product){
                        return $product->get_ID();
                    }, $wl['products']);



                    $query = new WP_Query([
                        'post_type' => 'product',
                        'post__in'  => $productsIds,
                    ]); ?>
                        <?php while($query->have_posts()) : ?>
                            <?php $query->the_post(); ?>
                            <?php 
                                $img_url = get_the_post_thumbnail_url(get_the_ID()); 
                                echo '<div class="image-wrapper"><img data-src="'.$img_url.'" class="" data-object-fit="" src="'.$img_url.'"></div>';
                            ?>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                        <?php 
                        ?>
                <?php else: ?>
                    <div class="typo">
                        <h3>
                            <?php _e('This list is empty', WishlistPlugin::DOMAIN); ?>
                        </h3>
                        <p>
                            <?php _e('Once added new items, you\'ll be able to continue shopping any time and also share the information about the purchase with your friends.', WishlistPlugin::DOMAIN); ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php 
            echo '<a class="wl-frame__title-link"'; if(!$onlyView): echo ' href="'.$wishlist_url.'"'; endif; echo ' rel="nofollow" style="display:block;">'.$wl['name'].'</a><input type="checkbox" data-href="'.$wishlist_url.'" data-title="'.$wl['name'].'" value="'.$wishlist_url.'" name="project_boards_data" class="project_boards_data" />';
            echo '<p class="card-text"> '.$query->found_posts.' Products</p><span class="right-span"><button class="wl-frame__header-link" data-modal-wishlist="'.wp_nonce_url(home_url($apiUrlWishListRename . $wl['wishlist_key']), 'wp_rest').'">Rename</button> / <a class="wl-frame__header-link" href="'.wp_nonce_url(home_url($apiUrlWishListDelete . $wl['wishlist_key']), 'wp_rest').'">Delete</a></span>';
        ?>
  </section>
<?php endforeach ?>

</div>
<? } ?>

<? if($_GET['key'] && !$_GET['show'] ) { ?>
    <style>
.project-breadcrumb {
display: flex;
justify-content: space-between;
align-items: center;
background-color: #F0F0F0;
padding: 15px;
margin-bottom: 20px;
}
.ordersmpteldive2 a {
color: #2ea3f2;
font-weight: 700;
}
.ordersmpteldive .allorderforsample {
vertical-align: middle;
margin: -2px 0 0 !important;
cursor: pointer;
color:#2ea3f2 !important;
}
.ordersmpteldive{color:#2ea3f2 !important;}
.wl-frame__header-nav:before {
padding: 0 4px;
}
.ordersmpteldive2 span.arrow {
padding: 0 4px;
}
.ordersmpteldive2 .all-sample-order a {
color: #2ea3f2;
text-decoration: none;
font-weight: 400;
}

span.all-sample-order-mail.all-sample-ordernew:after {
content: '|';
padding: 0 7px;
color:#2ea3f2 !important;
color:#2ea3f2;
}

span.share-btn:before{
content: '|';
padding: 0 7px;
color:#2ea3f2 !important;
color:#2ea3f2;
}

@media (min-width: 981px) { 
        #left-area {
            width: 100% !important;
            padding-bottom: 23px;
            }
        }

        h2.woocommerce-loop-product__title {
font-size: 15px;
font-weight: 500;
}

span.price {
    display: <?= $_GET['pricing'] == 'show' ? 'block !important' : ' none !important' ?> ;
}

.wishlist-btn-wrap {
display: none !important;
}

h1.entry-title.main_title {
            display: none;
        }

        h1.entry-title {
            display: none;
        }

span.et_shop_image img {
    width: 100%;
}

@media (min-width: 960px) {
    .wl-product-list > * {
        width: 33.3% !important;
    }
}

@media (max-width: 980px) {
    .et_right_sidebar #left-area {
        padding: 30px;
    }
}

#left-area ul, .entry-content ul, .et-l--body ul, .et-l--footer ul, .et-l--header ul {
    padding: 0 0 0px 0em !important;
}

</style>
<script>
jQuery(document).ready(function ($) {
    
$(function() {
  //select all checkboxes
  $(".select_all").on('click', function() { //"select all" change 
    $(".orderforsample").data('checked', !$(".orderforsample").data('checked')).prop('checked', $(".orderforsample").data('checked')); //change all ".checkbox" checked status
    if ($(".orderforsample").data('checked')) {
      this.innerHTML = "Remove All";
    } else {
      this.innerHTML = "Select All";
    }
  });

  $(".select_all_mobile").click(function() { //"select all" change 
    $(".orderforsample").data('checked', !$(".orderforsample").data('checked')).prop('checked', $(".orderforsample").data('checked')); //change all ".checkbox" checked status
    if ($(".orderforsample").data('checked')) {
        this.innerHTML = '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>';
    } else {
        this.innerHTML = '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true" fill-rule="evenodd" clip-rule="evenodd"><path d="M21 6.285l-11.16 12.733-6.84-6.018 1.319-1.49 5.341 4.686 9.865-11.196 1.475 1.285z"/></svg>';
    }
  });

});

$(".share-project-btn").click(function(event){
    $('.orderforsample:checkbox:checked').each(function(){
        $("#order_items_names").append('<li class="w-full text-gray-900 bg-gray-50 px-4 py-2 border-b border-gray-200">• ' + $(this).val() + '</li>');
    });

    if(!$('#order_items_names').is(':empty')) {
        $("#authentication-modal").removeClass("hidden");
    }

    if($('#order_items_names').is(':empty')) {
        alert('Please select at least one product from the list');
    }

});

$("#close-order").click(function(event){
    $("#authentication-modal").addClass("hidden");
    document.querySelector('[id="order_items_names"]').innerHTML = "";
});

});
</script>
<script>
jQuery(document).ready(function ($) {
jQuery('#sub').submit(function(e){

    e.preventDefault();

    var email =  jQuery('#email').val();
    var name =  jQuery('#name').val();
    var note =  jQuery('#message').val();
    var company_name =  jQuery('#company_name').val();
    var street_address =  jQuery('#street_address').val();
    var phone =  jQuery('#phone').val();
    var city =  jQuery('#city').val();
    var state =  jQuery('#state').val();
    var zipcode =  jQuery('#zipcode').val();
    var order_items_names = $("input:checkbox:checked").map(function(){ return $(this).attr("value"); }).toArray();

jQuery.ajax({
    url: '<?php echo admin_url('admin-ajax.php'); ?>',
    type: "POST",
    cache: false,
    
    data:{ 
        action: 'send_email', 
        name: name,
        email: email,
        note: note,
        company_name: company_name,
        street_address: street_address,
        city: city,
        state: state,
        zipcode: zipcode,
        phone: phone,
        order_items_names: order_items_names,
        isOrder: true,
    },
    
    success:function(res){
        alert("Project Board Sent Successfully!");
    }

}); 
});
});
</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  important: true,
}
</script>
<div class="hidden z-[999] fixed top-0 left-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto bg-slate-900/40"
id="authentication-modal" tabindex="-1" aria-labelledby="exampleModalScrollableLabel" aria-hidden="true">
<div class="max-w-lg my-6 mx-auto relative w-auto pointer-events-none">
<div
  class="max-h-full overflow-hidden border-none relative flex flex-col w-full pointer-events-auto rounded-md outline-none text-current">
  <div class="relative bg-white rounded-lg shadow mx-5">
  
  <button id="close-order" type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>

        <div class="py-6 px-6 lg:px-8">
            <h3 class="mb-4 text-xl font-medium text-gray-900">Order Form</h3>
            <form class="space-y-6" method="POST" action="" id="sub">
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Your Email</label>
                    <input type="text" name="email" id="email" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="" >
                </div>
                <div>
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900">Your Note</label>
                    <textarea id="message" name="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
                </div>

                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <input type="text" name="name" id="name" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your fullname" required="" >
                </div>
                <div>
                    <label for="company_name" class="block mb-2 text-sm font-medium text-gray-900">Company Name</label>
                    <input type="text" name="company_name" id="company_name" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Company Name" required="" >
                </div>

                <div>
                    <label for="street_address" class="block mb-2 text-sm font-medium text-gray-900">Street Address</label>
                    <input type="text" name="street_address" id="street_address" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Phone Number" required="" >
                </div>          
                <div class="grid xl:grid-cols-2 xl:gap-6">
                    <div class="relative z-0 w-full group">
                        <label for="city" class="block mb-2 text-sm font-medium text-gray-900">City</label>
                        <input type="text" name="city" id="city" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your City">
                    </div>
                    <div class="relative z-0 w-full group">
                        <label for="state" class="block mb-2 text-sm font-medium text-gray-900">State</label>
                        <input type="text" name="state" id="state" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your State" required="">
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="zip" class="block mb-2 text-sm font-medium text-gray-900">Zip</label>
                        <input type="number" name="zipcode" id="zipcode" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Zipcode" required="">
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">Phone</label>
                        <input type="number" name="phone" id="phone" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Phone Number" required="">
                    </div>
                </div>
                <div>
                <ul class="w-full list-none text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="w-full px-4 py-2 border-b text-green-700 bg-green-100 border-gray-200 rounded-t-lg dark:border-gray-600 font-bold">List of Samples Being Requested:</li>
                    <div id="order_items_names"></div>
                </ul>
                </div>
                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Share now</button>
        </form>
    </div>
  </div>
</div>
</div>
</div>


<?php foreach($wishlists as $wl): ?>
    <?php $wishlist_current = $wl; ?>
    <section class="content__row woocommerce">
    <div class="relative bg-white">
  <div class="max-w-7xl mx-auto md:rounded-lg md:shadow-lg md:ring-1 md:ring-black md:ring-opacity-5 px-4 sm:px-6 my-5">
    <div class="flex justify-between items-center py-6 md:justify-start md:space-x-10">
      <div class="flex justify-start lg:w-0 lg:flex-1">
      <a class="wl-frame__title-link font-sans text-lg font-medium text-slate-900 dark:text-slate-200 no-underline" <?php if(!$onlyView): ?> href="<?php echo $wishlist_url ?>" <?php endif; ?> rel="nofollow"><?php echo $wl['name']; ?></a>
      </div>
      <div class="-mr-2 -my-2 md:hidden">
        <button id="close-share" type="button" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-expanded="false">
          <span class="sr-only">Open menu</span>
          <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
      <div class="hidden md:flex items-center justify-end md:flex-1 lg:w-0">
        <a id="select_all" class="select_all ml-8 no-underline whitespace-nowrap bg-gray-800 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white">Select All</a>
        <a id="share-project-btn" class="share-project-btn ml-2 no-underline whitespace-nowrap inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">Order Now</a>
      </div>
    </div>
  </div>
  <div class="absolute top-0 inset-x-0 p-2 transition transform origin-top-right md:hidden">
    <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white divide-y-2 divide-gray-50">
      <div class="pt-5 pb-6 px-5">
        <div class="flex items-center justify-between">
          <div>
            <a class="wl-frame__title-link font-sans text-sm text-slate-900 dark:text-slate-200 no-underline" <?php if(!$onlyView): ?> href="<?php echo $wishlist_url ?>" <?php endif; ?> rel="nofollow"><?php echo $wl['name']; ?></a>
          </div>
          <div class="-mr-2">
            <button type="button" class="select_all_mobile bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
              <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true" fill-rule="evenodd" clip-rule="evenodd"><path d="M21 6.285l-11.16 12.733-6.84-6.018 1.319-1.49 5.341 4.686 9.865-11.196 1.475 1.285z"/></svg>
            </button>
            <a class="share-project-btn bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
              <svg class="h-6 w-6" fill="currentColor" width="24" aria-hidden="true" class="h-6 w-6" height="24" viewBox="0 0 24 24"><path d="M19.5 3c-2.485 0-4.5 2.015-4.5 4.5s2.015 4.5 4.5 4.5 4.5-2.015 4.5-4.5-2.015-4.5-4.5-4.5zm-.5 7v-2h-2v-1h2v-2l3 2.5-3 2.5zm-3.5 8c.828 0 1.5.671 1.5 1.5s-.672 1.5-1.5 1.5-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5zm-15.5-15l.743 2h1.929l3.474 12h13.239l1.307-3.114c-.387.072-.785.114-1.192.114-3.59 0-6.5-2.91-6.5-6.5 0-.517.067-1.018.181-1.5h-5.993l2.542 9h-2.103l-3.431-12h-4.196zm9 16.5c0 .829.672 1.5 1.5 1.5s1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5s-1.5.672-1.5 1.5z"/></svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
            <div class="wl-frame__inner wl-content__row wl-content__row--sm">
                <?php if($wl['products']): ?>
                    <?php $productsIds = array_map(function($product){
                        return $product->get_ID();
                    }, $wl['products']);

                    $query = new WP_Query([
                        'post_type' => 'product',
                        'post__in'  => $productsIds,
                    ]); ?>
                    <ul class="wl-product-list products">
                        <?php while($query->have_posts()) : ?>
                            <?php $query->the_post(); ?>
                                <?php wc_get_template_part('content', 'product'); ?>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    </ul>
                <?php else: ?>
                    <div class="typo">
                        <h3><?php _e('This list is empty', WishlistPlugin::DOMAIN); ?></h3>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </section>

<?php endforeach ?>
<? } ?>
<?php endif; ?>
<?php $wishlistPage = false; ?>