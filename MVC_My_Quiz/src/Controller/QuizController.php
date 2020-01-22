<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\Question;
use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuizController extends AbstractController
{
    /**
     * @Route("/quiz", name="quiz")
     */
    public function index()
    {

        return $this->render('quiz/index.html.twig', [
            'controller_name' => 'QuizController',
        ]);
    }

    /**
     * @Route("/", name="home")
     */ 
    public function home() {
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();

        return $this->render('quiz/home.html.twig', array("categorie"=>$categorie));
    }

    /**
     * @Route("/quiz/{id}/{id2}", name="quiz2")
     */
    public function select(Request $request, $id, $id2){
        
        $userChoice = 0;
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        $categorie_id = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
        $question_id = $this->getDoctrine()->getRepository(Question::class)->find($id2);
        $question = $this->getDoctrine()->getRepository(Question::class)->findby(['idCategorie' => $id, 'id' => $id2]);
        $reponse = $this->getDoctrine()->getRepository(Reponse::class)->findby(['idQuestion' => $id2]);
        $value_reponse = $this->getDoctrine()->getRepository(Reponse::class)->findAll();

        $userChoice = $request->request->get("reponse_vali");

        $product = $this->getDoctrine()->getRepository(Reponse::class)->findOneBy([
            'reponseExpected' => 1,
            'idQuestion' =>  $id2
        ]);
        $BonnRep = $product->getReponse();

        $var = 0;

        if($BonnRep == $userChoice){
            $var = 1;
        } else if(!isset($userChoice)){
            $var = 0;
        } else if($BonnRep != $userChoice) {
            $var = 2;
        }

        return $this->render('quiz/index.html.twig',
            array('question' => $question, 'reponse' => $reponse, 'choix_user' => $userChoice, 'categorie_id' => $categorie_id, 'question_id' => $question_id, 'value_reponse' => $value_reponse, 'categorie'=>$categorie, 'var' => $var, 'bonRep' => $BonnRep)
       );
    }
}
