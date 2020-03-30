<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings", name="admin_booking_index")
     */
    public function index(BookingRepository $repo)
    {
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findAll()
        ]);
    }

    /**
     * Permet d'éditer une réservation
     * 
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     *
     * @return Response
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager){

        $form= $this->createForm(AdminBookingType::class, $booking);//$booking, pour que le formulaire soit pré-rempli avec les infos du booking sélectionné. 

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $booking->setAmount(0);

            $manager->persist($booking); //cette ligne n'est pas nécessaire puisqu'il s'agit d'un booking qui existe déjà, donc a déjà été pris en compte par le manager.
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation n° {$booking->getId()} a bien été modifiée."
            );

            return $this->redirectToRoute('admin_booking_index');
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }
    /**
     * Permet de supprimer une réservation
     * 
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     *
     * @param Booking $booking
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Booking $booking, EntityManagerInterface $manager){

        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation n° {$booking->getId()} a bien été supprimée"
        );

        return $this->redirectToRoute('admin_booking_index');
    }
}
