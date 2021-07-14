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
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LinkController extends AbstractController
{

    /**
     * @Route("/", name="link_new", methods={"GET","POST"})
     */
    public function new(Request $request, LinkRepository $linkRepository, ValidatorInterface $validator): Response
    {
        $link = new Link();
        $links = $linkRepository->findAll();
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($link->getIdentifier())){
                do {
                    $identifier = $this->generateIdentifier();
                    $link->setIdentifier($identifier);
                    $identifierErrors = $validator->validate($link, null, 'identifier');
                } while (count($identifierErrors) > 0);
            }
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

    /**
     * @Route("/link/{id}/edit", name="link_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Link $link, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($link->getIdentifier())){
                do {
                    $identifier = $this->generateIdentifier();
                    $link->setIdentifier($identifier);
                    $identifierErrors = $validator->validate($link, null, 'identifier');
                } while (count($identifierErrors) > 0);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('link_show', ['identifier' => $link->getIdentifier()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('link/edit.html.twig', [
            'link' => $link,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/link/{id}", name="link_delete", methods={"POST"})
     */
    public function delete(Request $request, Link $link): Response
    {
        if ($this->isCsrfTokenValid('delete'.$link->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($link);
            $entityManager->flush();
        }

        return $this->redirectToRoute('link_new', [], Response::HTTP_SEE_OTHER);
    }


    private function generateIdentifier()
    {
        $length = mt_rand(5,9);
        $charList = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charListLength = strlen($charList);
        $randomString = '';
        for($i = 0; $i < $length; $i++) {
            $randomCharacter = $charList[mt_rand(0, $charListLength - 1)];
            $randomString .= $randomCharacter;
        }
 
        return $randomString;
    }
}
