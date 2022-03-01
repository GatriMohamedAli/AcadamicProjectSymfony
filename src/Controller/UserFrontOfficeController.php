<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Monolog\Logger;
use phpDocumentor\Reflection\Types\Null_;
use Psr\Log\LoggerInterface;
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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserFrontOfficeController extends AbstractController
{
    /**
     * @Route("/",name="userFront")
     */
//$env:MERCURE_PUBLISHER_JWT_KEY='!ChangeMe!'; $env:MERCURE_SUBSCRIBER_JWT_KEY='!ChangeMe!'; .\mercure.exe run -config Caddyfile.dev

    public function index(Request $request){
        return $this->render('/FrontOffice/index.html.twig');
    }

    /**
     * @Route("/testJson" , name="testjson")
     */
    public function jsonTest(Request $request,NormalizerInterface $normalizer, LoggerInterface $logger ):Response{
        $user=new User();
        $request->get("Agent");
        //dd(gettype(strval($query)));
        $user->setEmail("mohamed@live.com");
        $user->setUsername("edi");
        if(strcmp($request->get("Agent"),"mobile")==0) $user->setUsername("MOBILLLE");

        $user->setTelephone("20202020");
        $jsonContent=$normalizer->normalize($user,'json');
        return new Response(json_encode($jsonContent));
    }

}
