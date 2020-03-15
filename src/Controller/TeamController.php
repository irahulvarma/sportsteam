<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Team;
use App\Form\TeamType;

/*
 * 
 * TeamController.*
 * 
 */

class TeamController extends AbstractFOSRestController
{
   /**
    * Lists all Teams.
    * @Rest\Get("/api/teams")
    *
    * @return Response
    */
    public function getTeams()
    {
        $repository = $this->getDoctrine()->getRepository(Team::class);
		$teams = $repository->findall();
		return $this->handleView($this->view($teams));
    }
    
    /**
	 * Create Team.
	 * @Rest\Post("/api/teams")
	 *
	 * @return Response
	 */
	 public function postTeam(Request $request)
	 {
		$team = new Team();
	
		$data = json_decode($request->getContent(), true);
		
		if ( ($data['name'] != '') && ($data['logoURI'] != '') )  {
		  $em = $this->getDoctrine()->getManager();
		  $team->setName($data['name']);
		  $team->setLogoURI($data['logoURI']);
		  $team->setCreatedAt(new \DateTime());
		  $team->setUpdatedAt(new \DateTime());
		  $em->persist($team);
		  $em->flush();
		  return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
		}
		return $this->handleView($this->view(['status' => 'error'], Response::HTTP_CREATED));
	 }
	 
	 /**
	 * Update Team.
	 * @Rest\Patch("/api/teams/{id}")
	 *
	 * @return Response
	 */
	 public function patchTeam(Request $request, int $id)
	 {
		$team = $this->getDoctrine()->getRepository(Team::class)->find($id);
		
		$data = json_decode($request->getContent(), true);
		
		if ( ($data['name'] != '') && ($data['logoURI'] != '') )  {
		  $em = $this->getDoctrine()->getManager();
		  $team->setName($data['name']);
		  $team->setLogoURI($data['logoURI']);		  
		  $team->setUpdatedAt(new \DateTime());
		  $em->persist($team);
		  $em->flush();
		  return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
		}
		return $this->handleView($this->view(['status' => 'error'], Response::HTTP_CREATED));
	 }
	 
	 /**
	 * Delete Team.
	 * @Rest\Delete("/api/teams/{id}")
	 *
	 * @return Response
	 */
	 public function deleteTeam(Request $request, int $id)
	 {
		$team = $this->getDoctrine()->getRepository(Team::class)->find($id);
		
		if ( $team )  {
		  $em = $this->getDoctrine()->getManager();
		  $em->remove($team);		 
		  $em->flush();
		  return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
		}
		return $this->handleView($this->view(['status' => 'error'], Response::HTTP_CREATED));
	 }
    
    
}
