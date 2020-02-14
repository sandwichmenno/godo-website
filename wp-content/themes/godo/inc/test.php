<?php
require_once 'BullhornAPI.php';

# ...

if($_POST) :
    $bh_obj = new BullhornAPI();
    $candidate_data = $_POST;
    $form_status = NULL;

    # Validate resume upload
    if(NULL === $form_status) :
        if($_FILES) :
            if(FALSE === ($resume = validateUpload('resume'))) :
                $form_status = array('status' => 'error', 'message' => uploadError());
                unset($resume);
            endif;
        endif;
    endif;

    # Look for duplicate applicant
    if(NULL === $form_status) :
        $duplicate_candidates = $bh_obj->candidateFind(array('first_name' => $candidate_data['firstName'], 'last_name' => $candidate_data['lastName'], 'email' => $candidate_data['email']), TRUE);

        if(FALSE === $duplicate_candidates) :
            if(NULL === ($bh_error = $bh_obj->errorsGetLast())) :
                $bh_error = '('.__LINE__.') An unknown error occurred';
            endif;

            $form_status = array('status' => 'error', 'message' => $bh_error['message']);
        elseif(NULL !== $duplicate_candidates) :
            $duplicate_comment = 'DUP:'.implode(',', $duplicate_candidates);

            # "occupation" is the field name, Quick Notes is the field label...
            if(isset($candidate_data['occupation'])) :
                $candidate_data['occupation'] = trim($candidate_data['occupation'])."\n\n".$duplicate_comment;
            else :
                $candidate_data['occupation'] = $duplicate_comment;
            endif;
        endif;
    endif;

    # Create the candidate
    if(NULL === $form_status) :
        $candidate_data = array_merge($candidate_data, array(
            'firstName' => $candidate_data['firstName'],
            'lastName' => $candidate_data['lastName'],
            'name' => trim($candidate_data['firstName'].' '.$candidate_data['lastName']),
            'description' => 'Website applicant',
            'email' => $candidate_data['email'],
            'address' => array(
                'address1' => $candidate_data['address1'],
                'address2' => $candidate_data['address2'],
                'city' => $candidate_data['city'],
                'state' => $candidate_data['state'],
                'zip' => $candidate_data['zip'],
                'countryID' => $candidate_data['countryID'],
            ),
        ));

        # Ditch keys Bullhorn will complain about
        unset($candidate_data['address1']);
        unset($candidate_data['address2']);
        unset($candidate_data['city']);
        unset($candidate_data['state']);
        unset($candidate_data['zip']);
        unset($candidate_data['countryID']);

        if($has_resume) :
            $resume_filename = basename($resume['data']['tmp_name']);
            $resume_path = $resume['data']['tmp_name'];
        else :
            $resume_filename = $resume_path = NULL;
        endif;

        if(FALSE === ($candidate_id = $bh_obj->candidateCreate($candidate_data, $resume_path, $resume_filename))) :
            if(NULL === ($bh_error = $bh_obj->errorsGetLast())) :
                $bh_error = '('.__LINE__.') An unknown error occurred';
            endif;

            $form_status = array('status' => 'error', 'message' => $bh_error['message']);
        endif;
    endif;

    if(NULL === $form_status) :
        redirect('to/success/page');
    endif;

    # Do something with $form_status
endif;

# ...