<?php
/**
 * Created by Formateur
 * Date: 07/07/2017
 * Time: 12:29
 */

namespace Service;

use Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;


/**
 * Class UserManager
 * @package Service
 */
class UserManager
{
    /**
     * @var Session
     */
    private $session;

    /**
     * UserManager constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param string $plainPassword
     * @return string
     */
    public function encodePassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }

    /**
     * @param string $plainPassword
     * @param string $encodedPassword
     * @return bool
     */
    public function verifyPassword($plainPassword, $encodedPassword)
    {
        return password_verify($plainPassword, $encodedPassword);
    }

    /**
     * @param User $user
     */
    public function login(User $user)
    {
        $this->session->set('user', $user);
    }

    public function logout()
    {
        $this->session->remove('user');
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->session->get('user');
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        if ($this->session->has('user')) {
            return $this->session->get('user')->getFullName();
        }

        return '';
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->session->has('user') && $this->session->get('user')->isAdmin();
    }
}
