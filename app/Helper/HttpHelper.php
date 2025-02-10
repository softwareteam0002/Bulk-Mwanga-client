<?php
/**
 * Author: Memory Mwageni <memorymwageni@gmail.com>
 * Date: 09/07/2019
 * Time: 20:19
 */

namespace App\Helper;

use Illuminate\Support\Facades\Log;


class HttpHelper
{
    /*
     * SIMULATION ENDPOINT
     */

    const API_ENDPOINT_NAMECHECK_SIMULATION = "https://uat.ubx.co.tz:8888/mhb_external-disbursement/api-simulate/customer-name-search-vd";
    const API_ENDPOINT_DISBURSE_SIMULATION = "https://uat.ubx.co.tz:8888/mhb_external-disbursement/api-simulate/disburse";


    //MNO Disbursement API`s
    //PRODUCTION
    const API_ENDPOINT_DISBURSE = "http://ipg.prod.vodacom.co.tz:5445/b2c_portal";
    const API_ENDPOINT_CUSTOMER_NAME_SEARCH_VODACOM = "https://ipg.prod.vodacom.co.tz:3304/app";
    const API_ENDPOINT_BALANCE = "http://ipg.prod.vodacom.co.tz:5445/b2c_portal";

    //BANK Disbursement API`s
    //PRODUCTION
    const API_ENDPOINT_BANK_ACCOUNT_NAMECHECK = "https://ipg.prod.vodacom.co.tz:3304/app";
    const API_ENDPONT_BANK_ACCOUNT_NAMECHECK = "https://ipg.prod.vodacom.co.tz:3304/app";
    const API_ENDPONT_BANK_DISBURSE = "http://ipg.prod.vodacom.co.tz:5445/b2c_portal";


    const API_ENDPOINT_SMS = 'https://vodashule.vodacom.co.tz/api-services';
    /**
     * If set to 1; Request will not be sent, the xml to be sent will be displayed
     */
    const SHOW_REQUEST_XML = 0;
    const DEBUG = 0;

    /**
     * @param $data
     * @param bool $is_xml
     * @param string $return
     * @param $url
     * @return array
     */
    public static function send($data, $is_xml = false, $return = 'object', $url = null)
    {
        $xml = $is_xml ? $data : XMLHelper::arrayToXML($data, 'Request');
        if (self::SHOW_REQUEST_XML) {
            dd($xml);
            die();
        }

        if (self::DEBUG) {
            echo htmlentities('URL:' . $url . "\n" . 'Request: ' . $xml . "\n");
        }

        $ch = curl_init();
        $headers = ['Content-Type: text/xml', 'Cookie: ROUTEID=.1'];

        set_time_limit(40);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($code == 200) {
            if (self::DEBUG) {
                echo htmlentities('Response: ' . $response . "\n");
            }
            if ($return != 'object') {
                $data = $response;
            } else {
                $data = XMLHelper::XMLStringToObject($response);
            }
        } else {
            $error = sprintf('HTTP Status : %s, Error: %s, Response: %s', $code, $error, $response);
            Log::error($error);
            $data = null;
            //echo $error . PHP_EOL;
            if (self::DEBUG) {
                echo htmlentities($error . "\n");
            }

        }

        return ['code' => $code, 'data' => $data, 'error' => $error];
    }

    public static function sendCurlRequest($url, $method = 'POST', $data = [], $username = '', $password = '')
    {
        // Initialize cURL session
        $ch = curl_init();

        // Set common options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        // If username and password are provided, set the Basic Authentication header
        if (!empty($username) && !empty($password)) {
            curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password); // Set Basic Auth directly
        }

        // Set the request method and data
        if (strtoupper($method) === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Convert array to JSON
        } elseif (strtoupper($method) === 'GET' && !empty($data)) {
            $url .= '?' . http_build_query($data);
            curl_setopt($ch, CURLOPT_URL, $url);
        }

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return ['error' => $error];
        }

        // Close cURL session
        curl_close($ch);

        // Return the response as an associative array (decoded JSON)
        return json_decode($response);
    }

    /**
     * @param $body
     * @param $responseCode
     *
     * C&P from https://gist.github.com/bubba-h57/32593b2b970366d24be7
     */

    public static function closeConnection($body, $responseCode)
    {
        // Cause we are clever and don't want the rest of the script to be bound by a timeout.
        // Set to zero so no time limit is imposed from here on out.
        set_time_limit(0);
        // Client disconnect should NOT abort our script execution
        ignore_user_abort(true);
        // Clean (erase) the output buffer and turn off output buffering
        // in case there was anything up in there to begin with.
        @ob_end_clean();
        // Turn on output buffering, because ... we just turned it off ...
        // if it was on.
        ob_start();
        echo $body;
        // Return the length of the output buffer
        $size = ob_get_length();
        // send headers to tell the browser to close the connection
        // remember, the headers must be called prior to any actual
        // input being sent via our flush(es) below.
        header("Connection: close\r\n");
        header("Content-Encoding: none\r\n");
        header("Content-Length: $size");
        // Set the HTTP response code
        // this is only available in PHP 5.4.0 or greater
        http_response_code($responseCode);
        // Flush (send) the output buffer and turn off output buffering
        ob_end_flush();
        // Flush (send) the output buffer
        // This looks like overkill, but trust me. I know, you really don't need this
        // unless you do need it, in which case, you will be glad you had it!
        @ob_flush();
        // Flush system output buffer
        // I know, more over kill looking stuff, but this
        // Flushes the system write buffers of PHP and whatever backend PHP is using
        // (CGI, a web server, etc). This attempts to push current output all the way
        // to the browser with a few caveats.
        flush();
    }

    public static function guessFailureReason($httpCode, $error)
    {
        if ($httpCode == 401 || $httpCode == 403) {
            return "AUTHENTICATION";
        } else if ($httpCode == 408 || preg_match("/timeout/", strtolower($error))) {
            return "TIMEOUT";
        }

        return 'NETWORK';
    }
}
