<?php
	class GHC_CTM_CERT_SPEC {

		static function init(){
			self::add_taxonomy('profession','Profession','Professions');
			self::add_taxonomy('specialty','Specialty','Specialties');
			
			add_action( 'job_listing_profession_add_form_fields',	[__CLASS__, 'add_meta_fields'],		0 );
			add_action( 'job_listing_profession_edit_form_fields', [__CLASS__, 'edit_meta_fields'], 	0, 2 );
			add_action( 'created_job_listing_profession', 					[__CLASS__, 'save_meta_fields'], 	0, 2 );
			add_action( 'edited_job_listing_profession', 					[__CLASS__, 'save_meta_fields'], 	0, 2 );
			
			add_action( 'job_listing_specialty_add_form_fields', 	[__CLASS__, 'add_meta_fields'], 	0 );
			add_action( 'job_listing_specialty_edit_form_fields', [__CLASS__, 'edit_meta_fields'],	0, 2 );
			add_action( 'created_job_listing_specialty', 					[__CLASS__, 'save_meta_fields'], 	0, 2 );
			add_action( 'edited_job_listing_specialty', 					[__CLASS__, 'save_meta_fields'], 	0, 2 );
		}

		static function add_meta_fields( $taxonomy ) {
			$tax_name = '';

			if($taxonomy == 'job_listing_profession'){
				$tax_name = 'Profession'; 
			}
			elseif($taxonomy == 'job_listing_specialty'){
				$tax_name = 'Specialty';	
			}
		  echo 
		  '<div class="form-field">
		    <label for="ctm_text">Allow Jobs to Post?</label>
		    <select name="ctm_active" id="ctm_active">
		    	<option value="1">Yes</option>
		    	<option value="0">No</option>
		    </select>
		    <p>If set to "No", Jobs coming from CTM that have this '.$tax_name.' will be filtered out.<br/>Existing jobs already on the website will be unaffected unless a new update is made to the posting in CTM [Edit, Fill, Void, etc].</p> 
		  </div>';

		  echo 
		  '<div class="form-field">
		    <label for="ctm_text">CTM Name</label>
		    <input type="text" name="ctm_text" id="ctm_text" />
		    <p>Name as written in the CTM system, for lookup when adding/updating/receiving jobs.</p> 
		  </div>';

		 	if($taxonomy == 'job_listing_specialty'){
		 		$profs = get_terms( array('taxonomy'   => 'job_listing_profession','hide_empty' => false) );

				echo 
					'<div class="form-field">
				    	<label for="ctm_dupe">Different Label, Same Profession:</label>
				    	<select name="ctm_dupe" id="ctm_dupe">
				    		<option value="">N/A</option>
				    ';
				    foreach($profs as $prof){
				    	echo "<option value='{$prof->term_id}'>{$prof->name}</option>";
				    }
				echo
					'
				    	</select>
				    	<p>If this is the same as a Profession, but the label is not identical, note it here.</p> 
			  		</div>';
		  }
		} 


		static function edit_meta_fields( $term, $taxonomy ) {
			$active = get_term_meta($term->term_id, 'ctm_active', true);
		  	$text  = get_term_meta($term->term_id, 'ctm_text',  true);
			$tax_name = '';

			if($taxonomy == 'job_listing_profession'){
				$tax_name = 'Profession'; 
				$dupe = '';
			}
			elseif($taxonomy == 'job_listing_specialty'){
				$tax_name = 'Specialty';	
			  	$dupe = get_term_meta($term->term_id, 'ctm_dupe',  true);
			}
		  
		  ?>
			  <tr class="form-field">
			    <th>
			    	<label for="ctm_text">Allow Jobs to Post?</label>
			    </th>
			    <td>
				    <select name="ctm_active" id="ctm_active">
				    	<option value="1" <?php echo ($active == 1)? 'selected="selected"' : '';?>>Yes</option>
				    	<option value="0" <?php echo ($active == 0)? 'selected="selected"' : '';?>>No</option>
				    </select>
				    <p>If set to "No", Jobs coming from CTM that have this <?php echo $tax_name; ?> will be filtered out.  Existing jobs already on the website will be unaffected unless a new update is made to the posting in CTM [Edit, Fill, Void, etc].</p> 
				   </td>
			  </tr>

			  <tr class="form-field">
			    <th>
			    	<label for="ctm_text">CTM Name</label>
			    </th>
			    <td>
				    <input type="text" name="ctm_text" id="ctm_text" value="<?php echo $text; ?>"/>
				    <p>Name as written in the CTM system, for lookup when adding/updating/receiving jobs.</p> 
			    </td>
			  </tr>
			<?php if($taxonomy == 'job_listing_specialty'){ ?>
			  <tr>
			    <th>
			    	<label for="ctm_dupe">Different Label, Same Profession</label>
			    </th>
			    <td>
				    	<select name="ctm_dupe" id="ctm_dupe">
				    		<option value="">N/A</option>
					<?php 
				 		$profs = get_terms( array('taxonomy'   => 'job_listing_profession','hide_empty' => false) );

						foreach($profs as $prof){
					    	echo "<option value='{$prof->term_id}'".selected($prof->term_id, $dupe, false).">{$prof->name}</option>";
					    }

					?>
				    <p>If this is the same as a Profession, but the label is not identical, note it here.</p> 
			    </td>
			  </tr>
			<?php 
			} 
		} 

		static function save_meta_fields( $term_id ) {
		  update_term_meta( $term_id, 'ctm_active', sanitize_text_field( $_POST[ 'ctm_active' ] ) );
		  update_term_meta( $term_id, 'ctm_text', 	sanitize_text_field( $_POST[ 'ctm_text' ] ) );

		  if(isset($_POST['ctm_dupe'])){
		  	update_term_meta( $term_id, 'ctm_dupe', sanitize_text_field( $_POST[ 'ctm_dupe' ] ) );
		  }

		}

		static function add_taxonomy($suffix, $single, $plural){
			register_taxonomy(
				"job_listing_{$suffix}",
				'job_listing',
				[
					'hierarchical'         => false,
					'label'                => $single,
					'labels'               => [
						'name'              => $single,
						'singular_name'     => $single,
						'menu_name'         => $plural,
						'search_items'      => "Search {$plural}",
						'all_items'         => "All {$plural}",
						'parent_item'       => "Parent {$single}",
						'parent_item_colon' => "Parent {$single}:",
						'edit_item'         => "Edit {$single}",
						'update_item'       => "Update {$single}",
						'add_new_item'      => "Add New {$single}",
						'new_item_name'     => "New {$single} Name",
					],
					'show_ui'              => true,
					'show_tagcloud'        => false,
					'public'               => false,
					'capabilities'         => [
						'manage_terms' => 'manage_job_listings',
						'edit_terms'   => 'manage_job_listings',
						'delete_terms' => 'manage_job_listings',
						'assign_terms' => 'manage_job_listings',
					],
					'rewrite'              => false,
					'show_in_rest'         => true,
					'rest_base'            => 'job-types',
				]
			);
		}

		static function fetch_selected_term($post_id, $taxonomy){
			$assigned = get_the_terms( $post_id, $taxonomy);
			$selected = $assigned ? array_pop($assigned) : false;
			$selected = $selected ? $selected->term_id : 0;
			return $selected;
		}

		static function fetch_term_by_CTM_name($term, $taxonomy){
			global $wpdb;
			$query = $wpdb->prepare("select T.* from {$wpdb->prefix}terms T, {$wpdb->prefix}termmeta TM, {$wpdb->prefix}term_taxonomy TT where T.term_id=TT.term_id and TT.taxonomy=%s and T.term_id=TM.term_id and TM.meta_key='ctm_text' and TM.meta_value=%s", array($taxonomy, $term));

			$term = $wpdb->get_results($query);

			if(!is_countable($term)) return false;

			return $term ? array_pop($term) : false;
		}
	}
?>