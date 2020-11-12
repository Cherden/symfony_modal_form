<?php

namespace App\Controller;

use App\Entity\Meeting;
use App\Form\MeetingType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/meeting", name="meeting_")
 */
class MeetingController extends AbstractController
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
        $meeting = new Meeting();
        $meetingForm = $this->createForm(MeetingType::class, $meeting);

        $meetingForm->handleRequest($request);
        if ($meetingForm->isSubmitted() && $meetingForm->isValid()) {
            $meeting = $meetingForm->getData();

            

            $logger->info('Create a new meeting!');
            $logger->info($this->serializer->serialize($meeting, 'json'));
            return $this->redirectToRoute('homepage');
        }

        return $this->render('meeting/_create.html.twig', [
            'meeting_form' => $meetingForm->createView(),
        ]);
    }
}
