<?php
namespace TimeShow\Repository\Helpers;

use TimeShow\Repository\Exceptions\CustomException;

class DataHelper
{
    /**
     * object to array
     * @param $object
     * @return array
     *
     * @example $array['id'] => 11
     */
    public static function object2array($object) : array
    {
        $json = json_encode($object);
        return json_decode($json, true);
    }

    /**
     * array to object
     * @param $array
     * @return object|mixed
     *
     * @example array('profile' => ['mobile' => '1234567890', 'b' => 'bag'], 'name' => 'tom', 'age' => '23')
     * @result $object->name => 'tom' $object->profile['mobile'] => '1234567890'
     */
    public static function array2object($array) : object
    {
        return (object) $array;
    }

    /**
     * all array to object
     * @param $array
     * @return object|mixed
     *
     * @example array('profile' => ['mobile' => '1234567890', 'b' => 'bag'], 'name' => 'tom', 'age' => '23')
     * @result $object->name => 'tom' $object->profile->mobile => '1234567890'
     */
    public static function arrays2object($array) : object
    {
        $json = json_encode($array);
        return json_decode($json);
    }

    /**
     * data to json
     * @param $data
     * @return string
     */
    public static function data2json($data) : string
    {
        if (is_array($data) || is_object($data)) {
            return json_encode($data);
        } else {
            return json_encode([]);
        }
    }

    /**
     * data to string
     * @param $data
     * @return string
     */
    public static function data2string($data) : string
    {
        if (is_array($data) || is_object($data)) {
            return json_encode($data);
        } else {
            return json_encode([]);
        }
    }

    /**
     * object to json
     * @param $object
     * @return string
     */
    public static function object2json($object) : string
    {
        if (is_object($object)){
            return json_encode($object);
        } else {
            return json_encode([]);
        }
    }

    /**
     * json to object
     * @param $json
     * @return object|mixed
     *
     * @example $object->name => 'tom'
     */
    public static function json2object($json) : object
    {
        return json_decode($json);
    }

    /**
     * array to json
     * @param $array
     * @return string
     */
    public static function array2json($array) : string
    {
        if (is_array($array)){
            return json_encode($array);
        } else {
            return json_encode([]);
        }
    }

    /**
     * json to object
     * @param $json
     * @return object|mixed
     *
     * @example $object['name'] => 'tom'
     */
    public static function json2array($json) : object
    {
        return json_decode($json, true);
    }

    /**
     * data to xml
     * @param $data
     * @param $element
     * @return string
     *
     * @example array('profile' => ['mobile' => '1234567890', 'b' => 'bag'], 'name' => 'tom', 'age' => '23')
     * @result '<?xml version="1.0"?><xml><name>John</name><age>23</age><profile><b>bag</b><mobile>1234567890</mobile></profile></xml>';
     */
    public static function data2xml($data, $element = '<xml></xml>') : string
    {
        try {
            $xml = new \SimpleXMLElement($element);

            array_walk_recursive($data, function ($value, $key) use ($xml) {
                $xml->addChild($key, $value);
            });

            return $xml->asXML();
        } catch (\Exception $e) {
            throw new CustomException($e->getMessage);
        }
    }

    /**
     * xml to data
     * @param $string
     * @return array
     *
     * @example '<?xml version="1.0"?><xml><name>John</name><age>23</age><profile><b>bag</b><mobile>1234567890</mobile></profile></xml>';
     * @result array('profile' => ['mobile' => '1234567890', 'b' => 'bag'], 'name' => 'tom', 'age' => '23')
     */
    public static function xml2data($string) : array
    {
        $xml = simplexml_load_string($string);
        return json_decode(json_encode((array)$xml), true);
    }

    /**
     * string to array
     * @param string $string
     * @param $delimiter
     * @return array
     *
     * @example "apple,banana,cherry" => ["apple", "banana", "cherry"]
     */
    public static function string2array(string $string, $delimiter = ',') : array
    {
        if (!is_string($string)) return $string;
        return explode($delimiter, $string);
    }

    /**
     * array to string
     * @param array $array
     * @param $delimiter
     * @return string
     *
     * @example ["apple", "banana", "cherry"] => "apple,banana,cherry"
     */
    public static function array2string(array $array, $delimiter = ',') : string
    {
        if (!is_array($array)) return $array;
        return implode($delimiter, $array);
    }



}
