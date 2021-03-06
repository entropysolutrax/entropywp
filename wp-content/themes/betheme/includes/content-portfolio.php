<?php
/**
 * The template for displaying content in the template-portfolio.php template
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

if( ! function_exists('mfn_content_portfolio') ){
	function mfn_content_portfolio( $query = false, $style = false ){
		global $wp_query;
		$output = '';
		
		$translate['readmore'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-readmore','Read more') : __('Read more','betheme');
		$translate['client'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-client','Client') : __('Client','betheme');
		$translate['date'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-date','Date') : __('Date','betheme');
		$translate['website'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-website','Website') : __('Website','betheme');
		$translate['view'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-view','View website') : __('View website','betheme');
		
		if( ! $query ) $query = $wp_query;
		if( ! $style ){
			if( $_GET && key_exists('mfn-p', $_GET) ){
				$style = $_GET['mfn-p']; // demo
			} else {
				$style = mfn_opts_get( 'portfolio-layout', 'grid' );
			}
		}
		
		if ( $query->have_posts() ){
			while ( $query->have_posts() ){
				$query->the_post();
				
				$item_class = array();
				$categories = '';
				
				$terms = get_the_terms(get_the_ID(),'portfolio-types');
				if( is_array( $terms ) ){
					foreach( $terms as $term ){
						$item_class[] = 'category-'. $term->slug;
						$categories .= '<a href="'. site_url() .'/portfolio-types/'. $term->slug .'">'. $term->name .'</a>, ';
					}
					$categories = substr( $categories , 0, -2 );
				}
				$item_class[] = get_post_meta( get_the_ID(), 'mfn-post-size', true );
				$item_class = implode(' ', $item_class);
				
				// full width sections for list style
				if( $item_bg = get_post_meta( get_the_ID(), 'mfn-post-bg', true ) ){
					$item_bg = 'style="background-image:url('. $item_bg .');"';
				}
				
				$external			= mfn_opts_get( 'portfolio-external' );
				$ext_link			= get_post_meta( get_the_ID(), 'mfn-post-link', true );
				$large_image_url 	= wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );
				
				if( $external == 'popup' ){
					
					// link to popup image
					$title_link = '<a href="'. $large_image_url[0] .'" rel="prettyphoto">'. get_the_title() .'</a>';
							
				} elseif( $external == 'disable' ){
					
					// disable details
					$title_link = get_the_title();
					
				} elseif( $external && $ext_link ){
					
					// link to project website
					$title_link = '<a href="'. $ext_link .'" target="_blank">'. get_the_title() .'</a>';
							
				} else {
					
					// link to project details
					$title_link = '<a href="'. get_permalink() .'">'. get_the_title() .'</a>';
					
				}
				
				$output .= '<li class="portfolio-item isotope-item '. $item_class .'">';
					$output .= '<div class="portfolio-item-fw-bg" '. $item_bg .'>';
						$output .= '<div class="portfolio-item-fw-wrapper">';
						
							// desc --------------------------------------------------------------------------
							$output .= '<div class="list_style_header">';
								$output .= '<h3 class="entry-title" itemprop="headline">'. $title_link .'</h3>';
								$output .= '<div class="links_wrapper">';
									$output .= '<a href="#" class="button button_js portfolio_prev_js"><span class="button_icon"><i class="icon-up-open"></i></span></a>';
									$output .= '<a href="#" class="button button_js portfolio_next_js"><span class="button_icon"><i class="icon-down-open"></i></span></a>';
									$output .= '<a href="'. get_permalink() .'" class="button button_left button_theme button_js"><span class="button_icon"><i class="icon-link"></i></span><span class="button_label">'. $translate['readmore'] .'</span></a>';
								$output .= '</div>';
							$output .= '</div>';
								
							// photo --------------------------------------------------------------------------
							$output .= '<div class="image_frame scale-with-grid">';
								$output .= '<div class="image_wrapper">';		
									$output .= mfn_post_thumbnail( get_the_ID(), 'portfolio', $style );
								$output .= '</div>';
							$output .= '</div>';
			
							// desc --------------------------------------------------------------------------
							$output .= '<div class="desc">';
							
								$output .= '<div class="title_wrapper">';
									$output .= '<h5 class="entry-title" itemprop="headline">'. $title_link .'</h5>';								
									$output .= '<div class="button-love">'. mfn_love() .'</div>';
								$output .= '</div>';
			
								$output .= '<div class="details-wrapper">';
									$output .= '<dl>';
										if( $client = get_post_meta( get_the_ID(), 'mfn-post-client', true ) ){
											$output .= '<dt>'. $translate['client'] .'</dt>';
											$output .= '<dd>'. $client .'</dd>';
										}
										$output .= '<dt>'. $translate['date'] .'</dt>';
										$output .= '<dd>'. get_the_date() .'</dd>';
										if( $link = get_post_meta( get_the_ID(), 'mfn-post-link', true ) ){
											$output .= '<dt>'. $translate['website'] .'</dt>';
											$output .= '<dd><a target="_blank" href="'. $link .'"><i class="icon-forward"></i>'. $translate['view'] .'</a></dd>';
										}
									$output .= '</dl>';
								$output .= '</div>';
								
								$output .= '<div class="desc-wrapper">';
									$output .= get_the_excerpt();
								$output .= '</div>';
								
							$output .= '</div>';
							
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</li>';
				
			}
		}
		
		return $output;
	}
}

?>