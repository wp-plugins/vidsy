<?php
function vidsy_after_the_content($content) {
    $postid = get_the_ID();

    $vmeta_auto_enable = get_post_meta($postid, '_vidsytv_auto_enabled', true);
    $vmeta_auto_playlist = get_post_meta($postid, '_vidsytv_auto_playlist', true);
    $vmeta_auto_theme = get_post_meta($postid, '_vidsytv_auto_theme', true);

    if (empty($vmeta_auto_enable)) {
        $vmeta_auto_enable = 'no';
    }

    if (!is_feed() && !is_home() && $vmeta_auto_enable == 'yes') {
        $userdata = get_option('vidsy_options');
        $subdomain = $userdata['subdomain'];
        $userdata = $userdata['userdata'];

        if ($vmeta_auto_playlist == 'recent') {
            $jsname = 'recenthorizontal.js';
            $playlistid = '0';
        } else {
            $jsname = 'playlisthorizontal.js';
            $playlistid = '';

            $playlist = trim(preg_replace('/\s+/', ' ', $vmeta_auto_playlist));
            $playlist = str_ireplace(' ', '+', $playlist);
            $playlisttransient = md5($playlist);

            $error = true;
            /*
            Traemos los resultados desde un trasient (cache 60 minutos), si no, y solo si no existen, vamos a la API para volver a consultar.
            */
            if (false === ($playlistid = get_transient('vidsy_' . $playlisttransient))) {
                $apiresponse = wp_remote_get(VIDSY_URL . '/api/playlists/fromname/' . $playlist . '/for/' . $subdomain, array('timeout' => 15));
                if (is_wp_error($apiresponse) || !isset($apiresponse['body'])) {
                    $error = true;
                } else {
                    $apiresults = json_decode(wp_remote_retrieve_body($apiresponse));
                    if ($apiresults->status == 'error') {
                        $error = true;
                    } else {
                        $error = false;
                        $playlistid = $apiresults->playlist->id;
                    }
                }

                if ($error === false) {
                    set_transient('vidsy_' . $playlisttransient, $playlistid, 60 * 60);
                }
            } else {
                $error = false;
            }

            if ($error === true) {
                $playlistid = '0';
            }
        }

        $autocontent = "<script type='text/javascript' src='//vidsy.tv/public/widgets/" . $jsname . "?userid=" . $userdata->userid . "&amp;username=" . $subdomain . "&amp;playlistid=" . $playlistid . "&amp;width=100%25&amp;height=150&amp;theme=" . $vmeta_auto_theme . "'></script>";

        $content.= '<p class="vidsy_thecontent">' . $autocontent . '</p>';
    }
    return $content;
}
add_filter('the_content', 'vidsy_after_the_content');
