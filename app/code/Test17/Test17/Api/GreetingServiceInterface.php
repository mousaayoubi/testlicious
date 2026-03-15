<?php

declare(strict_types=1);

namespace Test17\Test17\Api;

interface GreetingServiceInterface
{
	/**
	 * Return a greeting message
	 *
	 * @param string $name
	 * @return string
	 */
	public function getMessage(string $name): string;
}
