<?php
    namespace App\Controller;
    use App\Entity\Users;
    #use App\EventsBus;
    #use App\CommandBus;
    #use App\Message\Event\UserRegistered;
    #use App\MessageHandler\SendEmailVerification;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Messenger\MessageBusInterface;
    use App\Message\SmsNotification;
    use Symfony\Component\Messenger\Envelope;
    use Symfony\Component\Messenger\Stamp\SerializerStamp;
    use Symfony\Component\Messenger\Handler\HandlersLocator;
    use Symfony\Component\Messenger\MessageBus;
    use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

    class UsersController extends Controller{

        /**
         * @Route("/",name="All_users")
         * @Method({"GET"})
         */
        public function index(){
          //  return new Response('');
         // $users=['User 1','User 2'];
         $users=$this->getDoctrine()->getRepository(Users::class)
         ->findAll();
            
         return $this->render('users/index.html.twig',array('users'=>$users));
        }

          /**
         * @Route("/users/create")
         */
        public function create(){
            $entityManager=$this->getDoctrine()->getManager();
            $user=new Users();
            $user->setUsrName('User 1');
            $user->setEmail('Email1@gmail.com');
            $user->setPassword('Password1');
            $user->setGender('Male');
            $entityManager->persist($user);
            $entityManager->flush();

            return new Response('Saved in users with ID of ' .  $user->getId());

        }

         /**
         * @Route("/users/delete/{id}")
         * @Method({"DELETE"})
         */
        public function DELETE(Request $request,$id)
        {
            $user = $this->getDoctrine()->getRepository(Users::class)->find($id);
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            
            $response=new Response();
            $response->send();
        }
        
        /**
         * @Route("/users/new",name="new_user")
         * Method({"Get","POST"})
         */
        public function new(Request $request,MessageBusInterface $bus /*EventsBus $bus*/)
        {
            $user=new Users();
            $form=$this->createFormBuilder($user)
            ->add('usrName',TextType::class,array(
                'attr'=> array('class'=> 'form-control'))
               
                )
            ->add('Email',TextType::class,array(
             'required'=>false,
             'attr'=> array('class'=> 'form-control')    
            ))
            ->add('Password',TextType::class,array(
                'attr'=> array('class'=> 'form-control')))
            ->add('Gender',TextType::class,array(
                'attr'=> array('class'=> 'form-control')))
            ->add('Save',SubmitType::class,array(
                'label'=>'Create',
                'attr'=>array('calss'=>'btn btn-primary mt-3 form-control')
            ))
            ->getForm();
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $user=$form->getData();
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                //$bus->dispatch(new UserRegistered($user->getId()));
               
                $bus->dispatch(new SmsNotification($user));
                return $this->redirectToRoute('All_users');

            }
            return $this->render('users/new.html.twig',array(
                'form'=>$form->createView()
            ));
        }

     /**
         * @Route("/users/update/{id}",name="update_user")
         * Method({"PUT"})
         */
        public function update(Request $request,$id)
        {
            $user=new Users();
            $user = $this->getDoctrine()->getRepository(Users::class)->find($id);
            $form=$this->createFormBuilder($user)
            ->add('usrName',TextType::class,array(
                'attr'=> array('class'=> 'form-control'))
               
                )
            ->add('Email',TextType::class,array(
             'required'=>false,
             'attr'=> array('class'=> 'form-control')    
            ))
            ->add('Password',TextType::class,array(
                'attr'=> array('class'=> 'form-control')))
            ->add('Gender',TextType::class,array(
                'attr'=> array('class'=> 'form-control')))
            ->add('Save',SubmitType::class,array(
                'label'=>'Update',
                'attr'=>array('calss'=>'btn btn-primary mt-3')
            ))
            ->getForm();
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $user=$form->getData();
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->flush();
                return $this->redirectToRoute('All_users');
            }
            return $this->render('users/update.html.twig',array(
                'form'=>$form->createView()
            ));
        }

        /**
        * @Route("/users/{id}",name="User_Details")
        */
        public function ShowUser($id)
        {
            $user=$this->getDoctrine()->getRepository(Users::class)
            ->find($id);
            return $this->render('users/show.html.twig', array(
                'user'=>$user
            ));
        }

     
      
    }
