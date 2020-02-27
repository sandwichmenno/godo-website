<?php
class BullhornAPI {
    protected $API_USERNAME = '';
    protected $API_PASSWORD = '';
    protected $CLIENT_ID = '';
    protected $CLIENT_SECRET = '';

    const ENDPOINT_AUTH_CODE = 'https://auth-ger.bullhornstaffing.com/oauth/authorize?%s';
    const ENDPOINT_ACCESS_TOKEN = 'https://rest-ger.bullhornstaffing.com/oauth/token?%s';
    const ENDPOINT_REST_TOKEN = 'https://rest.bullhornstaffing.com/rest-services/login?version=*&access_token=%s';

    const PUBLIC_ERROR = 'We\'re unable to process your application at this time. Contact a site administrator to learn more.';

    public $access_token;
    protected $auth_code;
    protected $refresh_token;
    protected $rest_token;

    private $comm_response;
    private $comm_response_info;
    private $errors = array();
    private $is_dev = TRUE;
    private $show_log = FALSE;
    private $rest_endpoint_url;

    protected $_bullhorn_settings;

    public function __construct() {
        if(function_exists('is_dev')) :
            $this->is_dev = (bool) is_dev();
        endif;

        $options = get_option( 'bullhorn_settings' );
        $this->API_USERNAME = $options['username'];
        $this->API_PASSWORD = $options['password'];
        $this->CLIENT_ID = $options['client_id'];
        $this->CLIENT_SECRET = $options['client_secret'];
    }

    public function validateSubmission($data, $files) {
        $required = array('firstName', 'lastName', 'email', 'phone');
        $errors = array();

        foreach($required as $fieldName) {
            if(empty($data[$fieldName])) {
                $errors[$fieldName] = 'Dit veld is verplicht';
            }
        }

        if(empty($files) && empty($data['website'])) {
            $errors['website'] = '';
            $errors['drop-zone'] = 'Je moet een offline of online cv/portfolio/LinkedIn toevoegen';
        }

        if(empty($errors)) {
            return null;
        } else {
            return $errors;
        }
    }

    public function addJobSubmission($candidate_id, $job_id, $comment) {
        $timestamp = time();
        $candidate = array("id" => $candidate_id);
        $job = array("id" => $job_id);

        $submission_data = array(
            'candidate' => $candidate,
            'jobOrder' => $job,
            'status' => 'New Lead',
            'dateWebResponse' => $timestamp,
            'comments' => $comment
        );

        $submission = $this->comm('entity/JobSubmission?%s', 'PUT', $submission_data);

        if(FALSE === $submission) :
            return FALSE;
        endif;

        return $submission;
    }

    public function createCandidate($person, $resume_files) {
        $candidate = $this->comm('entity/Candidate?%s', 'PUT', $person);
        $candidate_id = $candidate['changedEntityId'];

        if(FALSE === $candidate) :
            return FALSE;
        endif;

        if(NULL !== $resume_files) :
            $files = $this->candidateAttachResume($candidate_id, $resume_files);
            if(FALSE === $files) :
                return FALSE;
            endif;
        endif;

        return $candidate_id;
    }

    public function candidateAttachResume($candidate_id, $resume_files) {
        if(NULL !== $resume_files) {
            $encoded_files = array();

            foreach($resume_files['tmp_name'] as $key=>$resume) {
                $filedata = file_get_contents($resume, false);
                $filecontent = base64_encode($filedata);

                $file = array(
                    'externalID' => 'PORTFOLIO/RESUME',
                    'fileContent' => $filecontent,
                    'fileType' => 'SAMPLE',
                    'name' => $resume_files['name'][$key],
                    'content_type' => $resume_files['type'][$key],
                    'description' => 'Candidate file uploaded via the GoDo website',
                );
                array_push($encoded_files, $resume);

                $files = $this->comm('file/Candidate/' . $candidate_id . '?%s', 'PUT', $file);
                if(FALSE === $files) :
                    return FALSE;
                endif;
            }
        }
    }

    public function findCandidate($person) {
        if(TRUE !== $this->oAuth()) :
            return FALSE;
        endif;

        $query = 'query=email:'.$person['email'].'&fields=id';

        if(FALSE === $this->comm(sprintf('search/Candidate?%s', $query), 'GET')) :
            return FALSE;
        endif;

        $perfect_matches = array();
        foreach($this->comm_response['data'] as $match) :
            if(1 !== (int) $match['_score']) :
                continue;
            endif;

            $perfect_matches[] = $match;
        endforeach;

        if(empty($perfect_matches)) :
            return NULL;
        endif;

        return $perfect_matches[0];
    }

    public function jobFetch($id) {
        if(TRUE !== $this->oAuth()) :
            return FALSE;
        endif;

        $query = 'fields=id,title,address,categories,skills,publicDescription,owner&where=id=' . $id;

        if(FALSE === $this->comm(sprintf('query/JobOrder?%s', $query), 'GET')) :
            return FALSE;
        endif;

        return $this->comm_response['data'];
    }

    public function jobsFetchAll() {
        if(TRUE !== $this->oAuth()) :
            return FALSE;
        endif;

        //+AND+isPublic=1
        $query = 'fields=id,title,address,categories,skills&where=isDeleted=false';

        if(FALSE === $this->comm(sprintf('query/JobOrder?%s', $query), 'GET')) :
            return FALSE;
        endif;

        return $this->comm_response['data'];
    }

    private function comm($endpoint_script=NULL, $endpoint_method=NULL, $endpoint_args=array(), $endpoint_url=NULL, $verify_response=TRUE) {
        $endpoint_args = !is_array($endpoint_args) ? array() : $endpoint_args;

        $this->comm_response = NULL;
        $this->comm_response_info = NULL;

        if(NULL === $endpoint_url) :
            $endpoint_url = $this->rest_endpoint_url.ltrim($endpoint_script, '/');
        endif;

        if(!empty($this->rest_token)) :
            $bh_token_param = '?BhRestToken='.urlencode($this->rest_token).'&';

            if(strstr($endpoint_url, '?')) :
                $endpoint_url = str_replace('?', $bh_token_param, $endpoint_url);
            else :
                $endpoint_url = $endpoint_url.$bh_token_param;
            endif;
        elseif(strstr($endpoint_url, '?')) :
            $endpoint_url = rtrim($endpoint_url, '&').'&';
        else :
            $endpoint_url = $endpoint_url.'?';
        endif;

        switch($endpoint_method) :
            case 'GET' :
                $endpoint_url_qs = empty($endpoint_args) ? '' : http_build_query($endpoint_args);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $endpoint_url.$endpoint_url_qs);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                break;
            case 'POST' :
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $endpoint_url);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_POST, TRUE);

                if(!empty($endpoint_args)) :
                    $endpoint_args_string = json_encode($endpoint_args);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $endpoint_args_string);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: '.strlen($endpoint_args_string),
                    ));
                endif;
                break;
            case 'PUT' :
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $endpoint_url);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); # Don't use CURLOPT_PUT (results in a "read timed out")

                if(!empty($endpoint_args)) :
                    $endpoint_args_string = json_encode($endpoint_args);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $endpoint_args_string);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: '.strlen($endpoint_args_string),
                    ));
                endif;
                break;
            default :
                ob_start();
                var_dump($method);

                $this->errorsSet(array(
                    'Comm error (method)',
                    'Invalid endpoint method ('.ob_get_clean().')',
                ), __LINE__, __METHOD__);

                return FALSE;
                break;
        endswitch;

        $this->comm_response = curl_exec($ch);
        $this->comm_response_info = curl_getinfo($ch);

        if(FALSE === $this->comm_response) :
            $this->errorsSet(array(
                'Comm error (cURL)',
                'Endpoint: '.$endpoint_url,
                'REST method: '.$endpoint_method,
                'REST args: '.((empty($endpoint_args)) ? 'NULL' : json_encode($endpoint_args)),
                'Error: '.curl_error($ch),
            ), __LINE__, __METHOD__);

            return FALSE;
        endif;

        if(TRUE === $verify_response) :
            if(NULL === ($this->comm_response = json_decode($this->comm_response, TRUE))) :
                $this->errorsSet(array(
                    'Comm error (Failed to decode response)',
                    'Endpoint: '.$endpoint_url,
                    'REST method: '.$endpoint_method,
                    'REST args: '.((empty($endpoint_args)) ? 'NULL' : json_encode($endpoint_args)),
                    'Response: '.$this->comm_response,
                    'Response info: '.json_encode($this->comm_response_info),
                ), __LINE__, __METHOD__);

                return FALSE;
            endif;

            if(!is_array($this->comm_response)) :
                $this->errorsSet(array(
                    'Comm error (Response is not an array)',
                    'Endpoint: '.$endpoint_url,
                    'REST method: '.$endpoint_method,
                    'REST args: '.((empty($endpoint_args)) ? 'NULL' : json_encode($endpoint_args)),
                    'Response: '.$this->comm_response,
                    'Response info: '.json_encode($this->comm_response_info),
                ), __LINE__, __METHOD__);

                return FALSE;
            endif;
        endif;

        if(is_array($this->comm_response) && isset($this->comm_response['error']) || isset($this->comm_response['errorCode'])) :
            $this->errorsSet(array(
                'Comm error (Endpoint error)',
                'Endpoint: '.$endpoint_url,
                'REST method: '.$endpoint_method,
                'REST args: '.((empty($endpoint_args)) ? 'NULL' : json_encode($endpoint_args)),
                'Response: '.json_encode($this->comm_response),
                'Response info: '.json_encode($this->comm_response_info),
            ), __LINE__, __METHOD__);

            return FALSE;
        endif;

        curl_close($ch);
        return $this->comm_response;
    }

    public function errorsGetAll() {
        return $this->errors;
    }

    public function errorsGetLast() {
        return empty($this->errors) ? NULL : end($this->errors);
    }

    private function errorsSet($message=NULL, $line=NULL, $method=NULL) {
        if(TRUE !== $this->is_dev) :
            $message = self::PUBLIC_ERROR;
        endif;

        $message = !is_array($message) ? array($message) : $message;
        $prefix = '';
        $suffix = '';

        if(TRUE !== $this->is_dev) :
            $prefix = empty($line) ? '' : '[Err'.$line.']';
        else :
            array_unshift($message, 'Method: '.$method, 'Line: '.$line);
        endif;

        $message = implode('<br>', $message);
        $message = trim($prefix.' '.$message.' '.$suffix);

        $this->errors[] = array('method' => $method, 'line' => $line, 'message' => $message);
        return TRUE;
    }

    private function log($msg) {
        if(FALSE === $this->is_dev || FALSE === $this->show_log) :
            return;
        endif;

        echo $msg.'<br>';
    }

    public function bullhorn_authenticate() {
        if(TRUE === $this->oAuth()) :
            return array($this->access_token, $this->auth_code, $this->refresh_token, $this->rest_token);
        endif;
    }

    private function oAuth() {
        if('' === $this->CLIENT_ID || '' === $this->CLIENT_SECRET || '' === $this->API_USERNAME || '' === $this->API_PASSWORD) :
            $this->errorsSet(array(
                'Empty client id, client secret, api username, or api password.',
                'Follow instructions located at: http://developer.bullhorn.com/articles/getting_started',
                'Hint: Request API access via support ticket',
            ), __LINE__, __METHOD__);

            return FALSE;
        endif;

        if(TRUE !== $this->oAuthSetRestToken()) :
            return FALSE;
        endif;

        if(TRUE !== $this->oAuthLogin()) :
            return FALSE;
        endif;

        return TRUE;
    }

    private function oAuthLogin() {
        if(FALSE === $this->comm(NULL, 'POST', NULL, sprintf(self::ENDPOINT_REST_TOKEN, urlencode($this->access_token)))) :
            return FALSE;
        endif;

        $this->rest_endpoint_url = $this->comm_response['restUrl'];
        $this->rest_token = $this->comm_response['BhRestToken'];

        return TRUE;
    }

    private function oAuthSetAuthCode($force=FALSE) {
        if(FALSE === $force && !empty($this->auth_code)) :
            return TRUE;
        endif;

        $comm_args = array(
            'client_id' => $this->CLIENT_ID,
            'response_type' => 'code',
            'username' => $this->API_USERNAME,
            'password' => $this->API_PASSWORD,
            'action' => 'Login',
        );

        $comm_url = sprintf(self::ENDPOINT_AUTH_CODE, http_build_query($comm_args));

        if(FALSE === $this->comm(NULL, 'GET', NULL, $comm_url, FALSE)) :
            return FALSE;
        endif;

        if(!empty($this->comm_response_info) && preg_match('@\?code=(.*)&@i', $this->comm_response_info['url'], $auth_code)) :
            $this->auth_code = urldecode($auth_code[1]);
            $this->log('Setting auth code: "'.$this->auth_code.'"');

            return TRUE;
        endif;

        $this->errorsSet(array(
            'Failed to retreive auth code',
            'Endpoint: '.$comm_url,
            'REST method: GET',
            'REST args: '.((empty($comm_args)) ? 'NULL' : json_encode($comm_args)),
            'Response: '.$this->comm_response,
            'Response info: '.json_encode($this->comm_response_info),
        ), __LINE__, __METHOD__);

        return FALSE;
    }

    private function oAuthSetRestToken() {

        if(FALSE === $this->oAuthSetAuthCode()) :
            return FALSE;
        endif;

        # Fetch access token
        $comm_args = array(
            'grant_type' => 'authorization_code',
            'code' => $this->auth_code,
            'client_id' => $this->CLIENT_ID,
            'client_secret' => $this->CLIENT_SECRET,
        );

        if(FALSE === $this->comm(NULL, 'POST', NULL, sprintf(self::ENDPOINT_ACCESS_TOKEN, http_build_query($comm_args)))) :
            return FALSE;
        endif;

        $this->log('Setting access token: "'.$this->comm_response['access_token'].'"');
        $this->log('Setting refresh token: "'.$this->comm_response['refresh_token'].'"');

        $this->access_token = $this->comm_response['access_token'];
        $this->refresh_token = $this->comm_response['refresh_token'];

        return TRUE;
    }
}
// End BullhornAPI class