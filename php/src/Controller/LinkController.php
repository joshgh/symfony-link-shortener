<?php

namespace App\Controller;

use App\Entity\Link;
use App\Form\LinkType;
use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LinkController extends AbstractController
{

    /**
     * @Route("/", name="link_new", methods={"GET","POST"})
     */
    public function new(Request $request, LinkRepository $linkRepository): Response
    {
        $link = new Link();
        $links = $linkRepository->findAll();
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($link);
            $entityManager->flush();

            return $this->redirectToRoute('link_show', ['identifier' => $link->getIdentifier()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('link/new.html.twig', [
            'link' => $link,
            'links' => $links,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/view/{identifier}", name="link_show", methods={"GET"})
     */
    public function show(Link $link): Response
    {
        return $this->render('link/show.html.twig', [
            'link' => $link,
        ]);
    }

    /**
     * @Route("/{identifier}", name="link_follow", methods={"GET"})
     */
    public function follow(Link $link): Response
    {
        $url = $link->getUrl();
        return new RedirectResponse($url);
    }

}
