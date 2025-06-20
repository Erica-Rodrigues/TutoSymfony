<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class RecipeController extends AbstractController
{
    #[Route('/recette', name: 'app_recipe_index')]
    public function index(Request $request, RecipeRepository $repository, EntityManagerInterface $em): Response
    {
        $recipes = $repository->findAll();
        // $recipes = $repository->findRecipeDurationLowerThan(60);
        // dd($recipes);
        // return new Response("Bienvenue sur la page des recettes !!!");

        //création de recette
        // $recipe = new Recipe();
        // $recipe->setTitle('Omelette')
        //     ->setSlug('omelette')
        //     ->setContent('Prenez des oeufs, cassez les et ensuite battez les en rajoutant du sel.')
        //     ->setDuration(6)
        //     ->setCreatedAt(new DateTimeImmutable())
        //     ->setUpdatedAt(new DateTimeImmutable());
        // $em->persist($recipe);

        //exo 12
        // $recipe = new Recipe();
        // $recipe->setTitle('Pain Perdu')
        // ->setSlug('pain-perdu')
        // ->setContent('Fouetter les oeufs avec le sucre et le lait, y tremper les tranches de pain. Les cuires à la poêle dans du beurre en les faisant dorer de chaque côté, ou, les cuire au four : beurrer légèrement un plat à gratin, y répartir les tranches, verser le reste du mélange (ajouter du sucre si envie), laisser cuire à 180°C (thermostat 6) jusqu\'à que les tranches soient dorées.')
        // ->setDuration(30)
        // ->setCreatedAt(new DateTimeImmutable())
        // ->setUpdatedAt(new DateTimeImmutable());
        // $em->persist($recipe);

        // $recipe = new Recipe();
        // $recipe->setTitle('Brownies')
        // ->setSlug('brownies')
        // ->setContent('Ingrédients : 250g chocolat pâtissier 150g de beurre 150g de sucre 60g de farine 1 sachet de sucre vanillé 3 oeufs Etape 1 : Faites fondre le chocolat cassé en morceaux avec le beurre. Etape 2 : Pendant ce temps, battez les oeufs avec le sucre jusqu\'à ce que le mélange blanchisse. Etape 3 : Ajoutez la farine, le sucre vanillé, et ajoutez le chocolat. Etape 4 : Versez le tout dans un moule, et enfournez à 180°C (thermostat 6)pendant 15 min...')
        // ->setDuration(25)
        // ->setCreatedAt(new DateTimeImmutable())
        // ->setUpdatedAt(new DateTimeImmutable());
        // $em->persist($recipe);
        // $em->flush();

        //modification de recette
        // $recipes[3]->setTitle('Omelette');

        //exo 13
        // $recipes[4]->setTitle('Brownies')
        //           ->setSlug('brownies')
        //           ->setContent('Ingrédients : 250g chocolat pâtissier 150g de beurre 150g de sucre 60g de farine 1 sachet de sucre vanillé 3 oeufs Etape 1 : Faites fondre le chocolat cassé en morceaux avec le beurre. Etape 2 : Pendant ce temps, battez les oeufs avec le sucre jusqu\'à ce que le mélange blanchisse. Etape 3 : Ajoutez la farine, le sucre vanillé, et ajoutez le chocolat. Etape 4 : Versez le tout dans un moule, et enfournez à 180°C (thermostat 6)pendant 15 min...')
        //           ->setDuration(25);
        // $em->flush();

        //suppression recette
        //$em->remove($recipes[3]);

        // exo 14
        // $em->remove($recipes[5]);
        // $em->flush();

        //récuperer nos recettes sans appeler le RecipeRepository
        //$recipes = $em->getRepository(Recipe::class)->findAll();

        return $this->render('recipe/index.html.twig',[
            'recipes' => $recipes
        ]);
    }

    #[Route('/recette/{slug}-{id}', name: 'app_recipe_show', requirements:['id'=>'\d+', 'slug'=>'[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id, RecipeRepository $repo): Response
    {
        // dd($request);
        // dd($request->attributes->getInt('id'),$request->attributes->get('slug'));
        // dd($slug,$id);
        // return new Response("Bienvenue sur la page " . $request->query->get('recette','des recettes !!!'));
        // return new Response("Recette numéro ". $id . " : ". $slug);

        // $recipe = $repo->findOneBy(['slug' => $slug]);
        // dd($recipe);

        $recipe = $repo->find($id);
        if($recipe->getSlug() !== $slug){
            return $this->redirectToRoute('app_recipe_show',['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
        }
        return $this->render('recipe/show.html.twig',[
            // 'slug' => $slug,
            // 'id' => $id,
            // 'user' => [
            //     'firstname' => 'Erica',
            //     'lastname' => 'Rodrigues'
            // ],
            'recipe' => $recipe
        ]);

        // version sans l'import de JsonResponse
        // return $this->json([
        //     'id' => $id,
        //     'slug' => $slug
        // ]);

        // version avec l'import de JsonResponse
        // return new JsonResponse([
        //     'id' => $id,
        //     'slug' => $slug
        // ]);
    }

    #[Route(path : '/recette/{id}/edit', name : 'app_recipe_edit')]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em): Response{
        //cette methode prend en premier paramètre le formulaire que l'on souhaite utiliser
        //en second paramètre elle prend les donées
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        // dd($recipe);
        if($form->isSubmitted() && $form->isValid()){
            $recipe->setUpdatedAt(new DateTimeImmutable());
            $em->flush();
            $this->addFlash('success', 'La recette a bien été modifiée');
            return $this->redirectToRoute('app_recipe_show',['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
        }
        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'monForm' => $form
        ]);
    }

    #[Route(path:'/recette/create', name : 'app_recipe_create')]
    public function create(Request $request, EntityManagerInterface $em) : Response{
        $recipe = new Recipe;
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $recipe->setUser($this->getUser())
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setUpdatedAt(new DateTimeImmutable());
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'La recette '. $recipe->getTitle().' a bien été créée');
            return $this->redirectToRoute('app_recipe_index');
        }
        return $this->render('recipe/create.html.twig', [
            'monForm' => $form
        ]);
    }

    #[Route(path:'/recette/{id}/delete', name : 'app_recipe_delete')]
    public function delete(Recipe $recipe, EntityManagerInterface $em) : Response{
        $titre = $recipe->getTitle();
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('info', 'La recette '.$titre.' a bien été supprimée');
        return $this->redirectToRoute('app_recipe_index');
    }

}
