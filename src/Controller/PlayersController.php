<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Players;
use App\Entity\Team;
use App\Form\PlayersType;

/*
 * 
 * PlayersController.*
 * 
 */

class PlayersController extends AbstractFOSRestController
{
    /**
    * Lists all Teams.
    * @Rest\Get("/api/players/{id}")
    *
    * @return Response
    */
    public function getPlayers(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Team::class);
		$teams = $repository->findOneBy(['id' => $id]);
		return $this->handleView($this->view($teams->getPlayers()));
    }
    
    /**
	 * Create Player.
	 * @Rest\Post("/api/players")
	 *
	 * @return Response
	 */
	 public function postPlayers(Request $request)
	 {
		$player = new Players();
	
		$data = json_decode($request->getContent(), true);
		
		if ( ($data['firstName'] != '') && ($data['lastName'] != '') && ($data['playerImageURI'] != '') )  {
		  
		  $team = $this->getDoctrine()->getRepository(Team::class)->find($data['team_id']);
		  
		  $em = $this->getDoctrine()->getManager();
		  $player->setFirstName($data['firstName']);
		  $player->setLastName($data['lastName']);
		  $player->setPlayerImageURI($data['playerImageURI']); 
		  $player->setTeam($team);
		  $player->setCreatedAt(new \DateTime());
		  $player->setUpdatedAt(new \DateTime());
		  $em->persist($player);
		  $em->flush();
		  return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
		}
		return $this->handleView($this->view(['status' => 'error'], Response::HTTP_CREATED));
	 }
	 
	 /**
	 * Update Player.
	 * @Rest\Patch("/api/players/{id}")
	 *
	 * @return Response
	 */
	 public function patchPlayers(Request $request, int $id)
	 {
		$player = $this->getDoctrine()->getRepository(Players::class)->find($id);
		
		$data = json_decode($request->getContent(), true);
		
		if ( ($data['firstName'] != '') && ($data['lastName'] != '') && ($data['playerImageURI'] != '') )  {
		
		  $team = $this->getDoctrine()->getRepository(Team::class)->find($data['team_id']);
			
		  $em = $this->getDoctrine()->getManager();
		  $player->setFirstName($data['firstName']);
		  $player->setLastName($data['lastName']);
		  $player->setPlayerImageURI($data['playerImageURI']); 
		  $player->setTeam($team);
		  $player->setUpdatedAt(new \DateTime());
		  $em->persist($player);
		  $em->flush();
		  return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
		}
		return $this->handleView($this->view(['status' => 'error'], Response::HTTP_CREATED));
	 }
	 
	 /**
	 * Delete Player.
	 * @Rest\Delete("/api/players/{id}")
	 *
	 * @return Response
	 */
	 public function deletePlayers(int $id)
	 {
		$player = $this->getDoctrine()->getRepository(Players::class)->find($id);
		
		if ( $player )  {
		  $em = $this->getDoctrine()->getManager();
		  $em->remove($player);		 
		  $em->flush();
		  return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
		}
		return $this->handleView($this->view(['status' => 'error'], Response::HTTP_CREATED));
	 }
}
