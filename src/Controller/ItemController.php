<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Lists;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/item", name="item.")
 */
class ItemController extends AbstractController
{
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Item $item,ItemRepository $itemRepository): JsonResponse
    {
        $itemRepository->remove($item,true);
        $this->addFlash("success","Item deleted successfully");

        return $this->json([
            'success' => true
        ]);
    }

    /**
     * @Route("/create/{list_id}", name="create")
     * 
     * @ParamConverter("list", options={"mapping" : {"list_id" : "id"}})
     */
    public function create(Request $request,ItemRepository $itemRepository, Lists $list):Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class,$item);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $position = 0;
            $placeAt = $form->get('placeAt')->getData();
            if($placeAt == 'top'){               
                $itemRepository->increasePosition($list);                
            }else{
                $maxPosArr = $itemRepository->getMaxPostion($list);
                if(count($maxPosArr)){
                    $position = $maxPosArr[0]['max_position'];
                    $position += 1;
                }               
            }

            $item->setPosition($position);
            $item->setList($list);
            $itemRepository->add($item,true);

            $this->addFlash("success", "Item added successfully to list: " . $list->getName());
            return $this->redirectToRoute("list.view");
        }

        return $this->renderForm('item/create_item.html.twig',[
            'form' => $form
        ]);
    }
}
