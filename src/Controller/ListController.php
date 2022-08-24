<?php

namespace App\Controller;

use App\Entity\Lists;
use App\Form\ListType;
use App\Repository\ItemRepository;
use App\Repository\ListsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/list", name="list.")
 */
class ListController extends AbstractController
{
    /**
     * @Route("/", name="view")
     */
    public function index(ListsRepository $listRepository, ItemRepository $itemRepository): Response
    {
        $lists = $listRepository->findAll();
        $itemsArr = [];

        foreach($lists as $list){
            $itemsArr[$list->getId()] = $itemRepository->findItemsByList($list);
        }

        

        return $this->render('list/index.html.twig', [
            "lists" => $lists,
            "itemsArr" => $itemsArr
        ]);
    }

     /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Lists $list, ListsRepository $listRepository): JsonResponse
    {  
        //remove items associated with it
        $items = $list->getItems();
        foreach($items as $item){
            $list->removeItem($item);
        }

        $listRepository->remove($list,true);
        $this->addFlash("success","List deleted successfully");

        return $this->json([
            'success' => true
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, ListsRepository $listRepository):Response
    {
        $list = new Lists();
        $form = $this->createForm(ListType::class,$list);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $listRepository->add($list,true);
            $this->addFlash("success","New list created");

            return $this->redirect($this->generateUrl('list.view'));
        }

        return $this->renderForm('list/create.html.twig',[
            "create_list_form" => $form
        ]);
    }
}
