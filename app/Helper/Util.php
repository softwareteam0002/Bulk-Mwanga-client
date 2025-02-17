<?php


namespace App\Helper;


class Util
{
    const  TO_MNO = 1;
    const TO_BANK = 2;

    public static function generateRandom($length)
    {
        $date = new \DateTime('now');
        $date = $date->format('ymdHisv');
        return $date . bin2hex(random_bytes((min($length, 20) - 15) / 2));
    }


    public static function addPhonePrefix($phone)
    {
        return strlen($phone) <= 9 ? '255' . $phone : preg_replace('/^0/', '255', $phone);
    }

    public static function getFirstAndLastName($fullName): array
    {
        // Trim extra spaces and replace multiple spaces with a single space
        $fullName = preg_replace('/\s+/', ' ', trim($fullName));

        // Split the full name into parts
        $nameParts = explode(' ', $fullName);

        // Get first name (the first part)
        $firstName = $nameParts[0];

        // Get last name (everything after the first part is considered the last name)
        $lastName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';

        return ['firstName' => $firstName, 'lastName' => $lastName];
    }
}
