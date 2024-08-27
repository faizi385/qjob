<?php

if (!function_exists('prepareContactData')) {
    /**
     * Prepare the contact data array.
     *
     * @param string $name
     * @param string $email
     * @param string $phone
     * @return array
     */
    function prepareContactData($name, $email, $phone)
    {
        return [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        ];
    }
}
