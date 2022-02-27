<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserFrontOfficeController extends AbstractController
{
    /**
     * @Route("/",name="userFront")
     */
//$env:MERCURE_PUBLISHER_JWT_KEY='!ChangeMe!'; $env:MERCURE_SUBSCRIBER_JWT_KEY='!ChangeMe!'; .\mercure.exe run -config Caddyfile.dev

    public function index(Request $request, HubInterface $hub){
        $update = new Update("https://example.com/users/dunglas", "".$request->getUri());
        $hub->publish($update);
        return $this->render('/FrontOffice/index.html.twig');
    }

}
