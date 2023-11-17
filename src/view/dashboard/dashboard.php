<?php

use app\src\core\components\charts\BarChartH;
use app\src\core\components\charts\LineChart;
use app\src\core\components\charts\NumBlock;
use app\src\core\components\charts\NumBlockChart;
use app\src\core\components\charts\PercentageBlock;
use app\src\core\components\charts\PieChart;
use app\src\core\components\charts\SVGBarChart;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;

$this->title = 'Dashboard';
/** @var array $statsDistributionDomaine
 * @var array $statsDensembleStageEtAlternance
 * @var array $statsCandidaturesParMois
 * @var array $offres
 * @var array $pourcentageEtudiantsConventionCetteAnnee
 * @var array $top5DomainesPlusDemandes
 * @var array $moyenneCandidaturesParOffreParDomaine
 */

$currentTab = $_COOKIE['currentTab'] ?? 'tab1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tab'])) {
        $currentTab = $_POST['tab'];
        setcookie('currentTab', $currentTab, time() + (86400 * 30), '/');
    }
}

$totalOffers = array_sum(array_column($statsDistributionDomaine, 'nombreoffres'));

echo '<script type="text/javascript" src="/resources/js/animate-counter.js"></script>';


function OffreCard($offre)
{

    $mail = substr($offre['emailentreprise'], 0, 6) . '…' . substr($offre['emailentreprise'], -3);
    $description = substr($offre['description'], 0, 50) . '…';
    $date = date_create($offre['datecreation']);
    $date = date_format($date, 'd/m/Y');


    echo <<<EOT
<div class="h-[125px] w-full border-gray-200 border rounded-[8px] flex flex-col justify-between items-center bg-white relative px-6 py-3 mb-5">
                <div class="w-full flex flex-row items-center justify-between">
                    <a class="font-semibold hover:underline" href="/offres/{$offre['idoffre']}">Offre n°{$offre['idoffre']}</a>
                    <p class="text-zinc-400 font-light text-xs">{$date}</p>
                </div>
                <div class="w-full flex flex-row justify-between items-center text-xs">
                    <div class="grid grid-cols-2 max-w-[200px]">
                    <p class="font-light text-zinc-400">Nom Entreprise : </p>
                                        <a class="hover:underline" href="/entreprises/{$offre['idutilisateur']}">{$offre['nomentreprise']}</a>
                    <p class="font-light text-zinc-400">Sujet : </p>
                                        <p>{$offre['sujet']}</p>

                    <p class="font-light text-zinc-400">Thematique : </p>
                                        <p>{$offre['thematique']}</p>
                    </div>
                    <div class="h-full w-[1px] bg-zinc-300"></div>
                    <div class="grid grid-cols-2 max-w-[200px]">
                    <p class="font-light text-zinc-400">Description : </p>
                                        <p>{$description}</p>
                    <p class="font-light text-zinc-400">Email : </p>
                                        <p><a class="hover:underline" href="mailto:{$offre['emailentreprise']}">{$mail}</a></p>

                    <p class="font-light text-zinc-400">Telephone : </p>
                    <p><a class="hover:underline" href="tel:{$offre['telephoneentreprise']}">{$offre['telephoneentreprise']}</a></p>
                    </div>
                    <div class="h-full w-[1px] bg-zinc-300"></div>
                    <div class="rounded-[8px] border overflow-hidden">
                        <iframe src="https://yandex.com/map-widget/v1/?ll=3.850089%2C43.634623&mode=search&sll=10.854186%2C49.182076&sspn=73.212891%2C44.753627&text={$offre['adresse']}&z=16.97" width="150" height="60"  allowfullscreen></iframe>
                    </div>
                </div>
                <div class="h-[50px] w-full border-zinc-200 border rounded-[8px] bg-zinc-50 absolute bottom-0 scale-[98%] translate-y-[8px] -z-[1]"></div>
                <div class="h-[50px] w-full border-zinc-200 border rounded-[8px] bg-zinc-100  absolute bottom-0 scale-[95%] translate-y-[16px] -z-[2]"></div>
</div>
EOT;
}

?>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        <?php if ($currentTab === 'tab1'): ?>

        setTimeout(() => {
            const bars = document.querySelectorAll("#tab1 .animated-bar");
            bars.forEach(bar => {
                const percentageWidth = bar.getAttribute('data-percentage');
                bar.style.width = percentageWidth + '%';
            });
        }, 100);

        <?php endif; ?>
    });
    document.addEventListener('DOMContentLoaded', animatePieSlices);
</script>
<div class="w-full flex md:flex-row flex-col justify-between items-start gap-4 mx-auto pt-12 pb-24">
    <div class="flex flex-col gap-4 w-full md:max-w-[400px]">
        <form method="POST" action=""
              class="relative text-[14px] w-full m-0 bg-zinc-50 isolate justify-around flex gap-2 flex-row overflow-hidden h-14 border rounded-2xl text-[#1A2421] backdrop-blur-xl p-2 [ border-[1px] border-solid border-black  border-opacity-10 ] [ shadow-black/5 shadow-2xl ]">
            <button type="submit" name="tab" value="tab1"
                    class="py-2 px-4 rounded-[8px] w-full <?php if ($currentTab === 'tab1') echo 'bg-white text-black shadow-zinc-500/5 shadow-md font-semibold'; else echo ' text-zinc-400'; ?>">
                Statistiques
            </button>
            <button type="submit" name="tab" value="tab2"
                    class="py-2 px-4 rounded-[8px] w-full <?php if ($currentTab === 'tab2') echo 'bg-white text-black shadow-zinc-500/5 shadow-md font-semibold'; else echo ' text-zinc-400'; ?>">
                Actions
            </button>
            <button type="submit" name="tab" value="tab3"
                    class="py-2 px-4 rounded-[8px] w-full <?php if ($currentTab === 'tab3') echo 'bg-white text-black shadow-zinc-500/5 shadow-md font-semibold'; else echo ' text-zinc-400'; ?>">
                Favoris
            </button>
        </form>
        <div class="relative w-full bg-zinc-50 isolate overflow-hidden border rounded-2xl text-[#1A2421] backdrop-blur-xl [ p-2 md:p-4 ] [ border-[1px] border-solid border-black  border-opacity-10 ] [ shadow-black/5 shadow-2xl ]">
            <div id="tab1"
                 class="<?php if ($currentTab === 'tab1') echo 'flex flex-col gap-2 items-start justify-start'; else echo 'hidden'; ?>">
                <div class="w-full flex flex-row justify-center items-center gap-2">
                    <?php
                    $percentageBlock = new PercentageBlock('Conventions signées', 'General', 'BUT2', 'BUT3', $pourcentageEtudiantsConventionCetteAnnee['pourcentageannuel'], $pourcentageEtudiantsConventionCetteAnnee['pourcentageannuel2'], $pourcentageEtudiantsConventionCetteAnnee['pourcentageannuel3']);
                    $percentageBlock->render();
                    ?>
                </div>
                <div class="w-full h-[1px] bg-zinc-300"></div>
                <div class="container mx-auto pt-4">
                    <h2 class="text-md font-bold text-zinc-700 mb-4">Offres de stage et d'alternance</h2>
                    <div class="w-full flex flex-row justify-center items-center gap-2">
                        <?php
                        $numBlockStage = new NumBlock('Stages', $statsDensembleStageEtAlternance['nombreoffresstageactives']);
                        $numBlockStage->render();

                        $numBlockAlternance = new NumBlock('Alternances', $statsDensembleStageEtAlternance['nombreoffresalternanceactives']);
                        $numBlockAlternance->render();

                        $numBlockPourvues = new NumBlock('Pourvues', $statsDensembleStageEtAlternance['nombrepositionspourvues']);
                        $numBlockPourvues->render();
                        ?>
                    </div>
                </div>
                <div class="w-full h-[1px] bg-zinc-300"></div>
                <div class="container mx-auto pt-4">
                    <h2 class="text-md font-bold text-zinc-700 mb-4">Candidatures validées par mois</h2>
                    <div class="w-full min-h-[100px]">
                        <?php
                        $size = [
                            'width' => 300,
                            'height' => 150,
                            'padding' => 20
                        ];
                        $lineChart = new LineChart($statsCandidaturesParMois, 'mois', 'nombrecandidatures', true, $size, '#910aff');
                        $lineChart->render();
                        ?>
                    </div>
                </div>
                <div class="w-full h-[1px] bg-zinc-300"></div>
                <div class="container mx-auto pt-4">
                    <h2 class="text-md font-bold text-zinc-700 mb-4">Top 5 des domaines les plus demandés</h2>
                    <div class="w-full">
                        <?php
                        $barChart = new BarChartH($top5DomainesPlusDemandes);
                        $barChart->render();
                        ?>

                    </div>
                </div>
                <div class="w-full h-[1px] bg-zinc-300"></div>
                <div class="container mx-auto pt-4">
                    <h2 class="text-md font-bold text-zinc-700 mb-4">Distribution des offres par Domaine</h2>
                    <div class="w-full">

                        <?php
                        $pieChart = new PieChart($statsDistributionDomaine, $totalOffers, ["#3d348b", "#7678ed", "#f7b801", "#f18701", "#f35b04", "#f542ad", "#4296f5", "#42f54e", "#f5df42"], true);
                        $pieChart->render();
                        ?>
                    </div>
                </div>
            </div>
            <div id="tab2" class="<?php if ($currentTab === 'tab2') echo 'flex'; else echo 'hidden'; ?>">
                <div class="flex flex-col w-full gap-2 items-center justify-center">
                    <?php if (!Application::isGuest()): ?>
                        <?php if (!Auth::has_role(Roles::ChefDepartment)): ?>
                            <a href="/offres"
                               class="flex items-center text-2xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800 border rounded-[8px] bg-white hover:bg-zinc-50 text-center w-full hover:underline p-4">Offres</a>
                        <?php else: ?>
                            <a href="/utilisateurs"
                               class="flex items-center text-2xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800 border rounded-[8px] bg-white hover:bg-zinc-50 text-center w-full hover:underline p-4">Gestion
                                roles</a>
                        <?php endif; ?>
                        <?php if (!Auth::has_role(Roles::Enterprise, Roles::Tutor, Roles::ChefDepartment)): ?>
                            <a href="/entreprises"
                               class="flex items-center text-2xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800 border rounded-[8px] bg-white hover:bg-zinc-50 text-center w-full hover:underline p-4">Entreprises</a>
                        <?php endif; ?>
                        <?php if (Auth::has_role(Roles::Student, Roles::Teacher, Roles::Tutor, Roles::Enterprise)): ?>
                            <a href="/candidatures"
                               class="flex items-center text-2xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800 border rounded-[8px] bg-white hover:bg-zinc-50 text-center w-full hover:underline p-4">Candidatures</a>
                        <?php endif; ?>
                        <?php if (Auth::has_role(Roles::Enterprise)): ?>
                            <a href="/offres/create"
                               class="flex items-center text-2xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800 border rounded-[8px] bg-white hover:bg-zinc-50 text-center w-full hover:underline p-4">Créer
                                une
                                offre</a>
                            <a href="/ListeTuteurPro"
                               class="flex items-center text-2xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800 border rounded-[8px] bg-white hover:bg-zinc-50 text-center w-full hover:underline p-4">Tuteurs</a>
                        <?php endif; ?>
                        <?php if (Auth::has_role(Roles::Manager, Roles::Staff)): ?>
                            <a href="/utilisateurs"
                               class="flex items-center text-2xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800 border rounded-[8px] bg-white hover:bg-zinc-50 text-center w-full hover:underline p-4">Utilisateurs</a>
                            <a href="/candidatures"
                               class="flex items-center text-2xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800 border rounded-[8px] bg-white hover:bg-zinc-50 text-center w-full hover:underline p-4">Candidatures</a>
                            <a href="/ListeTuteurPro"
                               class="flex items-center text-2xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800 border rounded-[8px] bg-white hover:bg-zinc-50 text-center w-full hover:underline p-4">Tuteurs</a>
                            <a href="/importer"
                               class="flex items-center text-2xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800 border rounded-[8px] bg-white hover:bg-zinc-50 text-center w-full hover:underline p-4">Import</a>
                        <?php endif; ?>
                        <?php if (Auth::has_role(Roles::Student)): ?>
                            <a href="/explicationSimu"
                               class="flex items-center text-2xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800 border rounded-[8px] bg-white hover:bg-zinc-50 text-center w-full hover:underline p-4">Simulateur</a>
                        <?php endif; ?>
                        <?php if (Auth::has_role(Roles::Enterprise, Roles::Student, Roles::Manager, Roles::Staff)): ?>
                            <a href="/conventions"
                               class="flex items-center text-2xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800 border rounded-[8px] bg-white hover:bg-zinc-50 text-center w-full hover:underline p-4">Conventions</a>
                        <?php endif; endif; ?>
                </div>
            </div>
            <div id="tab3" class="<?php if ($currentTab === 'tab3') echo 'flex'; else echo 'hidden'; ?>"></div>
        </div>
    </div>
    <div class="relative grow isolate overflow-hidden w-full gap-4 flex flex-col bg-zinc-50 border rounded-2xl text-[#1A2421] backdrop-blur-xl [ p-8 md:p-10 lg:p-10 ] [ border-[1px] border-solid border-black  border-opacity-10 ] [ shadow-black/5 shadow-2xl ]">
        <div id="tab1"
             class="<?php if ($currentTab === 'tab1') echo 'flex flex-col w-full gap-2 items-start justify-start'; else echo 'hidden'; ?>">
            <div class="w-full flex flex-row justify-around items-center bg-white border rounded-[8px]">
                <?php
                $numBlockChart1 = new NumBlockChart('Example', $statsDensembleStageEtAlternance['nombreoffresstageactives'], $statsCandidaturesParMois, 'mois', 'nombrecandidatures', '#f542ad');
                $numBlockChart2 = new NumBlockChart('Example', $statsDensembleStageEtAlternance['nombreoffresstageactives'], $statsCandidaturesParMois, 'mois', 'nombrecandidatures', '#4296f5');
                $numBlockChart3 = new NumBlockChart('Example', $statsDensembleStageEtAlternance['nombreoffresstageactives'], $statsCandidaturesParMois, 'mois', 'nombrecandidatures', '#42f54e');
                $numBlockChart1->render();
                echo '<span class="w-[1px] bg-zinc-300 h-[100px] mr-2"></span>';
                $numBlockChart2->render();
                echo '<span class="w-[1px] bg-zinc-300 h-[100px] mr-2"></span>';
                $numBlockChart3->render();
                ?>
            </div>
            <div class="w-full h-[1px] bg-zinc-300"></div>
            <div class="container mx-auto pt-4">
                <h2 class="text-md font-bold text-zinc-700 mb-4">Moyenne des candidatures par offre par domaine</h2>
                <div class="w-full ">
                    <?php
                    $size = [
                        'width' => 600,
                        'height' => 150,
                        'padding' => 20
                    ];
                    $svgbarChart = new SVGBarChart($moyenneCandidaturesParOffreParDomaine, 'thematique', 'moyennecandidaturesparoffre', true, $size, '#f56642');
                    $svgbarChart->render();
                    ?>
                </div>
            </div>
            <div class="w-full h-[1px] bg-zinc-300"></div>
            <div class="flex flex-col mt-2 w-full">
                <div class="w-full flex flex-row justify-between items-center">
                    <h2 class="text-md font-bold text-zinc-700 mb-4">Dernières offres ajoutées</h2>
                    <a href="/offres"
                       class="text-sm font-medium text-zinc-700 hover:text-zinc-500 flex flex-row items-center justify-end group">
                    <span class="group w-full pr-2 duration-150 group-hover:underline inline-flex items-center justify-end gap-2  text-sm font-medium text-zinc-600">
                            Voir plus</span>
                    </a>
                </div>

                <div class="w-full flex flex-col gap-2 justify-start items-start max-h-[635px] overflow-y-scroll pr-3">
                    <div class="flex flex-row justify-start items-start w-full h-full">
                        <div class="w-full flex flex-col justify-start items-start">
                            <?php
                            $counter = 0;
                            foreach ($offres as $offre) {
                                echo <<<EOT
<div class="flex flex-row justify-between items-center gap-2 w-full relative">
EOT;
                                if ($counter != 0) {
                                    echo <<<EOT
<span class="w-[1px] bg-zinc-300 h-[150px] mr-2"></span>
<span class="w-6 h-6 border-zinc-300 border-b border-l rounded-bl-2xl absolute -translate-y-3 -z-[1]"></span>

EOT;
                                } else {
                                    echo <<<EOT
<span class="w-[1px] bg-zinc-300 h-[75px] translate-y-14 mr-2"></span>
<span class="w-6 h-6 border-zinc-300 border-t border-l rounded-tl-2xl absolute translate-y-3 -z-[1]"></span>
EOT;
                                }

                                $counter += 1;
                                echo <<<EOT
<div class="min-w-[18px] min-h-[18px] max-w-[18px] flex justify-center items-center max-h-[18px] border border-zinc-300 bg-white rounded-full">
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"  class="w-3 h-3 stroke-zinc-500">
  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
</svg>

</div>
EOT;

                                OffreCard($offre);
                                echo <<<EOT
</div>
EOT;
                            }
                            ?>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>