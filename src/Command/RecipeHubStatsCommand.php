<?php

namespace App\Command;

use App\Repository\CategorieRecetteRepository;
use App\Repository\IngredientRepository;
use App\Repository\RecetteRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:recipehub:stats',
    description: 'Affiche les statistiques de la plateforme de recettes',
)]
class RecipeHubStatsCommand extends Command
{
    public function __construct(
        private RecetteRepository $recetteRepo,
        private CategorieRecetteRepository $categorieRepo,
        private IngredientRepository $ingredientRepo,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('detail', null, InputOption::VALUE_NONE, 'Affiche le détail par catégorie')
            ->addOption('top', null, InputOption::VALUE_OPTIONAL, 'Top N recettes les plus longues', 3);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('📊 Statistiques RecipeHub');

        $recettes   = $this->recetteRepo->findAll();
        $publiees   = $this->recetteRepo->findBy(['publiee' => true]);
        $brouillons = array_filter($recettes, fn($r) => !$r->isPubliee());
        $ingredients = $this->ingredientRepo->findAll();

        // General stats
        $io->table(
            ['Statistique', 'Valeur'],
            [
                ['Total recettes',     count($recettes)],
                ['Recettes publiées',  count($publiees)],
                ['Brouillons',         count($brouillons)],
                ['Total ingrédients',  count($ingredients)],
                ['Temps préparation moyen', round(array_sum(array_map(fn($r) => $r->getTempsPreparation(), $recettes)) / max(count($recettes), 1)) . ' min'],
            ]
        );

        // Difficulty breakdown
        $difficulties = ['facile' => 0, 'moyen' => 0, 'difficile' => 0];
        foreach ($recettes as $r) {
            $difficulties[$r->getDifficulte()]++;
        }
        $io->section('Répartition par difficulté');
        $io->table(['Difficulté', 'Nombre'], array_map(fn($k, $v) => [$k, $v], array_keys($difficulties), $difficulties));

        // Detail by category
        if ($input->getOption('detail')) {
            $io->section('Répartition par catégorie');
            $categories = $this->categorieRepo->findAll();
            $rows = [];
            foreach ($categories as $cat) {
                $rows[] = [$cat->getIcone() . ' ' . $cat->getNom(), count($cat->getRecettes())];
            }
            $io->table(['Catégorie', 'Nombre de recettes'], $rows);
        }

        // Top N longest recipes
        $top = (int) $input->getOption('top');
        $sorted = $recettes;
        usort($sorted, fn($a, $b) => ($b->getTempsPreparation() + ($b->getTempsCuisson() ?? 0)) - ($a->getTempsPreparation() + ($a->getTempsCuisson() ?? 0)));
        $io->section('Top ' . $top . ' recettes les plus longues');
        $rows = [];
        foreach (array_slice($sorted, 0, $top) as $r) {
            $rows[] = [$r->getTitre(), ($r->getTempsPreparation() + ($r->getTempsCuisson() ?? 0)) . ' min'];
        }
        $io->table(['Recette', 'Temps total'], $rows);

        $io->success('Statistiques affichées avec succès !');
        return Command::SUCCESS;
    }
}
