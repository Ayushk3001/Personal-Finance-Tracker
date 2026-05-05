<?php

namespace Utils;

class Validator
{
    private $data;
    private $errors = [];

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * Validate required field
     */
    public function required($field, $message = null)
    {
        if (empty($this->data[$field])) {
            $this->errors[$field] = $message ?? "$field is required";
        }
        return $this;
    }

    /**
     * Validate email
     */
    public function email($field, $message = null)
    {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message ?? "$field must be a valid email";
        }
        return $this;
    }

    /**
     * Validate minimum length
     */
    public function minLength($field, $length, $message = null)
    {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field] = $message ?? "$field must be at least $length characters";
        }
        return $this;
    }

    /**
     * Validate maximum length
     */
    public function maxLength($field, $length, $message = null)
    {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field] = $message ?? "$field must not exceed $length characters";
        }
        return $this;
    }

    /**
     * Validate numeric value
     */
    public function numeric($field, $message = null)
    {
        if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = $message ?? "$field must be numeric";
        }
        return $this;
    }

    /**
     * Validate positive number
     */
    public function positive($field, $message = null)
    {
        if (!empty($this->data[$field]) && $this->data[$field] <= 0) {
            $this->errors[$field] = $message ?? "$field must be positive";
        }
        return $this;
    }

    /**
     * Validate date format
     */
    public function date($field, $format = 'Y-m-d', $message = null)
    {
        if (!empty($this->data[$field])) {
            $date = \DateTime::createFromFormat($format, $this->data[$field]);
            if (!$date) {
                $this->errors[$field] = $message ?? "$field must be a valid date";
            }
        }
        return $this;
    }

    /**
     * Check if validation passed
     */
    public function passes()
    {
        return count($this->errors) === 0;
    }

    /**
     * Get errors
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
