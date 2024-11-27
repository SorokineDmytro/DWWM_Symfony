<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route("/menu")]
class MenuController extends AbstractController
{
    #[Route('/', name: 'app_menu')]
    public function index(MenuRepository $mr): Response
    {
        $menu = $this->showRowsMenu($mr);
        return $this->render('menu/index.html.twig', [
            'title' => 'Liste Menus',
            'menu' => $menu,
        ]);
    }

    //------------------------------AFFICHAGE
    #[Route("/affichage",name:"app_menu_affichage",methods:["GET"])]
    public function afficherMenu(MenuRepository $mr){ // la fonctionne qui affiche le menu du site
        $menus=$mr->findBy([],['parent'=>'ASC','rang'=>'ASC']);
        $menu=$this->getMenu(null,0,$menus);
        return $this->render("menu/menu.html.twig",[
            'title'=>'Affichage Menu',
            'menu'=>$menu,
        ]);
    } 

    public function getMenu($parent, $niveau, $menus) { // la fonctionne qui creer le menu du syte en ul/li
        $html = "";
        $niveau_precedent = 0;
        if ($niveau == 0) {
            $html .= "<ul class='nav navbar-nav'>";
        }
        foreach ($menus as $menu) {
            $menu_id = $menu->getId();
            $menu_parent = $menu->getParent();
            $menu_libelle = $menu->getLibelle();
            $menu_url = $menu->getUrl();
            $menu_role = $menu->getRole();
            $menu_icone = $menu->getIcone();
            $enfants = count ($menu->getMenus());
            if($parent == $menu_parent && ($this->isGranted($menu_role) || $menu_role=="ROLE_USER")) {
                if ($niveau_precedent != $niveau) {
                    $html .= "<ul class='dropdown-menu mx-2 bg-dark border-light'>";
                }
                if($niveau == 0) {
                    $text = "text-light fs-4";
                    $drop = "dropdown";
                } else {
                    $text = "text-light fs-4";
                    $drop = "dropend ";
                }
                if ($enfants) {
                    $html .= "<li class='nav-item $drop mx-2'><a href='$menu_url' class='nav-link $text dropdown-toggle' data-bs-toggle='dropdown' data-bs-auto-close='outside'><i class='$menu_icone me-2'></i>$menu_libelle</a>";
                } else {
                    $html .= "<li class='nav-item mx-2'><a href='$menu_url' class='nav-link text-light fs-4'><i class='$menu_icone me-2'></i>$menu_libelle</a>";
                }
                $niveau_precedent = $niveau;
                $html .= $this->getMenu($menu, $niveau+1, $menus);
            }
        }
        if ($niveau == 0) {
            $html .= "</ul>";
        } else if ($niveau_precedent == $niveau) {
            $html .= "</ul></li>";
        } else {
            $html .= "</li>";
        }
        return $html;
    }

    // -----------------------------CRUD
    public function showRowsMenu($mr){
        $menus=$mr->findBy([],['parent' => 'ASC', 'rang' => 'ASC']);
        $rows=$this->getRowsMenu(null,0,$menus);
        return $rows;
    } 

    public function getRowsMenu($parent,$niveau,$menus){  // fonction recursive
        $html='';

        foreach($menus as $menu){
            $menu_id=$menu->getId();
            $menu_parent=$menu->getParent();
            $menu_rang=$menu->getRang();
            $menu_libelle=$menu->getLibelle();
            $menu_url=$menu->getUrl();
            $menu_role=$menu->getRole();
            $menu_icone=$menu->getIcone();
            if($menu_parent==$parent){
                $espace='';
                for($i=1;$i<=$niveau;$i++){
                    $nbsp="";
                    for($j=1;$j<=10;$j++){
                        $nbsp.="&nbsp;";
                    }
                    $espace.=$nbsp;
                }
                if($niveau==0){
                    $text='fs-5 fw-bold';
                }else{
                    $text='fs-6';
                }
                if($menu_icone){
                    $i="<i class='$menu_icone'></i>";
                }else{
                    $i="";
                }
                $menu_libelle="$espace $menu_libelle";
                $html.="
                    <tr>
                        <td class='w05 text-center'><input type='checkbox' id='$menu_id' name='choix' onclick='onlyOne(this)' ></td>
                        <td class='w05 text-center $text' >$menu_rang</td>
                        <td class='w30 text-center $text' >$menu_libelle</td>
                        <td class='w20 text-center'>$menu_url</td>
                        <td class='w20 text-center'>$menu_role</td>
                        <td class='w20 text-center'>$i $menu_icone</td>
                    </tr>
                ";
                $html.=$this->getRowsMenu($menu,$niveau +1 , $menus);

            }
        }
        return $html;

    } 

    #[Route("/new/{parent_id}",name:"app_menu_new",methods:["GET","POST"])]
    public function new($parent_id,EntityManagerInterface $em,Request $request){
        $parent_id=(int) $parent_id;
        $menu=new Menu;
        if($parent_id){
            $parent=$em->getRepository(Menu::class)->find($parent_id);
            $menu->setParent($parent);
        }
        $form = $this->createForm(MenuType::class,$menu);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($menu);
            $em->flush();
            return $this->redirectToRoute("app_menu");
        }
        return $this->render("menu/form.html.twig",[
            'title' => 'Creation Menu',
            'form' => $form,
        ]);
    } 

    #[Route("/show/{id}",name:"app_menu_show",methods:["GET"])]
    public function show(Menu $menu, EntityManagerInterface $em, ) {
        return $this->render("menu/show.html.twig",[
            'title' => 'Affichage Menu',
            'menu' => $menu
        ]);
    }

    #[Route("/update/{id}",name:"app_menu_update",methods:["GET","POST"])]
    public function update(Menu $menu, EntityManagerInterface $em, Request $request) {
        $form = $this->createForm(MenuType::class,$menu);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($menu);
            $em->flush();
            return $this->redirectToRoute("app_menu");
        }
        return $this->render("menu/form.html.twig",[
            'title' => 'Modification Menu',
            'form' => $form,
        ]);
    }

    #[Route("/delete/{id}",name:"app_menu_delete",methods:["GET"])]
    public function delete($id, EntityManagerInterface $em) {
        $menu = $em->getRepository(Menu::class)->find($id); // find avec $id sans utiliser DependencyInjection Menu
        $enfants = $menu->getMenus();
        foreach($enfants as $enfant) {
            $em->remove($enfant);
        }
        $em->remove($menu);
        $em->flush();
        return $this->redirectToRoute("app_menu");
    }

}
