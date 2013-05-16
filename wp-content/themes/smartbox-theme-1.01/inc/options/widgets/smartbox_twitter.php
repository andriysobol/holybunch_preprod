<?php
/**
 * Oxygenna.com
 *
 * $Template:: *(TEMPLATE_NAME)*
 * $Copyright:: *(COPYRIGHT)*
 * $Licence:: *(LICENCE)*
 */

require_once CORE_DIR . 'widget.php';

class Smartbox_twitter extends OxyWidget {

    public function __construct() {
        $widget_options = array( 'description' => __( 'Displays you latest tweets', THEME_ADMIN_TD) );
        parent::__construct( 'smartbox_twitter-options.php', false, $name = THEME_NAME . ' - ' . __('Twitter Feed', THEME_ADMIN_TD), $widget_options );
    }


    function widget( $args, $instance ) {
        $account = trim( urlencode( $instance['account'] ) );

        if ( empty( $account ) ) {
            if ( current_user_can('edit_theme_options') ) {
                echo $args['before_widget'];
                echo '<p>' . sprintf( __( 'Please configure your Twitter username for the <a href="%s">Twitter Widget</a>.', THEME_FRONT_TD ), admin_url( 'widgets.php' ) ) . '</p>';
                echo $args['after_widget'];
            }

            return;
        }

        $show = absint( $instance['show'] );  // # of Updates to show

        if ( $show > 200 ) // Twitter paginates at 200 max tweets. update() should not have accepted greater than 20
            $show = 200;

        $hidereplies        = $this->get_option( 'hidereplies', $instance, 'false');
        $hidepublicized     = $this->get_option( 'hidepublicized', $instance, 'false');
        $include_retweets   = $this->get_option( 'include_retweets', $instance, 'false');
        $follow_button      = $this->get_option( 'follow_button', $instance, 'false');


        $tweets = $this->fetch_twitter_user_stream( $account, $hidereplies, $show, $include_retweets );

        if ( isset( $tweets['error'] ) && ( isset( $tweets['data'] ) && ! empty( $tweets['data'] ) ) )
            $tweets['error'] = '';

        if ( empty( $tweets['error'] ) ) {
            $before_tweet     = isset( $instance['beforetweet'] ) ? stripslashes( wp_filter_post_kses( $instance['beforetweet'] ) ) : '';
            $before_timesince = ( isset( $instance['beforetimesince'] ) && ! empty( $instance['beforetimesince'] ) ) ? esc_html( $instance['beforetimesince'] ) : ' ';

            $this->display_tweets( $show, $tweets['data'], $hidepublicized, $before_tweet, $before_timesince, $account );

            if ( $follow_button )
               // $this->display_follow_button( $account );

            add_action( 'wp_footer', array( $this, 'twitter_widget_script' ) );
        } else {
            echo $tweets['error'];
        }

       // echo $args['after_widget'];
        do_action( 'jetpack_stats_extra', 'widgets', 'twitter' );
    }

    function display_tweets( $show, $tweets, $hidepublicized, $before_tweet, $before_timesince, $account ) {
        $tweets_out = 0;
        ?><div class="widget_twitter"><div class="twitter-feed"><ul class='tweet_list'><?php

        foreach( (array) $tweets as $tweet ) {
            if ( $tweets_out >= $show )
                break;

            if ( empty( $tweet['text'] ) )
                continue;

            if( $hidepublicized && false !== strstr( $tweet['source'], 'http://publicize.wp.com/' ) )
                continue;

            $tweet['text'] = esc_html( $tweet['text'] ); // escape here so that Twitter handles in Tweets don't get mangled
            $tweet         = $this->expand_tco_links( $tweet );
            $tweet['text'] = make_clickable( $tweet['text'] );

            /*
             * Create links from plain text based on Twitter patterns
             * @link http://github.com/mzsanford/twitter-text-rb/blob/master/lib/regex.rb Official Twitter regex
             */
            $tweet['text'] = preg_replace_callback( '/(^|[^0-9A-Z&\/]+)(#|\xef\xbc\x83)([0-9A-Z_]*[A-Z_]+[a-z0-9_\xc0-\xd6\xd8-\xf6\xf8\xff]*)/iu',  array( $this, '_jetpack_widget_twitter_hashtag' ), $tweet['text'] );
            $tweet['text'] = preg_replace_callback( '/([^a-zA-Z0-9_]|^)([@\xef\xbc\xa0]+)([a-zA-Z0-9_]{1,20})(\/[a-zA-Z][a-zA-Z0-9\x80-\xff-]{0,79})?/u', array( $this, '_jetpack_widget_twitter_username' ), $tweet['text'] );

            if ( isset( $tweet['id_str'] ) )
                $tweet_id = urlencode( $tweet['id_str'] );
            else
                $tweet_id = urlencode( $tweet['id'] );

            ?>

            <li><i class="icon-twitter"></i>
                <span class="tweet_text"><?php echo esc_attr( $before_tweet ) . $tweet['text'] . esc_attr( $before_timesince ) ?></span>
                <small class="info text-italic"><span class="tweet_time"><a href="<?php echo $tweet['entities']['urls'][0]['expanded_url']; ?>" title="view tweet on tweeter"><?php echo esc_html( str_replace( ' ', '&nbsp;', $this->time_since( strtotime( $tweet['created_at'] ) ) ) ); ?>&nbsp;ago</a></span></small>
            </li>

            <?php

            unset( $tweet_it );
            $tweets_out++;
        }

        ?></ul></div></div><?php
    }

    function display_follow_button( $account ) {
        global $themecolors;

        $follow_colors        = isset( $themecolors['link'] ) ? " data-link-color='#{$themecolors['link']}'" : '';
        $follow_colors       .= isset( $themecolors['text'] ) ? " data-text-color='#{$themecolors['text']}'" : '';
        $follow_button_attrs  = " class='twitter-follow-button' data-show-count='false'{$follow_colors}";

        ?><a href="http://twitter.com/<?php echo esc_attr( $account ); ?>" <?php echo $follow_button_attrs; ?>>Follow @<?php echo esc_attr( $account ); ?></a><?php
    }

    function expand_tco_links( $tweet ) {
        if ( ! empty( $tweet['entities']['urls'] ) && is_array( $tweet['entities']['urls'] ) ) {
            foreach ( $tweet['entities']['urls'] as $entity_url ) {
                if ( ! empty( $entity_url['expanded_url'] ) ) {
                    $tweet['text'] = str_replace(
                                        $entity_url['url'],
                                        '<a href="' . esc_url( $entity_url['expanded_url'] ) . '"> ' . esc_html( $entity_url['display_url'] ) . '</a>',
                                        $tweet['text']
                                    );
                }
            }
        }

        return $tweet;
    }

    function fetch_twitter_user_stream( $account, $hidereplies, $show, $include_retweets ) {
        $tweets    = get_transient( 'widget-twitter-' . $this->number );
        $the_error = get_transient( 'widget-twitter-error-' . $this->number );

        if ( ! $tweets ) {
            $params = array(
                'screen_name'      => $account, // Twitter account name
                'trim_user'        => true,     // only basic user data (slims the result)
                'include_entities' => true
            );

            // If combined with $count, $exclude_replies only filters that number of tweets (not all tweets up to the requested count).
            if ( $hidereplies )
                $params['exclude_replies'] = true;
            else
                $params['count'] = $show;

            if ( $include_retweets )
                $params['include_rts'] = true;

            $twitter_json_url = esc_url_raw( 'http://api.twitter.com/1/statuses/user_timeline.json?' . http_build_query( $params ), array( 'http', 'https' ) );
            unset( $params );

            $response = wp_remote_get( $twitter_json_url, array( 'User-Agent' => 'WordPress.com Twitter Widget' ) );
            $response_code = wp_remote_retrieve_response_code( $response );

            switch( $response_code ) {
                case 200 : // process tweets and display
                    $tweets = json_decode( wp_remote_retrieve_body( $response ), true );

                    if ( ! is_array( $tweets ) || isset( $tweets['error'] ) ) {
                        do_action( 'jetpack_bump_stats_extras', 'twitter_widget', 'request-fail-$response_code-bad-data' );
                        $the_error = '<p>' . esc_html__( 'Error: Twitter did not respond. Please wait a few minutes and refresh this page.', THEME_FRONT_TD ) . '</p>';
                        $tweet_cache_expire = 300;
                        break;
                    } else {
                        set_transient( 'widget-twitter-backup-' . $this->number, $tweets, 86400 ); // A one day backup in case there is trouble talking to Twitter
                    }

                    do_action( 'jetpack_bump_stats_extras', 'twitter_widget', 'request-success' );
                    $tweet_cache_expire =  900;
                    break;
                case 401 : // display private stream notice
                    do_action( 'jetpack_bump_stats_extras', 'twitter_widget', 'request-fail-$response_code' );

                    $tweets = array();
                    $the_error = '<p>' . sprintf( esc_html__( 'Error: Please make sure the Twitter account is %1$spublic%2$s.', THEME_FRONT_TD ), '<a href="http://support.twitter.com/forums/10711/entries/14016">', '</a>' ) . '</p>';
                    $tweet_cache_expire = 300;
                    break;
                default :  // display an error message
                    do_action( 'jetpack_bump_stats_extras', 'twitter_widget', 'request-fail-$response_code' );

                    $tweets = get_transient( 'widget-twitter-backup-' . $this->number );
                    $the_error = '<p>' . esc_html__( 'Error: Twitter did not respond. Please wait a few minutes and refresh this page.', THEME_FRONT_TD ) . '</p>';
                    $tweet_cache_expire = 300;
                    break;
            }

            set_transient( 'widget-twitter-' . $this->number, $tweets, $tweet_cache_expire );
            set_transient( 'widget-twitter-error-' . $this->number, $the_error, $tweet_cache_expire );
        }

        return array( 'data' => $tweets, 'error' => $the_error );
    }


    function time_since( $original, $do_more = 0 ) {
        // array of time period chunks
        $chunks = array(
            array(60 * 60 * 24 * 365 , 'year'),
            array(60 * 60 * 24 * 30 , 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24 , 'day'),
            array(60 * 60 , 'hour'),
            array(60 , 'minute'),
        );

        $today = time();
        $since = $today - $original;

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];

            if (($count = floor($since / $seconds)) != 0)
                break;
        }

        $print = ($count == 1) ? '1 '.$name : "$count {$name}s";

        if ($i + 1 < $j) {
            $seconds2 = $chunks[$i + 1][0];
            $name2 = $chunks[$i + 1][1];

            // add second item if it's greater than 0
            if ( (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) && $do_more )
                $print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
        }
        return $print;
    }

    /**
     * Link a Twitter user mentioned in the tweet text to the user's page on Twitter.
     *
     * @param array $matches regex match
     * @return string Tweet text with inserted @user link
     */
    function _jetpack_widget_twitter_username( array $matches ) { // $matches has already been through wp_specialchars
        return "$matches[1]@<a href='" . esc_url( 'http://twitter.com/' . urlencode( $matches[3] ) ) . "'>$matches[3]</a>";
    }

    /**
     * Link a Twitter hashtag with a search results page on Twitter.com
     *
     * @param array $matches regex match
     * @return string Tweet text with inserted #hashtag link
     */
    function _jetpack_widget_twitter_hashtag( array $matches ) { // $matches has already been through wp_specialchars
        return "$matches[1]<a href='" . esc_url( 'http://twitter.com/search?q=%23' . urlencode( $matches[3] ) ) . "'>#$matches[3]</a>";
    }

    function twitter_widget_script() {
        if ( ! wp_script_is( 'twitter-widgets', 'registered' ) ) {
            if ( is_ssl() )
                $twitter_widget_js = 'https://platform.twitter.com/widgets.js';
            else
                $twitter_widget_js = 'http://platform.twitter.com/widgets.js';
            wp_register_script( 'twitter-widgets', $twitter_widget_js,  array(), '20111117', true );
            wp_print_scripts( 'twitter-widgets' );
        }
    }
}