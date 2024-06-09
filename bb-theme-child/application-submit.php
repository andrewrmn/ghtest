<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

    if(!empty($_POST['goback'])){
        $goback_parts = explode('?',$_POST['goback']);
        $goback = $goback_parts[0];
        $queryString = $goback_parts[1];
    }
    else{
        $goback = '/apply/';
        $queryString = '';
    }
    
    # Re-use the old defines because it's easier than substituting all across the script.  Plus, then I'm less likely to break something.
    define("quickapp_contingent_url",$tss_creds->endpoint_url);
    define("quickapp_contingent_un",$tss_creds->endpoint_un);
    define("quickapp_contingent_pw",$tss_creds->endpoint_pw);

    define("quickapp_hubspot_url","https://forms.hubspot.com/uploads/form/v2/4132613/eae8ede6-0d53-42b9-ac63-17349a3ce4c4");

    # Map which certs trigger 'Travel To':
    $license_certs = array(
        'RN',
        'LPN',
        'CNA',
        'Nurse Practitioner',
        'EMT',
    );
   
    # These will need modified later, when the Quick App is made powered by the WP Admin.
    # Contingent Certification to ID map:
    $cert_map = array(
        "Anesthesia Technician" => 124,
        'Cath Lab Tech' => 50,
        'Clinical Lab Scientist' => 80,
        'CNA' => 3,
        "CRNA" => 13,
        'CRT' => 9,
        'CST' => 4,
        "CT Technologist" => 107,
        "Cytotechnologist" => 114,
        "Echo Tech" => 104,
        "EEG Technologist" => 116,
        "EKG Tech" => 25,
        'EMT' => 26,
        "Endoscopy Tech" => 102,
        "ER Technician" => 119,
        "Histotechnologist" => 115,
        'LPN' => 2,
        'Medical Assistant' => 20,
        'Medical Technologist' => 91,
        'MLT' => 79,
        "Monitor Tech" => 34,
        "MRI Technologist" => 108,
        "Nuclear Medicine Technologist" => 109,
        'Nurse Practitioner' => 21,
        'ORT' => 6,
        "OT" => 11,
        "OTA" => 23,
        "Pharmacist" => 27,
        "Pharmacy Tech" => 88,
        "Phlebotomist" => 17,
        "Physician Assistant" => 28,
        "PT" => 10,
        "PTA" => 19,
        "Rad Tech" => 82,
        'RN' => 1,
        "RPSGT" => 113,
        'RRT' => 8,
        'Rad Tech' => 82,
        "Radiation Therapist" => 118,
        "Sleep Technologist" => 125,
        "Special Procedures Technician" => 110,
        "Speech Language Pathologist" => 38,
        'Sterile Processing Tech' => 31,
        'Sitter' => 5,
        "Surgical First Assistant" => 120,
        "Ultrasound Tech" => 78,
        "Vascular Technologist" => 112,
        "X-Ray Tech" => 15,
    );
   
    # Contingent Specialty map:
    $spec_map = array(
        'Anesthesia' => 'Anesthesia',
        'Assessment - RN' => 'Assessment - RN',
        'Asst Liv Unit' => 'Asst Liv Unit',
        'Case Mgmt' => 'Case Mgmt',
        'Cath Lab' => 'Cath Lab',
        'Cath Lab Tech' => 'Cath Lab',
        'Clinic' => 'Clinic',
        'Clinical Trials' => 'Clinical Trials',
        'CNA' => 'CNA',
        'Corrections' => 'Corrections',
        'CRT' => 'CRT',
        'Certified Surgical Tech' => 'CST',
        'CRNA' => 'CRNA',
        'CT Tech' => 'CT Tech',
        'CVICU' => 'CVICU',
        'CVOR' => 'CVOR',
        'Cytology' => 'Cytology',
        'Dialysis' => 'Dialysis',
        'E/M' => 'E/M',
        'Echocardiography' => 'Echocardiography',
        'EEG' => 'EEG',
        'EKG Technician' => 'EKG Technician',
        'EMT' => 'EMT',
        'Endoscopy' => 'Endoscopy',
        'ER' => 'ER',
        'Family Practice' => 'Family Practice',
        'First Assistant' => 'First Assistant',
        'Hematology' => 'Hematology',
        'Histology' => 'Histology',
        'Home Health' => 'Home Health',
        'Hospice' => 'Hospice',
        'ICU' => 'ICU',
        'IMC' => 'IMC',
        'Infusion' => 'Infusion',
        'Interventional Radiology' => 'Interventional Radiology',
        'L&D' => 'L&D',
        'Laboratory' => 'Laboratory',
        'LTAC' => 'LTAC',
        'LTC' => 'LTC',
        'Mammography' => 'Mamography', // Spelled wrong somewhere.
        'Med Surg' => 'MedSurg',
        'Med Asst' => 'Med Asst',
        'Monitor Tech' => 'Monitor Tech',
        'MRI' => 'MRI',
        'NICU 3' => 'NICU 3',
        'Nuclear Medicine' => 'Nuclear Medicine ',
        'Nursing Home' => 'Nursing Home',
        'OB/GYN' => 'Ob/Gyn',
        'Oncol' => 'Oncol',
        'OR' => 'OR',
        'ORT' => 'ORT',
        'Ortho' => 'Ortho',
        'OT' => 'OT',
        'OTA' => 'OTA',
        'PACU' => 'PACU',
        'PCU' => 'PCU',
        'Peds' => 'Peds',
        'Pharmacy' => 'Pharmacy',
        'Phlebotomy' => 'Phlebotomy',
        'Physician Assistant' => 'Physician Assistant',
        'PICU' => 'PICU',
        'Psych' => 'Psych',
        'PT' => 'PT',
        'PTA' => 'PTA',
        'Radiation' => 'Radiation',
        'Radiology' => 'Radiology',
        'Rehab' => 'Rehab',
        'Respiratory' => 'Respiratory',
        'Serology' => 'Serology',
        'Sitter' => 'Sitter',
        'Skilled Nursing' => 'Skilled Nursing',
        'Sleep Technologist' => 'Sleep Technologist',
        'Special Procedures' => 'Special Procedures',
        'Speech Language Pathologist' => 'Speech Language Pathologist',
        'Stepdown' => 'Stepdown',
        'Sterile Processing Tech' => 'Sterile Processing Tech ',
        'Tele' => 'Tele',
        'Ultrasound Tech' => 'Ultrasound Tech',
        'Vascular' => 'Vascular',
        'X-Ray' => 'X-Ray',
    );

    # Determine whether to process Travel To and Licensed In:
    $doTravel = false;
    $doTravel = true;
    $doLicenses = false;
   
    # When they're looking for Travel, always do 'Travel To' and Licenses.
    if(!empty($_POST['looking-for-check'])){
        foreach($_POST['looking-for-check'] as $value){
            if($value == 'Travel'){
                $doTravel = true;
            }
        }
    }
       
    # When they're in 'Licensed Certs', do the Licenses.
    if(in_array($_POST['certification-check'],$license_certs)){
        $doLicenses = true;
    }

    # If it's LTAC without recent experience, remap to LTC.
    if(!empty($_POST['ltac-check']) && $_POST['ltac-check'] == 'No' && $_POST['specialty-check'] == 'LTAC'){
        $_POST['specialty-check'] = 'LTC';
    }
   
/********************************************************************
 *                                                                  *
 *    Part 1: Contingent Candidate                                  *
 *                                                                  *
 ********************************************************************/

    # Map Certification for Contingent
    if($_POST['certification-check'] == 'Allied Health Professional'){
        $certs = $_POST['allied-certification-check'];
    }
    else{
        $certs = $_POST['certification-check'];
    }

    # Map Specialty for Contingent.
    if(!empty($_POST['specialty-check'])){
        $specs = $spec_map[$_POST['specialty-check']];
    }else{
        $specs = '';
    }
 
    # Map Notes for Contingent
    $notes = array();
    if(!empty($_POST['jobID']) && is_numeric($_POST['jobID'])){
        $remoteJobID = get_field('job_remote_system_id', $_POST['jobID']);
       
        $notes[] = 'Website Job ID:';
        $notes[] = '    '.$_POST['jobID'];

        if(!empty($remoteJobID)){
            $notes[] = 'TSS Job ID:';
            $notes[] = '    '.$remoteJobID;
        }
    }
   
    # Travel preferences in Notes: Only if Travel is needed.
    if($doTravel && !empty($_POST['travel-to-check'])){
        $notes[] = 'Travel Preference:';
       
        foreach($_POST['travel-to-check'] as $value){
                $notes[] = '    '.$value;
        }
    }
   
    if(!empty($_POST['superpower'])){
        $notes[] = 'Superpower:';
        $notes[] = '    '.$_POST['superpower'];        
    }

    $notes = implode("<br>",$notes);

    # Map the Region (these IDs are the same in both Testing and Live):
    if(!empty($_POST['ltac-check']) && $_POST['ltac-check'] == 'Yes'){
        $region = 29;
    }
    elseif($_POST['certification-check'] == 'RN' && strpos($specs,'Infusion')!== false){
        $region = 13;
    }
    else{
        $region = 8;
    }
   
    # Map "Looking For":
    if(!empty($_POST['looking-for-check'])){
        if(count((array)$_POST['looking-for-check']) == 2){
            $prnTravel = 2;
        }
        elseif(count((array)$_POST['looking-for-check']) == 1){
            foreach($_POST['looking-for-check'] as $value){
                if($value == 'Travel'){
                    $prnTravel = 1;
                }
                else{
                    $prnTravel = 0;
                }
            }
        }
        else{
            $prnTravel = 0;
        }
    }
    else{
        $prnTravel = 0;
    } 
//$prnTravel = 1;
    # Map 'Referred By'
    if(!empty($_POST['referred-by'])){
        $referredBy = $_POST['referred-by'];
    }
    else{
        $referredBy = 'Online Search/GH Website';
    }

    # First step: Have they already registered?
    $validation = array(
                'username' => quickapp_contingent_un,
                'password' => quickapp_contingent_pw,
                'action' => 'getTemps',
                'resultType' => 'json',
    );

    $payload = array(
        'emailStartsWith' => $_POST['email'],
    );
    //$handle = fopen("https://www.giftedhealthcare.com/wp-content/themes/bb-theme-child/test.txt","a");
   // fwrite($handle, "\r\n\r\n Payload for Temp Search: \r\n".print_r($payload,true));

    $validation = http_build_query($validation);
   
    $payload = http_build_query($payload);
  // print_r($_POST); exit;

    # Send it over.    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, quickapp_contingent_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$validation&$payload");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Connection: close"));

    $response = curl_exec($ch);
    $response = json_decode($response);

    if(curl_getinfo($ch,CURLINFO_HTTP_CODE) != 200){
       // fwrite($handle, "\r\n\r\n getTemps failed with ".curl_getinfo($ch,CURLINFO_HTTP_CODE));
       // fwrite($handle,print_r($response,true));
       // fclose($handle);
    }
    else{
        $doTempInsert = false;
        if(is_array($response) && empty($response)){
            $doTempInsert = true;
        }
        elseif(isset($response[0]->errorCode)){
           // fwrite($handle, "\r\n\r\n getTemps failed with ".curl_getinfo($ch,CURLINFO_HTTP_CODE));
//fwrite($handle,print_r($response,true));
           // fclose($handle);
            # Something went seriously wrong.
           // wp_redirect("$goback?$queryString&error=http");
            //exit;
        }
    }

    if($doTempInsert){
        # Second Step: New Contact
        $validation = array(
                    'username' => quickapp_contingent_un,
                    'password' => quickapp_contingent_pw,
                    'action' => 'InsertTempRecords',
                    'resultType' => 'json',
        );
       
        $payload = array(
                    'tempRecords' => array(
                        'tempRecord' => array(
                            'firstName' => $_POST['fname'],
                            'lastName' => $_POST['lname'],
                            'homeRegion' => $region, // On standby, Gifted conferring internally.  'Travel' is for testing.
                            'status' => 'Prospect',
                            'zip' => $_POST['zip'],
                            'phoneNumber' => $_POST['phoneNum'],
                            'cell_phone' => $_POST['phoneNum'],
                            'specialty' => $specs,
                            'certification' => $certs,
                            'notes' => $notes,
                            'email' => $_POST['email'],
                            'referredByName' => $referredBy,
                            'referralSourceId' => 25,
                            'prnTravel' => $prnTravel,
                        ),
                    ),
        );

       // fwrite($handle, "\r\n\r\n Payload for Contingent: \r\n".print_r($payload,true));

        $validation = http_build_query($validation);
       
        $payload = http_build_query(array('tempRecords'=>mind_array_to_xml_string($payload, false)));
       
        # Send it over.    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, quickapp_contingent_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$validation&$payload");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Connection: close"));

        $response = curl_exec($ch);

       
        if(curl_getinfo($ch,CURLINFO_HTTP_CODE) != 200){
          //  fwrite($handle, "\r\n\r\n InsertTempCandidate failed with ".curl_getinfo($ch,CURLINFO_HTTP_CODE));
            fclose($handle);
            # Something went seriously wrong.
            wp_redirect("$goback?$queryString&error=http");
            exit;
        }
        else{
            # Success.
            $response = substr($response,2,strlen($response) - 4);
            $response = stripslashes($response);
            $data = json_decode($response);
           
            fwrite($handle, "\r\n\r\n InsertTempCandidate returned with response ".print_r($data,true));

            if($data->success == 0){
                if(strpos($data->message,'A candidate with this email address already exists') !== false){
                    fclose($handle);
                    wp_redirect("$goback?$queryString&error=email");
                    exit;
                }
                else{
                    fclose($handle);
                    wp_redirect("$goback?$queryString&error=failure");
                    exit;
                }
            }
            else{
                # The base insertion was successful.  If licenses are necessary, build a payload for state licenses.            
                if($doLicenses){
                    $payload = '';
                    foreach($_POST['licensed-check'] as $value){
                        $sub_payload = array(
                            'stateLicenseRecord' => array(
                                'tempId' => $data->tempId,
                                'stateCode' => $value,
                                'certId' => $cert_map[$_POST['certification-check']]
                            )
                        );
                       
                        $payload .= mind_array_to_xml_string($sub_payload, false);
                       
                    };
                   
                    $payload = http_build_query(array("stateLicenseRecords" => "<stateLicenseRecords>$payload</stateLicenseRecords>"));

                    $validation = array(
                                'username' => quickapp_contingent_un,
                                'password' => quickapp_contingent_pw,
                                'action' => 'insertStateLicenses',
                                'resultType' => 'json',
                    );
                    $validation = http_build_query($validation);
                                   
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, quickapp_contingent_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "$validation&$payload");
                    //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "Content-Length: ".strlen($payload),"Connection: close"));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Connection: close"));
               
                    $response = curl_exec($ch);

                    if(curl_getinfo($ch,CURLINFO_HTTP_CODE) != 200){
                        # Something went seriously wrong.
                        fwrite($handle, "\r\n\r\n InsertTempCandidate failed with ".curl_getinfo($ch,CURLINFO_HTTP_CODE));
                        fclose($handle);
                        wp_redirect("$goback?$queryString&error=licenseHttp");
                        exit;
                    }
                    else{
                        $response = stripslashes($response);
                        $data = json_decode($response);
                       
                        foreach($data as $record){
                            if($record->success == 0){
                                fwrite($handle, "\r\n\r\n InsertStateLicenses failed");
                                fclose($handle);
                               wp_redirect("$goback?$queryString&error=licenseFailure");
                               exit;
                           }                  
                        }
                    }
                }

                # And now do the Resume, if one exists.
                if(!empty($_FILES['resume']) && empty($_FILES['resume']['error'])){
                    $validation = array(
                        'username' => quickapp_contingent_un,
                        'password' => quickapp_contingent_pw,
                        'action' => 'insertDocument',
                        'resultType' => 'json',
                    );
                    $validation = http_build_query($validation);

                    $mimetype = $_FILES['resume']['type'];
                    $mimetype = explode('/',$mimetype);

                    $fileContents = file_get_contents($_FILES['resume']['tmp_name']);

                    $payload = array(
                        'filename' => $_FILES['resume']['name'],
                        'contentType' => $mimetype[0],
                        'contentSubType' => $mimetype[1],
                        'docTypeId' => 13, // Resume for Temps
                        'filesize' => $_FILES['resume']['size'],
                        'contents' => base64_encode($fileContents),
                        'tempId' => $data->tempId,
                        'documentType' => 'Temp',
                        'fileNote' => 'Uploaded through Quick App'
                    );

                    $payload = http_build_query($payload);
                   
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, quickapp_contingent_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "$validation&$payload");
                    //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "Content-Length: ".strlen($payload),"Connection: close"));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Connection: close"));

                    $response = curl_exec($ch);
                }
            }
        }
    }

   
    # Next up: Tell Hubspot about it!
   
    # Pull the cookie
    $states = array();
    $license_states = array();
    $looking_for = array();

    $hs_cookie = array(
        'hutk' => $_POST['hubspotutk'],
        'ipAddress' => $_SERVER['REMOTE_ADDR'],
        'pageUrl' => $_SERVER['HTTP_REFERER'],
        'pageName' => 'Quick App Form'
    );
   // print_r($hs_cookie); exit;
    # Build the 'Notes' field.
    $notes = array();
    if(!empty($_POST['jobID'])){
        $notes[] = 'Job ID:';
        $notes[] = '    '.$_POST['jobID'];
    }
   
    if(!empty($_POST['looking-for-check'])){
        $notes[] = 'Looking For:';
        foreach(explode(', ',$_POST['looking-for-check']) as $value){
            $notes[] = '    '.$value;    
           
            if($value == 'Travel'){
                $looking_for[] = 'Travel';
            }
            elseif($value == 'Local Contract/PRN'){
                $looking_for[] = 'Per Diem/PRN';
            }else{
$looking_for[] = $value;
}
        }
    }
//print_r($looking_for);

    if($doTravel){
        $notes[] = 'Travel Preference:';
       
        foreach(explode(', ',$_POST['travel-to-check']) as $value){
            $notes[] = '    '.$value;
            $states[] = $value;
        }
   
    }
   //  $states=explode(', ',$_POST['travel-to-check']);
    // print_r($states); exit;
    if($doLicenses){
        $notes[] = 'State Licenses:';
        foreach($_POST['licensed-check'] as $value){
            $notes[] = '    '.$value;
            $license_states[] = $value;
        }
    }
   
    if(!empty($_POST['superpower'])){
        $notes[] = 'Superpower:';
        $notes[] = '    '.$_POST['superpower'];        
    }    

    $notes = implode("\r\n",$notes);
    # Assemble the Aveng---er, the payload.
	
	if(isset($_POST['email_notification']))
	{
		$email_notification=$_POST['email_notification'];
		if($email_notification=="Yes")
		{
			$email_text="By selecting this box I agree to receiving email from Gifted Healthcare - Yes";
		}
		else{
			$email_text="";
		}
	}
	else{
		$email_text="";
	}

    $payload = array(
        'hs_context' => json_encode($hs_cookie),
        'firstname' => $_POST['fname'],
        'lastname' => $_POST['lname'],
        'email' => $_POST['email'],
        'phone' => $_POST['phoneNum'],
        'zip' => $_POST['zip'],
        'certification' => $_POST['certification-check'],
        'what_is_your_primary_nursing_specialty' => $_POST['specialty-check'],
        'notes' => $notes,
        'referred_by' => $referredBy,
        //'quick_apply_job_title_and_location' => $_POST['jobtitle'],
        //'quick_apply_submission_page' => $_POST['joburl'],
        //'website_opt_in_notes' => $email_text,
        //'okay_to_contact' => 'Yes',
        'travel_preference__state_' => implode(';',$states),
        'state_licenses__form_' => implode(';',$license_states),
        //'travel_local_prn_government' => implode(';',$looking_for),
'ctm_worktype' => implode(';',$looking_for)
    );

    # LTAC or LTC?
    # If it's LTAC without recent experience, remap to LTC.
    if(!empty($_POST['ltac-check'])){
        $payload['do_you_have_current_vent__trach__drip_experience_'] = $_POST['ltac-check'];
    }
//echo quickapp_hubspot_url;
    //print_r($payload); exit;
   // fwrite($handle, "\r\n\r\n Payload for Hubspot: \r\n".print_r($payload,true));

    $payload = http_build_query($payload);
       
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, quickapp_hubspot_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Connection: close"));

    $response = curl_exec($ch);
//print_r(curl_getinfo($ch, CURLINFO_HTTP_CODE));
//echo"response check";
//exit;
    # If it gets here, the application was successfully sent. If the tracking code couldn't, note it in an error log but let the
    # user pass.
    if(curl_getinfo($ch, CURLINFO_HTTP_CODE) != 204){
        $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/app_hubspot_errors.txt','a');
        fwrite($fp,date('m/d/Y H:i:s').": Hubspot call for {$_POST['email']} failed.\r\n");
        fclose($fp);
    }

//fwrite($handle, "\r\n\r\n Quickapp Submission finished.");
   // fclose($handle);
    wp_redirect('/thank-you-job-application/');
    exit;
    function mind_array_to_xml_string($array, $do_cdata){
        $output = '';
       
        foreach($array as $key=>$value){
            $output.="<$key>";
           
            if(is_array($value)){
                if($key == 'tempRecord' || $key == 'stateLicenseRecord'){
                    $output .= mind_array_to_xml_string($value, true);        
                }
                else{
                    $output .= mind_array_to_xml_string($value, false);        
                }
               
            }
            else{
                if($do_cdata){
                    $output .= "<![CDATA[$value]]>";
                }
                else{
                    $output.= $value;
                }
            }
           
            $output.="</$key>";
        }
       
        return $output;
    }
?>