<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/person", name="person_")
 */
class PersonController extends AbstractController
{
    private $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }
    
    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, LoggerInterface $logger): Response
    {
        $person = new Person();
        $personForm = $this->createForm(PersonType::class, $person);

        $personForm->handleRequest($request);
        if ($personForm->isSubmitted() && $personForm->isValid()) {
            $person = $personForm->getData();

            $logger->info('Create a new person!');
            $logger->info($this->serializer->serialize($person, 'json'));
            return $this->redirectToRoute('homepage');
        }

        return $this->render('person/_create.html.twig', [
            'person_form' => $personForm->createView(),
        ]);
    }
}
