<?php
namespace TimeShow\Repository\Helpers;

use Illuminate\Support\Str;
class StringHelper
{

    /**
     * Determine whether the given string matches the given pattern (* Can be used as a wildcard)
     *
     * @param string $pattern
     * @param string $string
     * @return bool
     *
     * @example Str::is('foo*', 'foobar') => true
     * @example Str::is('baz*', 'foobar') => false
     */
    public static function is(string $pattern, string $string) : bool
    {
        return Str::is($pattern, $string);
    }

    /**
     * Determine if the given string is Ascii
     *
     * @param string $string
     * @return bool
     */
    public static function isAscii(string $string) : bool
    {
        return Str::isAscii($string);
    }

    /**
     * Determine if the given string is a valid JSON
     *
     * @param string $string
     * @return bool
     */
    public static function isJson(string $string) : bool
    {
        return Str::isJson($string);
    }

    /**
     * Determine if the given string is a valid URL
     *
     * @param string $string
     * @return bool
     */
    public static function isUrl(string $string) : bool
    {
        return Str::isUrl($string);
    }

    /**
     * Determine if the given string is a valid ULID
     *
     * @param string $string
     * @return bool
     */
    public static function isUlid(string $string) : bool
    {
        return Str::isUlid($string);
    }

    /**
     * Determine if the given string is a valid UuID
     *
     * @param string $string
     * @return bool
     */
    public static function isUuid(string $string) : bool
    {
        return Str::isUuid($string);
    }

    /**
     * Check if the string is empty
     *
     * @param string $string
     * @return bool
     */
    public static function isEmpty(string $string) : bool
    {
        return Str::empty($string);
    }

    /**
     * Check if the string is not empty
     *
     * @param string $string
     * @return bool
     */
    public static function isNotEmpty(string $string) : bool
    {
        return Str::empty($string);
    }

    /**
     * Check if the string is a pure number
     *
     * @param string $string
     * @return bool
     */
    public static function isNumeric(string $string) : bool
    {
        return Str::is_numeric($string);
    }

    /**
     * Check if the string is in Base64 encoding format
     *
     * @param string $string
     * @return bool
     */
    public static function isBase64(string $string) : bool
    {
        $decoded = base64_decode($string, true);
        return ($decoded !== false && base64_encode($decoded) === $string);
    }

    /**
     * Check if the string is a valid email address
     *
     * @param string $string
     * @return bool
     */
    public static function isEmail(string $string) : bool
    {
        return filter_var($string, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Check if the string is a valid IP address
     *
     * @param string $string
     * @return bool
     */
    public static function isIp(string $string) : bool
    {
        return filter_var($string, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Check if the string is in a valid date format
     *
     * @param string $string
     * @param string $format
     * @return bool
     */
    public static function isDate(string $string, string $format = 'Y-m-d') : bool
    {
        $datetime = DateTime::createFromFormat($format, $string);
        return $datetime && $datetime->format($format) === $string;
    }

    /**
     * Check if the string is in a valid time format
     *
     * @param string $string
     * @param string $format
     * @return bool
     */
    public static function isTime(string $string, string $format = 'Y-m-d H:i:s') : bool
    {
        $datetime = DateTime::createFromFormat($format, $string);
        return $datetime && $datetime->format($format) === $string;
    }

    /**
     * Convert the given string to ascii encoding
     *
     * @param string $string
     * @return string
     */
    public static function ascii(string $string) : string
    {
        return Str::ascii($string);
    }

    /**
     * Convert the given string to Base64 encoding
     *
     * @param string $string
     * @return string
     */
    public static function toBase64(string $string) : string
    {
        return Str::toBase64($string);
    }

    /**
     * Generate a random string of specified length
     * @param int $length
     * @return string
     */
    public static function random(int $length = 16) : string
    {
        return Str::random($length);
    }

    /**
     * Generate a quick random string of specified length
     * @param int $length
     * @return string
     */
    public static function quickRandom(int $length = 16) : string
    {
        return Str::quickRandom($length);
    }


    /**
     *
     * @param string $string
     * @return string
     *
     * @example ab-cd_ef => AbCdEf
     */
    public static function toUpperCamelCase(string $string) : string
    {
        return Str::studly($string);
    }

    /**
     *
     * @param string $string
     * @return string
     *
     * @example ab-cd_ef => abCdEf
     */
    public static function toLowerCamelCase(string $string) : string
    {
        return Str::camel($string);
    }

    /**
     * Format string as camel hump naming
     * @param string $string
     * @return string
     *
     * @example ab-cd_ef => ab-cdEf
     */
    public static function toCamelCase(string $string) : string
    {
        $parts = explode('_', $string);
        foreach ($parts as $index => $part) {
            if ($index > 0) {
                $parts[$index] = ucfirst($part);
            }
        }
        return implode('', $parts);
    }

    /**
     * Generate a random string of specified length
     * @param int $length
     * @return string
     */
    public static function generateRandomString(int $length = 10) : string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Generate a random number of specified length
     * @param int $length
     * @return int
     */
    public static function generateRandomNumber(int $length = 6) : int
    {
        $min = pow(10, $length-1);
        $max = pow(10, $length) -1;
        return mt_rand($min, $max);
    }

    /**
     * Generate a random number of specified length
     * @param int $length
     * @return string
     */
    public static function generateRandomNumberString(int $length = 10) : string
    {
        $characters = '0123456789';

        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * Generate a random letter of specified length
     * @param int $length
     * @return string
     */
    public static function generateRandomLetter(int $length = 10, string $type = '') : string
    {
        if ($type == 'lower'){
            $characters = 'abcdefghijklmnopqrstuvwxyz';
        }elseif($type == 'upper'){
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }else{
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * Generate a random letter of specified length
     * @param int $length
     * @return string
     */
    public static function generateRandomNumberLetter(int $length = 10, string $type = '') : string
    {
        if ($type == 'lower'){
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        }elseif($type == 'upper'){
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }else{
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * Generate a password string
     * @param int $lenght
     * @param $digitsType
     * @param $lowercaseType
     * @param $uppercaseType
     * @param $specialType
     * @param $excludeType
     * @return string
     */
    public static function getPasswordString(int $lenght = 16, $digitsType = true, $lowercaseType = true, $uppercaseType = true, $specialType = false, $excludeType = true) : string
    {
        $digits = array_flip(range('0', '9'));
        $lowercase = array_flip(range('a', 'z'));
        $uppercase = array_flip(range('A', 'Z'));
        $special = array_flip(str_split('!@#$%^&*()_+=-}{[}]\|;:<>?/'));

        if ($excludeType){
            $exclude = array_flip(str_split('OoIl|'));
            $lowercase = array_diff_key($lowercase, $exclude);
            $uppercase = array_diff_key($uppercase, $exclude);
            $special = array_diff_key($special, $exclude);
        }

        //$combined  = array_merge($digits, $lowercase, $uppercase, $special);
        $combined = array_merge($digitsType?$digits:[], $lowercaseType?$lowercase:[], $uppercaseType?$uppercase:[], $specialType?$special:[]);

        //return $combined;
        $password  = str_shuffle(implode(array_rand($combined, $lenght)));
        return $password;
    }

    /**
     * Format string as camel hump naming
     * @param string $string
     * @return string
     *
     * @example foo_bar => fooBar
     */
    public static function camel(string $string) : string
    {
        return Str::camel($string);
    }

    /**
     * Truncate string
     *
     * @param string $string
     * @param int $length
     * @param string $suffix
     * @return string
     *
     * @example abcdefghijklmnopqrstuvwxyz => abcdefgh...
     */
    public static function truncate(string $string, int $length = 30, string $suffix = '...') : string
    {
        if (strlen($string) > $length) {
            return substr($string, 0, $length - strlen($suffix)) . $suffix;
        }

        return $string;
    }

    /**
     * Convert a string to a snake like complex form
     *
     * @param string $string
     * @return string
     *
     * @example UserProfile => user_profiles
     */
    public static function snakePlural(string $string) : string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $string)) . 's';
    }

    /**
     * Convert the given string
     *
     * @param string $string
     * @return string
     *
     * @example UserProfile => user_profile
     */
    public static function toSnake(string $string) : string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $string));
    }

    /**
     * Convert the given string
     *
     * @param string $string
     * @param string $delimiter
     * @return string
     *
     * @example UserProfile-M y-name-LiHua => user_profile-my-name-li_hua
     */
    public static function snake(string $string, string $delimiter = '_') : string
    {
        return Str::snake($string, $delimiter);
    }

    /**
     * Reverse the given string
     *
     * @param string $string
     * @return string
     *
     * @example Hello World => dlroW olleH
     */
    public static function reverse(string $string) : string
    {
        return Str::reverse($string);
    }

    /**
     * Method to generate a friendly URL for a given string
     *
     * @param string $string
     * @param string $separator
     * @return string
     *
     * @example tr::slug('Laravel 10 Framework', '-') => laravel-10-framework
     */
    public static function slug(string $string, string $separator = '-') : string
    {
        return Str::slug($string, $separator);
    }

    /**
     * Convert the given string
     *
     * @param string $string
     * @return string
     *
     * @example UserProfile-M y-name-LiHua =>user-profile--m-y-name--li-hua
     */
    public static function kebab(string $string) : string
    {
        return Str::kebab($string);
    }

    /**
     * Return the length of the given string
     *
     * @param string $string
     * @return int
     *
     * @example Laravel => 7
     */
    public static function length(string $string) : int
    {
        return Str::length($string);
    }

    /**
     * Truncate the given string to a specified length
     *
     * @param string $string
     * @param int $length
     * @param string $suffix
     * @return string
     *
     * @example abcdefghijklmnopqrstuvwxyz => abcdefghij...
     */
    public static function limit(string $string, int $length = 10, string $suffix = '') : string
    {
        return Str::limit($string, $length, $suffix);
    }

    /**
     * Convert the given string to uppercase
     *
     * @param string $string
     * @return string
     *
     * @example laravel => LARAVEL
     */
    public static function upper(string $string) : string
    {
        return Str::upper($string);
    }

    /**
     * Convert the given string to lowercase
     *
     * @param string $string
     * @return string
     *
     * @example LARAVEL => laravel
     */
    public static function lower(string $string) : string
    {
        return Str::lower($string);
    }

    /**
     * Return the given string with its first letter capitalized
     *
     * @param string $string
     * @return string
     *
     * @example foo bar => Foo bar
     */
    public static function ucfirst(string $string) : string
    {
        return Str::ucfirst($string);
    }

    /**
     * Split the given string into an array based on uppercase characters
     *
     * @param string $string
     * @return array
     *
     * @example FooBar => [0 => 'Foo', 1 => 'Bar']
     */
    public static function ucsplit(string $string) : array
    {
        return Str::ucsplit($string);
    }

    /**
     * Remove the specified string from the beginning and end of the given string
     *
     * @param string $string
     * @param string $before
     * @param string $after
     * @return string
     *
     * @example Str::wrap('Laravel', '"') => "Laravel"
     * @example Str::wrap('is', before: 'This ', after: ' Laravel!') => This is Laravel!
     */
    public static function wrap(string $string, string $before = '', string $after = '') : string
    {
        if ($before && $after){
            $newString = Str::wrap($string, before : $before, after : $after);
        } else {
            $newString =  Str::wrap($string, $before);
        }
        return $newString;
    }

    /**
     * Remove the specified string from the beginning and end of the given string
     *
     * @param string $string
     * @param string $first
     * @param string $end
     * @return string
     *
     * @example _Laravel_ => Laravel
     * @example {framework: "Laravel"} => framework: "Laravel"
     */
    public static function unwrap(string $string, string $first = '_', string $end = '') : string
    {
        return Str::unwrap($string, $first, $end);
    }

    /**
     * Generate a ULID
     *
     * @return string
     *
     * @example 11gd6r340bp37zj17nxb55yv43
     */
    public static function ulid() : string
    {
        return Str::ulid();
    }

    /**
     * Generate a UUID
     *
     * @return string
     *
     * @example f74f9eac-5258-45c2-bab7-ccb9b5ef74f9
     */
    public static function uuid() : string
    {
        return Str::uuid();
    }

    /**
     * Add the given value at the end of the string
     *
     * @param string $string
     * @param string $cap
     * @return string
     *
     * @example Str::finish('this/string', '/') => 'this/string/'
     * @example Str::finish('this/string/', '/') => 'this/string/'
     */
    public static function finish(string $string, string $cap = '') : string
    {
        return Str::finish($string, $cap);
    }

    /**
     * Add the given value at the end of the string
     *
     * @param string $string
     * @return mixed
     *
     * @example of('Taylor')->append(' Otwell') => Taylor Otwell
     * @example of('foo bar baz')->explode(' ') => ['foo', 'bar', 'baz']
     */
    public static function of(string $string) : mixed
    {
        return Str::of($string);
    }

}
