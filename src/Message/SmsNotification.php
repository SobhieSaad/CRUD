<?php
// src/Message/SmsNotification.php
namespace App\Message;

use App\Entity\Users;
class SmsNotification
{

  
    //private $content;
 
    /**
     * @var Users
     */
    private $user;

    /**
     * @param Users $user
     */
    public function __construct(Users $user)
    {
        $this->user = $user;
        //$this->content = $content;
    }

     /**
     * @return Users
     */
    public function getUser(): Users
    {
        return $this->user;
    }

    /**
     * @param Users $user
     */
    public function setUser(User $user) : void
    {
        $this->user = $user;
    }



   /* public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }*/

}