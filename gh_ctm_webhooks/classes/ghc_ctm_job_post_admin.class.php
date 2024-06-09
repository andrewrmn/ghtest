<?php
	class GHC_CTM_JOB_POST_ADMIN {

		static function init(){
			add_filter('add_meta_boxes', [__CLASS__, 'hide_metaboxes']);			
			add_filter('add_meta_boxes', [__CLASS__, 'add_metaboxes']);			
			add_action('save_post_job_listing', 		 [__CLASS__, 'prioritize_this_metabox'] , 2000, 2);
		}

		static function hide_metaboxes() {
		    remove_meta_box('job_listing_type', 'job_listing', 'side');
		    remove_meta_box('tagsdiv-job_listing_profession', 'job_listing', 'side');
		    remove_meta_box('tagsdiv-job_listing_specialty', 'job_listing', 'side');
		}

		static function add_metaboxes(){
			add_meta_box( 'job_listing_taxonomies', "Type, Profession, and Specialty", [ __CLASS__, 'categorization_box' ], 'job_listing', 'side', 'core' );			
			add_meta_box( 'job_listing_othermeta', "Job Details in CTM", [ __CLASS__, 'othermeta_box' ], 'job_listing', 'normal', 'high' );			
		}

		static function categorization_box( $post ){
			// One for each input.
			self::single_categorization_dropdown($post, 'job_listing_type', 			'Job Type', 	true );
			self::single_categorization_dropdown($post, 'job_listing_profession', 'Profession' );
			self::single_categorization_dropdown($post, 'job_listing_specialty', 	'Specialty' );
		}

		static function prioritize_this_metabox($post_id, $post_object){

			# Don't waste processing power on autosaves or revisions.
			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
			if($post_object->post_type == 'revision') return;

			if(isset($_POST)){
				$postdata = $_POST;

				$fields = array(
					'job_id',
					'region',
					'start_date',
					'end_date',
					'hot_job',
					'therapia_job',
					'shift',
					'shift_time',
					'hours',
					'week_day',
					'state',
					'zip_code',
					'client_name',
					'num_beds',
					'proximity',
					'pay_package',
				);

				foreach($fields as $field){
					if(isset($postdata[$field])){
						update_post_meta($post_id, $field, sanitize_text_field($postdata[$field]));	
					}
				}
			}
		}

		static function othermeta_box( $post ){
			echo '<div class="wp_job_manager_meta_data">';
			self::single_metabox($post, 'job_id', 'Job ID', 'text');
			self::single_metabox($post, 'region', 'Region', 'text');
			self::single_metabox($post, 'start_date', 'Start Date', 'date');
			self::single_metabox($post, 'end_date', 'End Date', 'date');
			self::single_metabox_bool($post, 'hot_job', 'Hot Job');
			self::single_metabox_bool($post, 'therapia_job', 'Therapia Job');
			self::single_metabox($post, 'shift', 'Shift', 'text');
			self::single_metabox($post, 'shift_time', 'Shift Time', 'text');
			self::single_metabox($post, 'hours', 'Hours', 'text');
			self::single_metabox($post, 'week_day', 'Duration', 'text');
			self::single_metabox($post, 'state', 'State [example: LA]', 'text');
			self::single_metabox($post, 'zip_code', 'Zip Code', 'text');
			self::single_metabox($post, 'client_name', 'Client Name', 'text');
			self::single_metabox($post, 'num_beds', '# Beds at Client', 'text');
			self::single_metabox($post, 'pay_package', 'Pay Range', 'text');
			echo '</div>';
		}

		static function single_metabox($post, $meta_key, $label, $input_type){
			$value = get_post_meta($post->ID, $meta_key, true);

			echo "<p class='form-field'><label for='$meta_key'>$label</label><input type='$input_type' value='$value' name='$meta_key', id='$meta_key' /></p>";
		}

		static function single_metabox_bool($post, $meta_key, $label){
			$value = get_post_meta($post->ID, $meta_key, true);
			echo "<p class='form-field'><label for='$meta_key'>$label</label><select name='$meta_key' id='$meta_key'><option value='0' ".selected(0, $value, false).">No</option><option value='1' ".selected(1, $value, false).">Yes</option></select></p>";	
		}

		static function single_categorization_dropdown( $post, $taxonomy, $tax_display_name, $value_is_id=false ){
			$terms = get_terms( ['taxonomy' => $taxonomy, 'hide_empty' => 0] );
			$assigned = get_the_terms( $post->ID, $taxonomy);
			$selected = $assigned ? array_pop($assigned) : false;
			$selected = $selected ? $selected->term_id : 0;

			echo "
				<div id='taxonomy-{$taxonomy}' class='categorydiv'>
					<h4>{$tax_display_name}</h4>
					<select name='tax_input[{$taxonomy}]'>
					<option value=''>Please Select</option>
			";
			foreach($terms as $term){
				if($value_is_id){
					$val = esc_attr($term->term_id);
				}
				else{
					$val = esc_attr($term->name);
				}
				echo "<option value='{$val}'" . selected($selected, $term->term_id, false) . ">{$term->name}</option>";
			}
			echo '
					</select>
				</div>
			';
		}
	}
?>