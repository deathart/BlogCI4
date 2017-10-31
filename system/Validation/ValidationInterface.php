<?php namespace CodeIgniter\Validation;

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014-2017 British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	CodeIgniter Dev Team
 * @copyright	2014-2017 British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 3.0.0
 * @filesource
 */
use CodeIgniter\HTTP\RequestInterface;

interface ValidationInterface
{

	/**
	 * Runs the validation process, returning true/false determing whether
	 * or not validation was successful.
	 *
	 * @param array  $data		The array of data to validate.
	 * @param string $group		The pre-defined group of rules to apply.
	 *
	 * @return bool
	 */
	public function run(array $data, string $group = null): bool;

	//--------------------------------------------------------------------

	/**
	 * Check; runs the validation process, returning true or false
	 * determining whether or not validation was successful.
	 *
	 * @param mixed    $value  Value to validation.
	 * @param string   $rule   Rule.
	 * @param string[] $errors Errors.
	 *
	 * @return bool True if valid, else false.
	 */
	public function check($value, string $rule, array $errors = []): bool;

	//--------------------------------------------------------------------

	/**
	 * Takes a Request object and grabs the data to use from its
	 * POST array values.
	 *
	 * @param \CodeIgniter\HTTP\RequestInterface $request
	 *
	 * @return \CodeIgniter\Validation\ValidationInterface
	 */
	public function withRequest(RequestInterface $request): ValidationInterface;

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------

	/**
	 * Stores the rules that should be used to validate the items.
	 *
	 * @param array $rules
	 *
	 * @return \CodeIgniter\Validation\ValidationInterface
	 */
	public function setRules(array $rules): ValidationInterface;

	//--------------------------------------------------------------------

	/**
	 * Checks to see if the rule for key $field has been set or not.
	 *
	 * @param string $field
	 *
	 * @return bool
	 */
	public function hasRule(string $field): bool;

	//--------------------------------------------------------------------
	//--------------------------------------------------------------------
	// Errors
	//--------------------------------------------------------------------

	/**
	 * Returns the error for a specified $field (or empty string if not set).
	 *
	 * @param string $field
	 *
	 * @return string
	 */
	public function getError(string $field): string;

	//--------------------------------------------------------------------

	/**
	 * Returns the array of errors that were encountered during
	 * a run() call. The array should be in the followig format:
	 *
	 *    [
	 *        'field1' => 'error message',
	 *        'field2' => 'error message',
	 *    ]
	 *
	 * @return array
	 */
	public function getErrors(): array;

	//--------------------------------------------------------------------

	/**
	 * Sets the error for a specific field. Used by custom validation methods.
	 *
	 * @param string $alias
	 * @param string $error
	 *
	 * @return \CodeIgniter\Validation\ValidationInterface
	 */
	public function setError(string $alias, string $error): ValidationInterface;

	//--------------------------------------------------------------------
	// Misc
	//--------------------------------------------------------------------

	/**
	 * Resets the class to a blank slate. Should be called whenever
	 * you need to process more than one array.
	 *
	 * @return mixed
	 */
	public function reset(): ValidationInterface;

	//--------------------------------------------------------------------
}
