<?php

declare(strict_types=1);

namespace Test17\Test17\Model;

use Test17\Test17\Api\GreetingServiceInterface;

class GreetingService implements GreetingServiceInterface
{
	public function getMessage(string $name): string
{
	return 'Hi '.$name.', welcome to Magento service contracts.';
}
}
