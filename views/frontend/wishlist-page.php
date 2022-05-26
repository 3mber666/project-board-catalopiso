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
    span.price {
    display: <?= $_GET['pricing'] == 'show' ? 'block !important' : ' none !important' ?> ;
}

@media (min-width: 981px) {
        #left-area {
            width: 100% !important;
            padding-bottom: 23px;
            }
        }

        h1.entry-title.main_title {
    display: none;
}

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
	
	 h2.woocommerce-loop-product__title {
font-size: 15px;
font-weight: 500;
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
</style>
	<?php foreach($wishlists as $wl): ?>
		<?php $wishlist_current = $wl; ?>
        <section class="content__row woocommerce">
        <div class="project-breadcrumb">
                <?php do_action("premmerce_wishlist_page_before_header_fields", $wl, $onlyView); ?>
                <!-- Name -->
                <?php
                    $wishlist_url = esc_url(home_url($pageSlug));
                    $wishlist_url .= (parse_url($wishlist_url, PHP_URL_QUERY) ? '&' : '?') . 'key=' . $wl['wishlist_key'];
                ?>
                <div class="wl-frame__title">
                    <a class="wl-frame__title-link zxcvxcvds"
                        <?php if(!$onlyView): ?>
                            href="<?php echo $wishlist_url ?>"
                        <?php endif; ?>
                       rel="nofollow">
                        <?php echo $wl['name']; ?>
                    </a>
                     <div class="wl-frame__header-nav">
                        <button class="wl-frame__header-link"
                                data-modal-wishlist="<?php echo wp_nonce_url(home_url($apiUrlWishListRename . $wl['wishlist_key']), 'wp_rest'); ?>">
                            <?php _e('Rename', WishlistPlugin::DOMAIN); ?>
                        </button>
                    </div>
                    <!-- Delete -->
                    <div class="wl-frame__header-nav">
                        <a class="wl-frame__header-link"
                           href="<?php echo wp_nonce_url(home_url($apiUrlWishListDelete . $wl['wishlist_key']), 'wp_rest'); ?>">
                            <?php _e('Delete', WishlistPlugin::DOMAIN); ?>
                        </a>
                    </div>
                </div>
                
                      
                   <?php
                     if(is_user_logged_in())
                    {
                        
                    ?>
                    <div class="ordersmpteldive">
                        <input class="allorderforsample" name="allorderforsample" type="checkbox" value="1" id="parent"> Select all          
                      </div>
                      <div class="ordersmpteldive2">
                       <span class="share-btn" style="float: right"><a href="javascript:void(0);">Share</a></span>
                       <span class="all-sample-order" style="float:right;"><a href="javascript:void(0);"><b>Add To Cart</b></a></span>
                       <span class="all-sample-order-mail all-sample-ordernew" id="order-items-now" style="float:right;"><a href="javascript:void(0);">Order Now</a></span>
                       
                      </div>
                      <div class="arragboaddiv">
                      <span class="arrange-btn" style="float: right"><a href="javascript:void(0);">Arrange The Board</a></span>
                      </div>
                    <?php
                    }
                    if(isset($_GET['key']) && !empty($_GET['key']) && !is_user_logged_in())
                    
                    {
                    ?>
                    <div class="ordersmpteldive">
                        <input class="allorderforsample" name="allorderforsample" type="checkbox" value="1"> Select all          
                      </div>
                      <div class="ordersmpteldive2">
                      <!--<span class="share-btn" style="float: right"><a href="javascript:void(0);">Share</a></span>-->
                       <span class="all-sample-order-mail" style="float:right;"><a href="javascript:void(0);">Order Now</a></span> 
                      </div>
                    <?php
                    }
                    
                    ?>
                </div>
                <?php do_action("premmerce_wishlist_page_after_header_fields", $wl, $onlyView); ?>
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

$("#select-all-btn").click(function() {
$('[type=checkbox]').prop("checked", true);
});

$("#remove-all-btn").click(function() {
$('[type=checkbox]').prop("checked", false);
});

$("#share-project-btn").click(function(event){
    $("#authentication-modal").removeClass("hidden");
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
        $("#projectboard-names").append('<a id="project-urls" href="'+searchIDs1[i]+'">' + searchIDs[i] + '</a>, ');
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
<div class="hidden z-[999] fixed top-0 left-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto"
id="authentication-modal" tabindex="-1" aria-labelledby="exampleModalScrollableLabel" aria-hidden="true">
<div class="max-w-lg my-6 mx-auto relative w-auto pointer-events-none">
<div
  class="max-h-full overflow-hidden border-none relative flex flex-col w-full pointer-events-auto rounded-md outline-none text-current">
  <div class="relative bg-white rounded-lg shadow bg-gray-800 mx-5">
  
  <button id="cancel-modal" type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>

        <div class="py-6 px-6 lg:px-8">
            <h3 class="mb-4 text-xl font-medium text-white dark:text-white">Share Project Board</h3>
            <form class="space-y-6" method="POST" action="" id="sub">
                <div>
                    <label for="email1" class="block mb-2 text-sm font-medium text-white dark:text-gray-300">From email</label>
                    <input type="text" name="email1" id="email1" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                </div>

                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-white dark:text-gray-300">Recipient Email</label>
                    <input type="text" name="email" id="email" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com [multiple recipients by comma (,)]" required="" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                </div>

                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-white dark:text-gray-300">Full Name</label>
                    <input type="text" name="name" id="name" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your fullname" required="" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                </div>

                <div>
                    <label for="company_name" class="block mb-2 text-sm font-medium text-white dark:text-gray-300">Company Name</label>
                    <input type="text" name="company_name" id="company_name" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Company Name" required="" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                </div>

                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-white dark:text-gray-300">Phone</label>
                    <input type="number" name="phone" id="phone" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Phone Number" required="" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                </div>
                
                <div>
                <label for="message" class="block mb-2 text-sm font-medium text-white dark:text-gray-400">Your Note</label>
                <textarea id="message" name="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
                </div>

                <div>
                <div class="flex flex-wrap">
                    <div class="flex items-center mr-4">
                        <input id="red-radio" type="radio" value="show" name="coloredradio" id="coloredradio" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="red-radio" class="ml-2 text-sm font-medium text-white dark:text-gray-300">Show Pricing</label>
                    </div>
                    
                    <div class="flex items-center mr-4">
                        <input id="green-radio" type="radio" value="hide" name="coloredradio" id="coloredradio" class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="green-radio" class="ml-2 text-sm font-medium text-white dark:text-gray-300">Hide Pricing</label>
                    </div>
                </div>
                </div>
                <div>
                <div class="flex p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    <svg class="inline flex-shrink-0 mr-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <div>
                    <span id="projectboard-names"></span>
                </div>

                </div>
                </div>
                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Share now</button>
        </form>
    </div>
  </div>
</div>
</div>
</div>


<div class="project_btns"><div id="select-all-btn" class="button-btns bg-gray-800">Select All</div><div id="remove-all-btn" class="button-btns bg-gray-800">Remove All</div><div id="share-project-btn" class="button-btns bg-gray-800">Share Project Board</div></div>
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

@media (max-width: 480px) {
    .project-breadcrumb {
        display: grid;
        justify-content: unset;
        align-items: unset;
        padding: 35px;
        margin-bottom: 20px;
        border-radius: 10px;
    }

    .arrange-btn {
        display: none;
    }

    .ordersmpteldive2 {
        font-size: 15px !important;
    }
}
</style>
<script>
jQuery(document).ready(function ($) {

$("#order-items-now").click(function(){
    $("#authentication-modal").removeClass("hidden");
    document.querySelector('[id=projectboard-names]').innerHTML = "";
});

$("#close-order").click(function(){
    $("#authentication-modal").addClass("hidden");
    document.querySelector('[id=projectboard-names]').innerHTML = "";
});



$("#parent").click(function() {

$(".child").prop("checked", this.checked);
    $('.child:checkbox:checked').each(function(){
        console.log($(this).val())
    });

});

$('.child').click(function() {

if ($('.child:checked').length == $('.child').length) {
  $('#parent').prop('checked', true);
  $('.child:checkbox:checked').each(function(){
        console.log($(this).val())
    });

} else {
  $('#parent').prop('checked', false);

  $('.child:checkbox:checked').each(function(){
        console.log($(this).val())
  });
  
}
});

});
</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  important: true,
}
</script>
<div class="hidden z-[999] fixed top-0 left-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto"
id="authentication-modal" tabindex="-1" aria-labelledby="exampleModalScrollableLabel" aria-hidden="true">
<div class="max-w-lg my-6 mx-auto relative w-auto pointer-events-none">
<div
  class="max-h-full overflow-hidden border-none relative flex flex-col w-full pointer-events-auto rounded-md outline-none text-current">
  <div class="relative bg-white rounded-lg shadow bg-gray-800 mx-5">
  
  <button id="close-order" type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>

        <div class="py-6 px-6 lg:px-8">
            <h3 class="mb-4 text-xl font-medium text-white dark:text-white">Share Project Board</h3>
            <form class="space-y-6" method="POST" action="" id="sub">
                <div>
                    <label for="email1" class="block mb-2 text-sm font-medium text-white dark:text-gray-300">From email</label>
                    <input type="text" name="email1" id="email1" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                </div>

                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-white dark:text-gray-300">Recipient Email</label>
                    <input type="text" name="email" id="email" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com [multiple recipients by comma (,)]" required="" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                </div>

                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-white dark:text-gray-300">Full Name</label>
                    <input type="text" name="name" id="name" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your fullname" required="" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                </div>

                <div>
                    <label for="company_name" class="block mb-2 text-sm font-medium text-white dark:text-gray-300">Company Name</label>
                    <input type="text" name="company_name" id="company_name" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Company Name" required="" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                </div>

                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-white dark:text-gray-300">Phone</label>
                    <input type="number" name="phone" id="phone" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Phone Number" required="" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                </div>
                
                <div>
                <label for="message" class="block mb-2 text-sm font-medium text-white dark:text-gray-400">Your Note</label>
                <textarea id="message" name="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
                </div>

                <div>
                <div class="flex flex-wrap">
                    <div class="flex items-center mr-4">
                        <input id="red-radio" type="radio" value="show" name="coloredradio" id="coloredradio" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="red-radio" class="ml-2 text-sm font-medium text-white dark:text-gray-300">Show Pricing</label>
                    </div>
                    
                    <div class="flex items-center mr-4">
                        <input id="green-radio" type="radio" value="hide" name="coloredradio" id="coloredradio" class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="green-radio" class="ml-2 text-sm font-medium text-white dark:text-gray-300">Hide Pricing</label>
                    </div>
                </div>
                </div>
                <div>
                <div class="flex p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    <svg class="inline flex-shrink-0 mr-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <div>
                    <span id="projectboard-names"></span>
                </div>

                </div>
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
        <div class="project-breadcrumb">
                <?php do_action("premmerce_wishlist_page_before_header_fields", $wl, $onlyView); ?>
                <!-- Name -->
                <?php
                    $wishlist_url = esc_url(home_url($pageSlug));
                    $wishlist_url .= (parse_url($wishlist_url, PHP_URL_QUERY) ? '&' : '?') . 'key=' . $wl['wishlist_key'];
                ?>
                <div class="wl-frame__title">
                    <a class="wl-frame__title-link zxcvxcvds"
                        <?php if(!$onlyView): ?>
                            href="<?php echo $wishlist_url ?>"
                        <?php endif; ?>
                       rel="nofollow">
                        <?php echo $wl['name']; ?>
                    </a>
                     <div class="wl-frame__header-nav">
                        <button class="wl-frame__header-link"
                                data-modal-wishlist="<?php echo wp_nonce_url(home_url($apiUrlWishListRename . $wl['wishlist_key']), 'wp_rest'); ?>">
                            <?php _e('Rename', WishlistPlugin::DOMAIN); ?>
                        </button>
                    </div>
                    <!-- Delete -->
                    <div class="wl-frame__header-nav">
                        <a class="wl-frame__header-link"
                           href="<?php echo wp_nonce_url(home_url($apiUrlWishListDelete . $wl['wishlist_key']), 'wp_rest'); ?>">
                            <?php _e('Delete', WishlistPlugin::DOMAIN); ?>
                        </a>
                    </div>
                </div>
                
                      
                   <?php
                     if(is_user_logged_in())
                    {
                        
                    ?>
                    <div class="ordersmpteldive">
                        <input class="allorderforsample" name="allorderforsample" type="checkbox" value="1" id="parent"> Select all          
                      </div>
                      <div class="ordersmpteldive2">
                       <span class="share-btn" style="float: right"><a href="javascript:void(0);">Share</a></span>
                       <span class="all-sample-order" style="float:right;"><a href="javascript:void(0);"><b>Add To Cart</b></a></span>
                       <span class="all-sample-order-mail all-sample-ordernew" id="order-items-now" style="float:right;"><a href="javascript:void(0);">Order Now</a></span>
                       
                      </div>
                      <div class="arragboaddiv">
                      <span class="arrange-btn" style="float: right"><a href="javascript:void(0);">Arrange The Board</a></span>
                      </div>
                    <?php
                    }
                    if(isset($_GET['key']) && !empty($_GET['key']) && !is_user_logged_in())
                    
                    {
                    ?>
                    <div class="ordersmpteldive">
                        <input class="allorderforsample" name="allorderforsample" type="checkbox" value="1"> Select all          
                      </div>
                      <div class="ordersmpteldive2">
                      <!--<span class="share-btn" style="float: right"><a href="javascript:void(0);">Share</a></span>-->
                       <span class="all-sample-order-mail" style="float:right;"><a href="javascript:void(0);">Order Now</a></span> 
                      </div>
                    <?php
                    }
                    
                    ?>
                </div>
                <?php do_action("premmerce_wishlist_page_after_header_fields", $wl, $onlyView); ?>
            </div>
            <!-- Frame header end -->
                    <?php add_action('woocommerce_after_shop_loop_item', 'add_a_custom_button', 5 );
function add_a_custom_button() {
global $product;
echo '<div style="margin-bottom:10px;"><input type="checkbox" class="child" id="vehicle1" name="vehicle1" href="' . esc_attr( $product->get_permalink() ) . '" value="' . esc_attr( $product->get_title() ) . '" /> ' . __('Select Product') . '</div>';
} ?>
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