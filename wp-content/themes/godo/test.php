<?php

class BullhornAPI {
    const API_USERNAME = 'godo.api';
    const API_PASSWORD = 'Liekeisdebest123!';
    const CLIENT_ID = 'eb70e8b5-3260-43b3-bdc2-148e6904aa19';
    const CLIENT_SECRET = '26tfplHVvXjatjIm9YcPELc0';
    const ENDPOINT_AUTH_CODE = 'https://auth.bullhornstaffing.com/oauth/authorize?%s';
    const ENDPOINT_ACCESS_TOKEN = 'https://rest-west.bullhornstaffing.com/oauth/token?%s';
    const ENDPOINT_REST_TOKEN = 'https://rest.bullhornstaffing.com/rest-services/login?version=*&access_token=%s';

    private $access_token;
    private $auth_code;
    private $comm_response;
    private $comm_response_info;
    private $errors = array();
    private $is_dev = TRUE;
    private $refresh_token;
    private $rest_token;
    private $rest_endpoint_url;

    public function __construct() {
        if(function_exists('is_dev')) :
            $this->is_dev = (bool) is_dev();
        endif;
    }

    public function jobsFetchAll() {
        if(TRUE !== $this->oAuth()) :
            return FALSE;

        endif;

        $query = 'fields=title,categories&where=isDeleted=false';

        if(FALSE === $this->comm(sprintf('query/JobOrder?%s', $query), 'GET')) :
            return FALSE;
        endif;

        return $this->comm_response;
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
            case 'FILE' :
                if(!isset($endpoint_args['file'])) :
                    $this->errorsSet('missing argument "file"', __LINE__, __METHOD__);
                    return FALSE;
                endif;

                $file = $endpoint_args['file'];
                unset($endpoint_args['file']);

                if(FALSE === is_readable($file['path'])) :
                    $this->errorsSet('Failed to locate path', __LINE__, __METHOD__);
                    return FALSE;
                else :
                    $file['contents'] = file_get_contents($file['path']);
                endif;

                $multipart_name = !isset($file['name']) || empty($file['name']) ? 'Unknown' : $file['name'];
                $multipart_filename = !isset($file['filename']) || empty($file['filename']) ? 'unknown' : $file['filename'];

                $multipart_new_line = "\r\n";
                $multipart_boundary = md5(time());
                $multipart_body  = '--'.$multipart_boundary.$multipart_new_line;
                $multipart_body .= 'Content-Disposition: form-data; name="'.$multipart_name.'"; filename="'.$multipart_filename.'"'.$multipart_new_line;
                $multipart_body .= 'Content-Length: '.strlen($file['contents']).$multipart_new_line;
                $multipart_body .= 'Content-Type: application/octet-stream'.$multipart_new_line;
                $multipart_body .= 'Content-Transfer-Encoding: binary'.$multipart_new_line.$multipart_new_line;
                $multipart_body .= $file['contents'].$multipart_new_line;
                $multipart_body .= '--'.$multipart_boundary.'--'.$multipart_new_line.$multipart_new_line;

                $endpoint_url_qs = empty($endpoint_args) ? '' : http_build_query($endpoint_args);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $endpoint_url.$endpoint_url_qs);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); # Don't use CURLOPT_PUT (results in a "read timed out" error)
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $multipart_body);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: multipart/form-data; boundary='.$multipart_boundary,
                ));
                break;
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
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::ENDPOINT_TIMEOUT);
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
        if(FALSE === $this->is_dev || FALSE === self::SHOW_LOG) :
            return;
        endif;

        echo $msg.'<br>';
    }

    public function oAuth() {
        if('' === self::CLIENT_ID || '' === self::CLIENT_SECRET || '' === self::API_USERNAME || '' === self::API_PASSWORD) :
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
            'client_id' => self::CLIENT_ID,
            'response_type' => 'code',
            'username' => self::API_USERNAME,
            'password' => self::API_PASSWORD,
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
            'client_id' => self::CLIENT_ID,
            'client_secret' => self::CLIENT_SECRET,
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

    private function searchBuildQuery($query_fields=array(), $select_args=array()) {
        $operator_groups = array('and', 'or', 'custom');

        # internal_field => bullhorn_field
        # This list could be greatly expanded...
        $possible_fields = array(
            'id' => 'id',
            'ID' => 'id',
            'first_name' => 'firstName',
            'firstName' => 'firstName',
            'last_name' => 'lastName',
            'email' => 'email',
            'is_deleted' => 'isDeleted',
            'isDeleted' => 'isDeleted',
        );

        if(empty($query_fields)) :
            $this->errorsSet('Query fields cannot be empty.', __LINE__, __METHOD__);
            return FALSE;
        endif;

        $valid_operator = FALSE;
        foreach($operator_groups as $operator) :
            if(!isset($query_fields[$operator]) || empty($query_fields[$operator]) || !is_array($query_fields[$operator])) :
                continue;
            endif;

            $valid_operator = TRUE;
            break;
        endforeach;

        if(FALSE === $valid_operator) :
            $this->errorsSet('Invalid query fields.', __LINE__, __METHOD__);
            return FALSE;
        endif;

        # Translate $select_args into BH field names
        foreach($select_args as $k => $v) :
            if(!isset($possible_fields[$v])) :
                unset($select_args[$operator][$k]);
                continue;
            endif;

            $select_args[$k] = $possible_fields[$v];
        endforeach;

        if(empty($select_args)) :
            $this->errorsSet('Select cannot be empty.', __LINE__, __METHOD__);
            return FALSE;
        endif;

        $return = array(
            'query' => '',
            'fields' => implode(',', $select_args),
        );

        foreach($query_fields as $operator_group => $operator_group_fields) :
            $return['query'] .= empty($return['query']) ? '' : ' AND ';

            if('custom' === $operator_group) :
                $return['query'] .= $operator_group_fields;
                continue;
            endif;

            $operator_fields = array();

            foreach($operator_group_fields as $operator_group_field => $operator_group_field_value) :
                if(!isset($possible_fields[$operator_group_field]) || in_array(trim($operator_group_field_value), array(NULL, ''))) :
                    continue;
                endif;

                $operator_fields[] = $possible_fields[$operator_group_field].':'.$operator_group_field_value;
            endforeach;

            $return['query'] .= '('.implode(' '.strtoupper($operator_group).' ', $operator_fields).')';
        endforeach;

        if(empty($return['query'])) :
            $this->errorsSet('Invalid query fields', __LINE__, __METHOD__);
            return NULL;
        endif;

        return http_build_query($return);
    }
}

// End BullhornAPI class