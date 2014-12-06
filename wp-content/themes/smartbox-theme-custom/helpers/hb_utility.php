 <?php


/**
 * @description teaching topic can occur in url query as term or just a topic try to get this term
 * @return string
 */
function hb_get_teaching_topic_from_query() {
    
    $teaching_topic = get_query_var('term');
    if (empty($teaching_topic)) {
        $teaching_topic = get_query_var('teaching_topics');
    }
    return $teaching_topic;
}

/**
 * @description provides true if theme is running locally on localhost and false if on server 
 * @return boolean
 */
function hb_is_local_environment() {
    $server_name = $_SERVER['SERVER_NAME'];
    if (isset($server_name) && $server_name == "localhost")
        return true;
    return false;
}
/**
 * @description get path to script library of jw player
 * @return string
 */
function hb_get_host_jw_player_script() {
    if (is_ssl() && !hb_is_local_environment())
        return 'https://ssl.jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
    else if (is_ssl() && hb_is_local_environment())
        return 'https://jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
    else if (!hb_is_local_environment())
        return 'https://ssl.jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
    else
        return 'http://jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
}

/**
 * @description do limit string by word count
 * @param string $string <i>string to be limited</i>
 * @param int $word_limit <i>amount of words for limit</i>
 * @param bool $add_punkts <i>whether to add punkts at the end </i>
 * @return string
 */
function hb_limit_string($string, $word_limit, $add_punkts = false) {
    $words = explode(' ', $string, ($word_limit + 1));
    if (count($words) > $word_limit) {
        array_pop($words);
    }

    if ($add_punkts)
        return implode(' ', $words) . ' ...';
    return implode(' ', $words);
}

/**
 * @description get taxonomy term summary, used for example on topic page 
 * @param taxonomy $taxonomy <i>post instance of text post</i>
  * @return string
 */
function hb_get_taxonomy_term_summary_mini($taxonomy) {
    $summary = get_field('taxonomy_summary', 'teaching_topics_' . $taxonomy->term_id);
    if (empty($taxonomy)) {
        $summary = $taxonomy->description . " ";
        $summary = oxy_limit_excerpt($summary, 40);
    }
    return $summary;
}

/**
 * @description function to create more text string
 * @param string $post_type <i> post type of post </i>
 * @return more text string
 */
function hb_get_more_text($post_type) {
    $more_text = __('Read more', THEME_FRONT_TD);
    switch ($post_type) {
        case 'oxy_video':
            $more_text = __('Goto video', THEME_FRONT_TD);
            break;
        case 'oxy_audio':
            $more_text = __('Goto audio', THEME_FRONT_TD);;
            break;
        default :
            break;
    }
    return $more_text;
}

/**
 * @package HTML_HELPER
 * @description function to generate a attributes for html-elements
 * @param string $id
 * @param string $class
 * @return string
 */
function hb_set_attributes($id, $class) {
    $string = ' ';
    if (hb_is_element_not_empty($id)) {
        $string .= 'id="' . $id . '" ';
    }
    if (hb_is_element_not_empty($class)) {
        $string .= 'class="' . $class . '" ';
    }
    return $string;
}

/**
 * @package HELPER
 * @description function to check if a element exists
 * @param string $element
 * @return boolean only <b>TRUE</b> or <b>FALSE</b>. Not NULL.
 */
function hb_is_element_not_empty($element) {
    if ($element != NULL && $element != '') {
        return TRUE;
    }
    return FALSE;
}
/*
 * @description function to check if a element exists  
 */
class hb_enum_taxonomy_image_type {
    const image = 'image';
    const banner_image = 'banner_image';
    const video_background_image = 'video_background_image';
}
?>
