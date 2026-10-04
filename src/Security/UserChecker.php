<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
// use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserChecker implements UserCheckerInterface
{
    /**
     * Our goal is: only users who have verified their email during the registration proces can
     * access to the webpage. We must here check for this verification.
     *
     * @param UserInterface $user
     * @return void
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if(!$user instanceof User) {
            return;
        }

        // if(!$user->isVerified()) {
        //     throw new CustomUserMessageAccountStatusException(
        //         'You need to verify your email address before accessing this page.'
        //     );
        // }
    }

    public function checkPostAuth(UserInterface $user, ?TokenInterface $token = null): void
    {
        // Add your post-authentication checks here
    }
}