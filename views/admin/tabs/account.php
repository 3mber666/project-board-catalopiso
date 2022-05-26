<?php
    if(function_exists('premmerce_pw_fs') && premmerce_pw_fs()->is_registered()){
        premmerce_pw_fs()->add_filter('hide_account_tabs', '__return_true');
        premmerce_pw_fs()->_account_page_load();
        premmerce_pw_fs()->_account_page_render();
    }
