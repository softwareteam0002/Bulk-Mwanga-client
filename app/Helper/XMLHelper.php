<?php
/**
* Author: Memory Mwageni <memorymwageni@gmail.com>
 * Date: 09/07/2019
 * Time: 20:19
 */

namespace App\Helper;

use Illuminate\Support\Facades\Log;
use SimpleXMLElement;


class XMLHelper
{

    public static function XMLStringToArray($xml_string)
    {
        try{
            return json_decode(json_encode((array)simplexml_load_string($xml_string)), true);
        }catch (\Exception $e){
            Log::error("Failed to decode XML: $xml_string");
            return null;
        }
    }

    public static function XMLStringToObject($xml_string)
    {
        try{
            return json_decode(json_encode((array)simplexml_load_string($xml_string)));
        }catch (\Exception $e){
            Log::error("Failed to decode XML: $xml_string");
            return null;
        }
    }

    public static function arrayToXML($array,$parentNode='Request')
    {
        $xml = new SimpleXMLElement("<{$parentNode}/>");
        self::_arrayToXML($array, $xml);
        $doc = dom_import_simplexml($xml)->ownerDocument;
        $doc->encoding = 'UTF-8';
        return $xml->asXML();
    }

    private static function _arrayToXML($array, &$xml)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (is_int($key)) {
                    self::_arrayToXML($value, $xml);
                } else {
                    $label = $xml->addChild($key);
                    self::_arrayToXML($value, $label);
                }
            } else {
                $xml->addChild($key, htmlspecialchars($value));
            }
        }
    }


    public static function soapXMLToArray($soap){
        return self::XMLStringToArray(preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $soap));
    }

    public static function  xmlDataItemValue($key,$data_items){
        foreach ($data_items as $item){
            if (!empty($item['Name'])){
                if (strtolower($item['Name'])==strtolower($key)){
                    return $item['Value'];
                }
            }else if(!empty($item['name']) && strtolower($item['name'])==strtolower($key)){
                return $item['value'];
            }

        }
        return null;
    }


    public static function soapItem($name,$value,$type='String'){
        return ['dataItem'=>['name'=>$name,'value'=>$value,'type'=>$type]];
    }



    public static function wrapSoap($event_id,$token,$data,$parent_node='Request'){
        $xml = str_replace('<?xml version="1.0" encoding="UTF-8" standalone="no"?>','',self::arrayToXML($data,$parent_node));
        return "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:soap=\"http://www.4cgroup.co.za/soapauth\" xmlns:gen=\"http://www.4cgroup.co.za/genericsoap\">
                   <soapenv:Header>
                      <soap:Token>{$token}</soap:Token>
                      <soap:EventID>{$event_id}</soap:EventID>
                   </soapenv:Header>
                   <soapenv:Body>
                      <gen:getGenericResult>
                      ".$xml."
                      </gen:getGenericResult>
                   </soapenv:Body>
                </soapenv:Envelope>";
    }
}
