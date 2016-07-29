<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\User;

class UserEvents extends Event
{
	const NEW_USER_CREATED = 'new_user_created';
}