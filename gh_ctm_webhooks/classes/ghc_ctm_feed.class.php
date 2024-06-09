<?php
	class GHC_CTM_FEED {

		static function XMLHeader(){
    		header("Content-Type: text/xml; charset=UTF-8");
		    echo '<?xml version="1.0" encoding="UTF-8"?>'; 
		    echo '<source>';
		}

		static function XMLFooter(){
			echo '</source>';
			exit;
			die;
		}

		static function outputXML($additional_args){
			self::XMLHeader();
			self::XMLFooter();
		}

		static function buildThreeByTwo($id){
			$contract_length = get_post_meta( $id, 'week_day', true );
			$hours = get_post_meta( $id, 'hours', true );
			if(!empty($hours)) $contract_length .= " at $hours/week";

			$descArray = array(
				'profession'		=> self::getTerm($id, 'job_listing_profession'),
				'date_range'		=> date('m/d/Y',strtotime(get_post_meta($id, 'start_date', true))) . ' to ' . date('m/d/Y',strtotime(get_post_meta($id, 'end_date', true))),
				'pay'				=> get_post_meta( $id, 'pay_package', true),
				'job_type'			=> self::getTerm($id, 'job_listing_type'),
				'shift'				=> get_post_meta( $id, 'shift', true ),
				'contract_length'	=> $contract_length						
			);

			$domain = get_site_url();
			return '
			<table>
				<tr>
					<td>
						<img class="image" src="'.$domain.'/wp-content/uploads/2022/04/heart.png" alt="">
                		'.$descArray['profession'].'
                	</td>
					<td>
						<img class="image" src="'.$domain.'/wp-content/uploads/2022/04/date.png" alt="">
                		'.$descArray['date_range'].'
                	</td>
					<td>
						<img class="image" src="'.$domain.'/wp-content/uploads/2022/04/pay.png" alt="">
                		'.$descArray['pay'].'
                	</td>
                </tr>
				<tr>
					<td>
						<img class="image" src="'.$domain.'/wp-content/uploads/2022/04/travel.png" alt="">
                		'.$descArray['job_type'].'
                	</td>
					<td>
						<img class="image" src="'.$domain.'/wp-content/uploads/2022/04/sun.png" alt="">
                		'.$descArray['shift'].'
                	</td>
					<td>
						<img class="image" src="'.$domain.'/wp-content/uploads/2022/04/clock.png" alt="">
                		'.$descArray['contract_length'].'
                	</td>
                </tr>
            </table>';
		}

		static function benefits(){
			return '
                <h2>BENEFITS OF BEING GIFTED!</h2>
                <ul>
                    <li>
                        Competitive pay packages 
                    </li>
                    <li>
                        Career Flexibility
                    </li>
                    <li>
                        Day one Medical, Dental and Vision <br>Plan from a National Provider
                    </li>
                    <li>
                        Housing and Meal Stipend
                    </li>
                    <li>
                        Contracts with Premier Facilities 
                    </li>

                </ul>
                <ul>
                    <li>
                        Referral bonuses – earn $1,000 for each <br>
                        friend you refer to Gifted Healthcare
                    </li>
                    <li>
                        24/7 support with Career Specialist
                    </li>
                    <li>
                        Access to Social Worker 24/7
                    </li>
                    <li>
                        Access to Chief Nursing Officer 24/7
                    </li>
                    <li>
                        Short- and Long-Term Disability
                    </li>
                    <li>
                        AD&D Insurance
                    </li>
                </ul>

                ';
		}

		static function buildDescription($id, $the_content, $doThreeByTwo=true, $doBenefits = true){
			# Add the fixed-in-template stuff to the feed description.
			# Can I do this by turning the block into separate includes for the child theme?

				$threeByTwo = ($doThreeByTwo)? self::buildThreeByTwo($id) : '';
				$benefits   = ($doBenefits)? self::benefits() : '';

				return "$threeByTwo $the_content $benefits";
		}

		static function getTerm($id, $taxonomy){
			$terms = get_the_terms($id, $taxonomy);

			if(!is_array($terms)) return '';

			$terms = array_pop($terms);
			return $terms->name;
		}

		static function getTermCTM($id, $taxonomy){
			$terms = get_the_terms($id, $taxonomy);

			if(!is_array($terms)) return '';

			$terms = array_pop($terms);

			return get_term_meta($terms->term_id, 'ctm_text', true);
		}

		static function outputNode($nodeData){
	        echo '<job>';
	            foreach($nodeData as $tag => $data){
	                echo "<$tag><![CDATA[".addslashes($data)."]]></$tag>";
	            }
	        echo '</job>';
		}
	}
?>