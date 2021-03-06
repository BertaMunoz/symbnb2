<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class PaginationService{
   
    private $entityClass;
    private $limit = 10;  
    private $currentPage;
    private $manager;
    private $twig;
    private $route;
    private $templatePath; 

    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request, $templatePath){
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig = $twig; 
        $this->templatePath = $templatePath;
    }

    
    public function setRoute($route){
        $this->route = $route;
        return $this;
    }
    public function getRoute(){
        return $this->route;
    }

    public function display(){
        $this->twig->display($this->templatePath, [
           'page' => $this->currentPage,
           'pages' => $this->getPages(),
           'route' => $this->route
        ]);
        
    }

    public function getPages(){
        if(empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer, Utilisez la méthode setEntityClass() de votre objet PaginationService! ");
        }
        //connaître le total des enregistrements de la table
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        //Faire la division, l'arrondi et le renvoyer
        $pages = ceil($total / $this->limit);

        return $pages;
    }
    public function getData(){
        if(empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer, Utilisez la méthode setEntityClass() de votre objet PaginationService! ");
        }
        //calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        //Demander au repository de trouver les éléments
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);

        //Renvoyer les éléments en question
        return $data;
    }

    public function setEntityClass($entityClass){
        $this->entityClass = $entityClass; 
        return $this; 
    }

    public function getEntityClass(){
        return $this->entityClass; 
    }  

    public function setLimit($limit){
        $this->limit = $limit;
        return $this;
    }

    public function getlimit(){
        return $this->limit;
    }
    public function getPage() {
		return $this->currentPage;
	}
 
	public  function setPage($page) {
        $this->currentPage = $page;
        return $this;
	}
 
    public function getTemplatePath()
    {
        return $this->templatePath;
    }
 
    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;

        return $this;
    }
} 