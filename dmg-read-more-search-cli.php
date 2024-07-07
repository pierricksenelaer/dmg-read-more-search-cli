<?php
/**
 * Plugin Name: DMG Read More Search
 * Description: A custom WP-CLI command to search for posts with a Gutenberg read-more block within a date range.
 * Version: 1.0
 * Author: Your Name
 * License: GPL2
 */

// Ensure this file is only accessible via WordPress
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
    WP_CLI::add_command( 'dmg-read-more search', 'DMG_Read_More_Command' );
}

class DMG_Read_More_Command {
    public function __invoke( $args, $assoc_args ) {
        $date_before = isset( $assoc_args['date-before'] ) ? $assoc_args['date-before'] : date('Y-m-d', current_time('timestamp'));
        $date_after = isset( $assoc_args['date-after'] ) ? $assoc_args['date-after'] : date('Y-m-d', strtotime('-30 days', current_time('timestamp')));

        // Ensure dates are in the correct format
        if ( ! $this->validate_date( $date_before ) || ! $this->validate_date( $date_after ) ) {
            WP_CLI::error( 'Invalid date format. Please use YYYY-MM-DD.' );
        }

        // Execute the query
        $this->query_posts( $date_after, $date_before );
    }

    private function validate_date( $date ) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    private function query_posts( $date_after, $date_before ) {
        $args = [
            'date_query' => [
                'after'     => $date_after,
                'before'    => $date_before,
                'inclusive' => true,
            ],
            'post_type'   => 'post',
            'post_status' => 'publish',
            'fields'      => 'ids',
            'no_found_rows' => true, // Improve performance
        ];

        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            foreach ( $query->posts as $post_id ) {
                $post = get_post( $post_id );
                if ( has_block( 'core/dmg-read-more-search', $post->post_content ) ) {
                    WP_CLI::log( $post_id );
                }
            }
        } else {
            WP_CLI::log( 'No posts found.' );
        }
    }
}
